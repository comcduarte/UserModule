<?php 
namespace User\Controller\Factory;

use User\Controller\UserController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new UserController();
        $controller->setAdapter($container->get('model-primary-adapter'));
        return $controller;
    }
}