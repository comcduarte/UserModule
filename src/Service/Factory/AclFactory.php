<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Permissions\Acl\Acl as AccessControlList;

class AclFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $acl = new AccessControlList();
        
        /**
         * Resources
         */
        $user = $acl->addResource('User');
        $streetlamps = $acl->addResource('StreetLamps');
        
        /**
         * Guest Role
         */
        $guest = $acl->addRole('guest');
        
        /**
         * Authenticated Role
         */
        $employee = $acl->addRole('employee','guest');
        $acl->allow($employee, $streetlamps);
        
        /**
         * Administrative Role
         */
        $admin = $acl->addRole('admin');
        $acl->allow($admin);
        
        return $acl;
    }
}