<?php

namespace Core;

return [
    'core_table_gateway' => [

        \Core\Service\AdminUserService::class => [
            'entity' => \Core\Entity\AdminUser::class,
            'service' => \Core\Service\AdminUserService::class,
            'table' => 'admin_user'
        ]

    ],

    'service_manager' => [
        'abstract_factories' => [
            \Core\Service\Factory\AbstractTableGatewayFactory::class
        ],
    ],

    // Loggers for the project
    'log' => [

        \Core\Log\Logging::LOGGER_ERROR_HANDLER => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => 'logs/php.log',
                ]
            ]
        ],
    ],

        \Core\Log\Logging::LOGGER_EXCEPTION_HANDLER => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => 'logs/exception.log',
                ]
            ]
        ],
    ],

    \Core\Log\Logging::LOGGER_APPLICATION => [
        'writers' => [
            [
                'name' => 'stream',
                'options' => [
                    'stream' => 'logs/application.log',
                ]
            ]
        ],
    ],

    \Core\Log\Logging::LOGGER_CONSOLE => [
        'writers' => [
            'standard-file' => [
                'name' => 'stream',
                'options' => [
                    'stream' => 'logs/console.log',
                    'filters' => [
                        new \Zend\Log\Filter\Priority([
                            'operator' => '<=',
                            'priority' => \Zend\Log\Logger::ERR
                        ])
                    ]
                ],
            ],
        ],
    ],
]
];
