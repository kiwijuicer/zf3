<?php

namespace Application\Authentication;

use Core\Entity\AdminUser;
use Core\Service\AdminUserService;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Adapter
 *
 * @package Application\Authentication
 */
class Adapter implements AdapterInterface
{
    /**
     * Username
     *
     * @var string
     */
    private $username = null;

    /**
     * Password
     *
     * @var string
     */
    private $password = null;

    /**
     * Admin User Service
     *
     * @var AdminUserService
     */
    protected $adminUserService;

    /**
     * Sets username and password for authentication
     *
     * @param $username
     * @param $password
     * @param AdminUserService $adminUserService
     */
    public function __construct($username, $password, AdminUserService $adminUserService)
    {
        $this->username = $username;
        $this->password = $password;
        $this->adminUserService = $adminUserService;
    }

    /**
     * Performs an authentication attempt
     *
     * @return Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        $user = $this->adminUserService->getForUsernameAndPassword($this->username, $this->password);

        if ($user instanceof AdminUser) {
            return new Result(Result::SUCCESS, $user);
        }

        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null);
    }
}