<?php
declare(strict_types=1);

namespace Application\Controller\Factory;

use Application\Controller\IndexController;

/**
 * Index Controller Factory
 *
 * @package Application\Controller\Factory
 */
class IndexControllerFactory
{
    /**
     * Returns Index Controller
     *
     * @param $serviceManager
     * @return \Application\Controller\IndexController
     */
    public function __invoke($serviceManager) : IndexController
    {
        return new IndexController(
            $serviceManager->get(\Application\Authentication\AuthService::class),
            $serviceManager->get(\Application\Authentication\Acl::class)
        );
    }
}