<?php
namespace Popov\ZfcMail;

return [
    'mail' => require_once 'mail.config.php',
    'navigation' => require_once 'navigation.config.php',

    'console' => [
        'router' => [
            'routes' => [
                'mail-routes' => [
                    'type' => 'simple',
                    'options' => [
                        //'route' => 'mail <action> [--from=|-f=] [--to=|-t=] [--message=|-m=]',
                        'route' => 'mail <action> [--from=|-f] [--to=|-t] [--message=|-m]',
                        'defaults' => [
                            'controller' => 'mail',
                            'action' => 'send'
                        ]
                    ]
                ]
            ]
        ]
    ],

    'controllers' => [
        'invokables' => [
            'mail' => Controller\MailController::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'mail' => View\Helper\Factory\MailHelperFactory::class,
        ],
    ],

    'service_manager' => [
        'aliases' => [
            'MailService' => Service\MailService::class,
            'MailRenderer' => Service\MailRenderer::class,
            'MailOptionService' => Service\MailOptionService::class, // @deprecated
            'MailOptionRoleService' => Service\MailOptionRoleService::class, // @deprecated
        ],
        //'invokables' => [],
        'factories' => [
            'MailTransport' => Service\Factory\MailTransportFactory::class,
            Service\MailService::class => Service\Factory\MailServiceFactory::class,
            Service\MailRenderer::class => Service\Factory\MailRendererFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    // Doctrine config
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver',
                ],
            ],
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'cache' => 'array',
                'extension' => '.dcm.yml',
                'paths' => [__DIR__ . '/yaml'],
            ],
        ],
    ],
];
