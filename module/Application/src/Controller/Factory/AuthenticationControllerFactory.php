<?php
declare(strict_types=1);

namespace Application\Controller\Factory;


use Application\Controller\AuthenticationController;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationControllerFactory
{
    public function __invoke(ServiceLocatorInterface $serviceManager) : AuthenticationController
    {
        return new AuthenticationController(
            $serviceManager->get(\Application\Authentication\AuthService::class),
            $serviceManager->get(\Application\Authentication\Acl::class),
            $serviceManager->get(\Core\Service\AdminUserService::class)
        );
    }
}