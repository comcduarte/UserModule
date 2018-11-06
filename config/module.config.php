<?php 
namespace User;

use User\Controller\UserController;
use User\Controller\Factory\UserControllerFactory;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:controller[/:action[/:uuid]]]',
                    'defaults' => [
                        'controller' => 'user',
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'controller' => '[a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            UserController::class => UserControllerFactory::class,
        ],
        'aliases' => [
            'user' => Controller\UserController::class,
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'User',
                'route' => 'user',
                'pages' => [
                    [
                        'label' => 'Create New User',
                        'route' => 'user',
                        'controller' => 'user',
                        'action' => 'create',
                    ],
                    [
                        'label' => 'List Users',
                        'route' => 'user',
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'user-model-primary-adapter-config' => 'model-primary-adapter-config',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];