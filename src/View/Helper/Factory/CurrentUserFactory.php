<?php
namespace User\View\Helper\Factory;
use Interop\Container\ContainerInterface;
use User\View\Helper\CurrentUser;

class CurrentUserFactory
{
    public function __invoke(ContainerInterface $container)
    {        
        $authService = $container->get('auth-service');
        $adapter = $container->get('user-model-primary-adapter');
        
        $plugin = new CurrentUser($authService);
        $plugin->setDbAdapter($adapter);
                        
        return $plugin;
    }
}