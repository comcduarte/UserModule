<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Authentication\AuthenticationService;
use User\Auth\AuthAdapter;
use Zend\Authentication\Storage\Session;

class AuthenticationServiceFactory implements FactoryInterface
{
    public function __invoke (ContainerInterface $container, $requestedName, array $options = null)
    {
        $storage = new Session();
        $adapter = $container->get(AuthAdapter::class);

        return new AuthenticationService($storage, $adapter);
    }
}