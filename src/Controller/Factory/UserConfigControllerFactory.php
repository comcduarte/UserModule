<?php 
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\UserConfigController;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserConfigControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new UserConfigController();
        $controller->setDbAdapter($container->get('user-model-primary-adapter'));
        return $controller;
    }
}