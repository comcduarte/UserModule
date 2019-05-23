<?php 
namespace User\Controller;

use Midnet\Model\Uuid;
use User\Model\UserModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use RuntimeException;

class UserConfigController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    public function indexAction()
    {
        
    }
    
    public function createAction()
    {
        $this->createDatabase();
        return $this->redirect()->toRoute('user/config', ['action' => 'index']);
    }
    
    public function clearAction()
    {
        $this->clearDatabase();
        return $this->redirect()->toRoute('user/config', ['action' => 'index']);
    }
    
    private function createDatabase()
    {
        $sql = [];
        /****************************************
         * User Table
         ****************************************/
        $sql[0] = "
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UUID` varchar(36) NOT NULL,
  `USERNAME` varchar(32) NOT NULL,
  `FNAME` varchar(100) DEFAULT NULL,
  `LNAME` varchar(100) DEFAULT NULL,
  `ADDR1` varchar(100) DEFAULT NULL,
  `ADDR2` varchar(100) DEFAULT NULL,
  `CITY` varchar(100) DEFAULT NULL,
  `STATE` varchar(2) DEFAULT NULL,
  `ZIP` varchar(9) DEFAULT NULL,
  `PHONE` varchar(10) DEFAULT NULL,
  `EMAIL` varchar(64) DEFAULT NULL,
  `PASSWORD` varchar(64) NOT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `DATE_CREATED` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DATE_MODIFIED` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Version00001';
        ";
        
        $sql[1] = "ALTER TABLE `users` ADD PRIMARY KEY (`UUID`);";
        
        /****************************************
         * Role Table
         ****************************************/
        $sql[2] = "
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `UUID` varchar(36) NOT NULL,
  `ROLENAME` varchar(255) DEFAULT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `DATE_CREATED` timestamp NULL DEFAULT NULL,
  `DATE_MODIFIED` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Version00001';
        "; 
        $sql[3] = "ALTER TABLE `roles` ADD PRIMARY KEY (`UUID`);";
        
        /****************************************
         * User-Role Table
         ****************************************/
        $sql[5] = "
DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `UUID` varchar(36) NOT NULL,
  `USER` varchar(36) NOT NULL,
  `ROLE` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Version00001';
        ";
        $sql[6] = "ALTER TABLE `user_roles` ADD PRIMARY KEY (`UUID`);";
        
        
        foreach ($sql as $key => $string) {
            $statement = $this->adapter->createStatement($string);
            
            try {
                $statement->execute();
            } catch (RuntimeException $e) {
                $this->flashMessenger()->addErrorMessage("Database tables failed [$key].");
                return $e;
            }
        }
        
        
        $this->flashMessenger()->addSuccessMessage('Database tables created.');
        
        $user = new UserModel($this->adapter);
        $bcrypt = new Bcrypt();
        $uuid = new Uuid();
        
        $user = new UserModel($this->adapter);
        $user->FNAME = 'Administrator';
        $user->USERNAME = 'Admin';
        $user->PASSWORD = $bcrypt->create('admin');
        $user->UUID = $uuid->generate()->value;
        $user->STATUS = $user::ACTIVE_STATUS;
        $user->create();
        
        $user = new UserModel($this->adapter);
        $user->UUID = 'SYSTEM';
        $user->FNAME = 'SYSTEM';
        $user->USERNAME = 'SYSTEM';
        $user->PASSWORD = $bcrypt->create('admin');
        $user->STATUS = $user::ACTIVE_STATUS;
        $user->create();
        
        $this->flashMessenger()->addSuccessMessage('Admin users created.');
    }
    
    private function clearDatabase()
    {
        $this->flashMessenger()->addSuccessMessage('Database tables cleared.');
    }
}