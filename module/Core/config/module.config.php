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
    ]
];
