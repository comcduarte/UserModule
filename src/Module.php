<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace User;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootStrap(MvcEvent $event) 
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'user-model-primary-adapter' => function ($container) {
                    return new Adapter($container->get('user-model-primary-adapter-config'));
                }
            ]
        ];
    }
}