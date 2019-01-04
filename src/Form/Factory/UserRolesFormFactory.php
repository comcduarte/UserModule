<?php 
namespace User\Form\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Form\UserRolesForm;

class UserRolesFormFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new UserRolesForm();
        $form->setDbAdapter($container->get('user-model-primary-adapter'));
        return $form;
    }
}