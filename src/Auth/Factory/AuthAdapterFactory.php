<?php
namespace User\Auth\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Auth\AuthAdapter;

class AuthAdapterFactory implements FactoryInterface
{
    public function __invoke (ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get('user-model-primary-adapter');
        
        $adapter = new AuthAdapter();
        $adapter->setDbAdapter($dbAdapter);
        
        return $adapter;
    }
}