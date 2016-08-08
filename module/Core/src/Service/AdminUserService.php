<?php
declare(strict_types = 1);

namespace Core\Service;

use Core\Entity\AdminUser;

class AdminUserService extends AbstractService
{
    public function getForUsernameAndPassword(string $username, string $password)
    {
        /** @var AdminUser $adminUser */
        $adminUser = $this->getGateway()->select(['admin_user_username' => $username, 'admin_user_status' => 'enabled'])->current();

        if (password_verify($password, $adminUser->getPassword())) {
            return $adminUser;
        }

        return null;
    }
}