<?php
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

$acl = new Acl();

$acl->addRole(new Role('guest'))
    ->addRole(new Role('member'))
    ->addRole(new Role('admin'));

$parents = ['guest', 'member', 'admin'];

$acl->addResource(new Resource(\Application\Controller\IndexController::class));

$acl->allow('admin', \Application\Controller\IndexController::class);