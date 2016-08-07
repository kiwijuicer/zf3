<?php
declare(strict_types=1);

namespace Application\Controller\Factory;

use Application\Controller\AuthenticationController;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Authentication Controller Factory
 *
 * @package Application\Controller\Factory
 */
class AuthenticationControllerFactory
{
    /**
     * Returns Authentication Controller
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceManager
     * @return \Application\Controller\AuthenticationController
     */
    public function __invoke(ServiceLocatorInterface $serviceManager) : AuthenticationController
    {
        return new AuthenticationController(
            $serviceManager->get(\Application\Authentication\AuthService::class),
            $serviceManager->get(\Application\Authentication\Acl::class)
        );
    }
}