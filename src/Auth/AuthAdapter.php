<?php 
namespace User\Auth;

use User\Model\UserModel;
use Zend\Authentication\Result;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterAwareTrait;

class AuthAdapter implements AdapterInterface
{
    use AdapterAwareTrait;
    
    private $username;
    private $password;
    
    public function authenticate()
    {
        $user = new UserModel($this->adapter);
        $user->read(['USERNAME' => $this->username]);
        
        /**
         * Return error if user is not found
         */
        if ($user == null) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid Credentials']);
        }
        
        /**
         * Return error if user has be deactivated
         */
        if ($user->STATUS == UserModel::STATUS_INACTIVE) {
            return new Result(Result::FAILURE, null, ['User is inactive']);
        }
        
        /**
         * Check password for active users
         */
        $bcrypt = new Bcrypt();
        $passwordHash = $user->PASSWORD;
        
        if ($bcrypt->verify($this->password, $passwordHash)) {
            return new Result(Result::SUCCESS, $user->USERNAME, ['Authenticated Successfully']);
        }
        
        /**
         * Return error if password did not verify
         */
        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid Credentials']);
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}