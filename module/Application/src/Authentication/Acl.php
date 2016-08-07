<?php
declare(strict_types=1);

namespace Application\Authentication;

/**
 * Acl
 * @package Application\Authentication
 */
class Acl extends \Zend\Permissions\Acl\Acl
{
    /**
     * Constructor for Acl
     */
    public function __construct()
    {
        // Roles
        $this->addRole('user');
        $this->addRole('admin', 'user');

        // Resources
        $this->addResource(\Application\Controller\IndexController::class);

        // Rules
        $this->allow('user', \Application\Controller\IndexController::class);
    }
}

