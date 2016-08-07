<?php
declare(strict_types=1);

namespace Application\Authentication\Resolver;

use Core\Entity\AdminUser;
use Core\Service\AdminUserService;
use Zend\Authentication\Adapter\Http\ResolverInterface;

class AdminUserResolver implements ResolverInterface
{
    /**
     * Admin User Service
     *
     * @var AdminUserService
     */
    protected $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function resolve($username, $realm, $password = null)
    {
        /** @var AdminUser $adminUser */
        $adminUser = $this->adminUserService->getByUsername($username);

        echo $adminUser->getUsername(); exit();
    }
}