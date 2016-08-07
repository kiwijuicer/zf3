<?php
declare(strict_types=1);

namespace Application\Authentication\Factory;

use Application\Authentication\AuthService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Auth Service Factory
 *
 * @package Application\Authentication\Factory
 */
class AuthServiceFactory
{
    public function __invoke(ServiceLocatorInterface $serviceLocator) : AuthService
    {
        return new AuthService($serviceLocator->get(\Core\Service\AdminUserService::class));
    }
}