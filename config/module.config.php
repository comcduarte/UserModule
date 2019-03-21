<?php
namespace User;

use User\Controller\UserController;
use User\Controller\Factory\UserControllerFactory;
use Zend\Router\Http\Segment;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;
use User\Controller\AuthController;
use User\Controller\Factory\AuthControllerFactory;
use User\Auth\AuthAdapter;
use User\Auth\Factory\AuthAdapterFactory;
use User\Service\Factory\AuthenticationServiceFactory;
use Zend\Authentication\AuthenticationService;
use Zend\Router\Http\Literal;
use User\Controller\RoleController;
use User\Controller\Factory\RoleControllerFactory;
use User\Form\UserRolesForm;
use User\Form\Factory\UserRolesFormFactory;

return [
    'router' => [
        'routes' => [
            'role' => [
                'type' => Literal::class,
                'priority' => 1,
                'options' => [
                    'route' => '/role',
                    'defaults' => [
                        'controller' => 'role',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/[:action[/:uuid]]',
                            'defaults' => [
                                'controller' => RoleController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            'user' => [
                'type'    => Literal::class,
                'priority' => 1,
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        'controller' => 'user',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'login' => [
                        'type' => Literal::class,
                        'priority' => 10,
                        'options' => [
                            'route' => '/login',
                            'defaults' => [
                                'controller' => 'auth',
                                'action' => 'login',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type' => Literal::class,
                        'priority' => 10,
                        'options' => [
                            'route' => '/logout',
                            'defaults' => [
                                'controller' => 'auth',
                                'action' => 'logout',
                            ],
                        ],
                    ],
                    'default' => [
                        'type' => Segment::class,
                        'priority' => 0,
                        'options' => [
                            'route' => '/[:controller[/:action[/:uuid]]]',
                            'defaults' => [
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'controller'    =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'        =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'acl' => [
        'guest' => [
            'user/login' => ['login'],
            'user/logout' => ['logout'],
            // @ TODO: Logout is only allowed for guests to clear identities in case of emergency.  Remove once checks and balances operate.
        ],
        'member' => [
            'user/logout' => ['logout'],
            'user/default' => ['index', 'create', 'update', 'delete', 'assign', 'unassign'],
            'user' => ['index'],
            'role/default' => ['index', 'create', 'update', 'delete'],
        ],
    ],
    'controllers' => [
        'factories' => [
            UserController::class => UserControllerFactory::class,
            AuthController::class => AuthControllerFactory::class,
            RoleController::class => RoleControllerFactory::class,
        ],
        'aliases' => [
            'user' => Controller\UserController::class,
            'auth' => Controller\AuthController::class,
            'role' => Controller\RoleController::class,
        ],
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'User',
                'route' => 'user',
                'class' => 'dropdown',
                'pages' => [
                    [
                        'label' => 'User Maintenance',
                        'route' => 'user',
                        'class' => 'dropdown-submenu',
                        'pages' => [
                            [
                                'label' => 'Create New User',
                                'route' => 'user/default',
                                'controller' => 'user',
                                'action' => 'create',
                            ],
                            [
                                'label' => 'List Users',
                                'route' => 'user/default',
                                'controller' => 'user',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    [
                        'label' => 'Role Maintenace',
                        'route' => 'role',
                        'class' => 'dropdown-submenu',
                        'pages' => [
                            [
                                'label' => 'Create New Role',
                                'route' => 'user/default',
                                'controller' => 'role',
                                'action' => 'create',
                            ],
                            [
                                'label' => 'List Roles',
                                'route' => 'user/default',
                                'controller' => 'role',
                                'action' => 'index',
                            ],
                        ],
                    ],
                    [                    
                        'label' => 'Login',
                        'route' => 'user/login',
                        'controller' => 'auth',
                        'action' => 'login',
                    ],
                    [
                        'label' => 'Logout',
                        'route' => 'user/logout',
                        'controller' => 'auth',
                        'action' => 'logout',
                    ],
                ],
            ],
        ],
        'user' => [
            [
                'label' => 'Welcome',
                'route' => 'user',
                'pages' => [
                    [
                        'label' => 'Logout',
                        'route' => 'user/logout',
                        'controller' => 'auth',
                        'action' => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'user-model-primary-adapter-config' => 'model-primary-adapter-config',
            AuthenticationService::class => 'auth-service',
        ],
        'factories' => [
            AuthAdapter::class => AuthAdapterFactory::class,
            'auth-service' => AuthenticationServiceFactory::class,
        ],
    ],
    'session_config' => [
        'cookie_lifetime' => 3600,
        'gc_maxlifetime'     => 2592000,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];