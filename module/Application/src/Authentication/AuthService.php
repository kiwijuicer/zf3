<?php
declare(strict_types=1);

namespace Application\Authentication;

use Core\Entity\AdminUser;
use Core\Service\AdminUserService;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

/**
 * Auth Service
 * @package Application\Authentication
 */
class AuthService
{
    const SESSION_NAMESPACE = 'AdminUserAuth';

    /**
     * Constructor for Auth Service
     */
    public function __construct()
    {
        $config = new SessionConfig();
        $config->setCacheLimiter('');

        $manager = Container::getDefaultManager();
        $manager->setConfig($config);

        $this->sessionContainer = new Container(self::SESSION_NAMESPACE, $manager);
        $this->authService = new AuthenticationService(new Session(self::SESSION_NAMESPACE, 'identity'));
    }

    /**
     * Tries to authenticate user with given credentials
     *
     * @param  string $username
     * @param  string $password
     * @return \Zend\Authentication\Result
     */
    public function authenticate(string $username, string $password, AdminUserService $adminUserService)
    {
        return $this->authService->authenticate(new Adapter($username, $password, $adminUserService));
    }

    /**
     * Returns whether or not a logged in user exists
     *
     * @return boolean
     */
    public function hasUser()
    {
        return $this->authService->hasIdentity();
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->authService->clearIdentity();
    }

    /**
     * Returns user
     *
     * @return AdminUser
     */
    public function getUser()
    {
        if (!$this->authService->hasIdentity()) {
            throw new \RuntimeException('No authenticated user found');
        }

        return $this->authService->getIdentity();
    }

    /**
     * Get Acl Schema
     *
     * @return string
     */
    public function getSchema()
    {
        return $this->getUser()->getRole();
    }
}