<?php
declare(strict_types = 1);

namespace Core\Service;

use Core\Entity\AdminUser;

/**
 * Admin User Service
 *
 * @package Core\Service
 */
class AdminUserService extends AbstractService
{
    /**
     * Returns admin user by given username and password
     * 
     * @param string $username
     * @param string $password
     * @return \Core\Entity\AdminUser|null
     */
    public function getForUsernameAndPassword(string $username, string $password)
    {
        /** @var AdminUser $adminUser */
        $adminUser = $this->getGateway()->select(['username' => $username, 'status' => 'enabled'])->current();

        if (password_verify($password, $adminUser->getPassword())) {
            return $adminUser;
        }

        return null;
    }
}