<?php
declare(strict_types=1);

namespace Core;

use Core\Log\Logging;
use Zend\Config\Config;
use Core\Log\Logger;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface;

/**
 * Module
 *
 * @package Core
 */
class Module
{
    /**
     * Returns the module configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Returns the configuration for the auto loader for the module
     *
     * @return array
     */
    public function getAutoloaderConfig(): array
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * Init for service managers or application parts required
     *
     * @param MvcEvent $mvcEvent
     * @return void
     * @throws \Zend\Log\Exception\InvalidArgumentException
     */
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        // Get the application service manager and register loggers
        $sm = $mvcEvent->getApplication()->getServiceManager();
        // Set config in static logger
        Logging::setConfig((new Config($sm->get('Config')))->log);

        Logger::registerErrorHandler(Logging::getLogger(Logging::LOGGER_ERROR_HANDLER));
        Logger::registerExceptionHandler(Logging::getLogger(Logging::LOGGER_EXCEPTION_HANDLER));

        // Get the application event manager and attach loggers
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'logExceptions'], 100);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'logExceptions'], 100);
    }

    /**
     * Logs exceptions
     *
     * @param  MvcEvent $e
     * @return void
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\Http\Exception\InvalidArgumentException
     */
    public function logExceptions(MvcEvent $e)
    {
        // Do nothing if no error in the event
        $error = $e->getError();
        if ('' === $error) {
            return;
        }

        // Do nothing if the result is a response object
        $result = $e->getResult();
        if ($result instanceof ResponseInterface) {
            return;
        }

        if ($error === Application::ERROR_EXCEPTION) {

            /* @var $exception \Exception */
            $exception = $e->getParam('exception');

            $sm = $e->getApplication()->getServiceManager();
            Logging::getLogger(Logging::LOGGER_ERROR_HANDLER)->crit($exception);
        }
    }

}