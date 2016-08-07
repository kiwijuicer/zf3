<?php
declare(strict_types=1);

namespace Application\Controller\Factory;


use Application\Controller\IndexController;

class IndexControllerFactory
{
    public function __invoke($serviceManager)
    {
        return new IndexController(
            $serviceManager->get(\Application\Authentication\AuthService::class),
            $serviceManager->get(\Core\Service\AdminUserService::class)
        );
    }
}