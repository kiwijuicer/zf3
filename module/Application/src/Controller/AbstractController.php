<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\AuthService;
use Core\Service\AdminUserService;
use Zend\Authentication\Adapter\Http;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class AbstractController extends AbstractActionController
{
    protected $authService;
    protected $adminUserService;

    public function __construct(AuthService $authService, AdminUserService $adminUserService)
    {
        $this->authService = $authService;
        $this->adminUserService = $adminUserService;
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws \DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            throw new \DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $this->authenticate();

//        if (! $this->authService->hasIdentity()) {
//            return $this->redirect()->toRoute('login');
//        }

        $actionResponse = $this->$method();

        $e->setResult($actionResponse);

        return $actionResponse;
    }

    protected function authenticate()
    {
        $config = array(
            'accept_schemes' => 'basic',
            'realm'          => 'KiwiJuicer',
            'nonce_timeout'  => 3600,
        );

        $httpAuth = new Http($config);


    }
}
