<?php
namespace Popov\ZfcMail;

// @link http://adam.lundrigan.ca/2012/07/quick-and-dirty-zf2-zend-navigation/
// All navigation-related configuration is collected in the 'navigation' key
return [
    // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
    'default' => [
        // And finally, here is where we define our page hierarchy
        'mail' => [
            'module' => 'mail',
            'label' => 'Главная',
            'route' => 'default',
            'controller' => 'index',
            'action' => 'index',
            'pages' => [
                'settings-index' => [
                    'label' => 'Настройки',
                    'route' => 'default',
                    'controller' => 'settings',
                    'action' => 'index',
                    'pages' => [
                        'mail-index' => [
                            'label' => 'Письма',
                            'route' => 'default',
                            'controller' => 'mail',
                            'action' => 'index',
                            'pages' => [
                                'mail-add' => [
                                    'label' => 'Добавить',
                                    'route' => 'default',
                                    'controller' => 'mail',
                                    'action' => 'add',
                                ],
                                'mail-edit' => [
                                    'label' => 'Редактировать',
                                    'route' => 'default/id',
                                    'controller' => 'mail',
                                    'action' => 'edit',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
