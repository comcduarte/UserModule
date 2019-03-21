<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace User;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\MvcEvent;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootStrap(MvcEvent $event) 
    {
//         $application = $event->getApplication();
//         $serviceManager = $application->getServiceManager();
//         $sessionManager = $serviceManager->get(SessionManager::class);
        
        /**
         * Set event to retrieve user's identity for every request
         */
//         $eventManager = $application->getEventManager();
//         $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'protectPage'], -100);
    }
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'user-model-primary-adapter' => function ($container) {
                    return new Adapter($container->get('user-model-primary-adapter-config'));
                },
           
            ]
        ];
    }
    
    public function protectPage(MvcEvent $event)
    {
        $match = $event->getRouteMatch();
        if (! $match) {
            return;
        }
        
        $sm = $event->getApplication()->getServiceManager();
//         $flashMessenger = $sm->get('ControllerPluginManager')->get('flashmessenger');
        $authService = $sm->get('auth-service');
        
        if (! $authService->hasIdentity()) {
            //-- Redirect to Login Page --//
            $event->getRouteMatch()->setParam('controller', 'auth')->setParam('action', 'login');
        }
    }
}