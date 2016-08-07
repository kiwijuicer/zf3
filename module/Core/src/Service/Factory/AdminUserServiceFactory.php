<?php
declare(strict_types=1);

namespace Core\Service\Factory;


use Core\Service\AdminUserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminUserServiceFactory
{
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $tableGateway = $serviceLocator->get(\Core\Service\AbstractTableGateway::class);

        return new AdminUserService($tableGateway);
    }
}