<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                        'auth'       => true,
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                        'auth'       => true,
                    ],
                ],
            ],

            'login' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => \Application\Controller\AuthenticationController::class,
                        'action'     => 'login',
                        'auth'       => false,
                    ],
                ],
            ],

            'facebook-login' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/facebook-login',
                    'defaults' => [
                        'controller' => \Application\Controller\AuthenticationController::class,
                        'action'     => 'facebook-login',
                        'auth'       => false,
                    ],
                ],
            ],

            'facebook-remove' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/facebook-remove',
                    'defaults' => [
                        'controller' => \Application\Controller\AuthenticationController::class,
                        'action'     => 'facebook-remove',
                        'auth'       => false,
                    ],
                ],
            ],

            'logout' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => \Application\Controller\AuthenticationController::class,
                        'action'     => 'logout',
                        'auth'       => false,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Application\Authentication\AuthService::class => \Application\Authentication\Factory\AuthServiceFactory::class,
        ],
        'invokables' => [
            \Application\Authentication\Acl::class => \Application\Authentication\Acl::class
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AuthenticationController::class => Controller\Factory\AuthenticationControllerFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
