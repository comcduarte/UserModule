<?php 
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Controller\RoleController;

class RoleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = new RoleController();
        $controller->setDbAdapter($container->get('user-model-primary-adapter'));
        return $controller;
    }
}