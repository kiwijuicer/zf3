<?php
declare(strict_types = 1);

namespace Core\Service;

class AdminUserService extends AbstractService
{
    public function getByUsername(string $username)
    {
        return $this->getGateway()->select(['admin_user_username' => $username])->current();
    }
}