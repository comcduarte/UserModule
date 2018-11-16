<?php 
namespace User\Model;

use Midnet\Model\DatabaseObject;

class UserModel extends DatabaseObject
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    public $UUID;
    public $USERNAME;
    public $EMAIL;
    public $PASSWORD;
    public $STATUS;
    public $DATE_CREATED;
    public $DATE_MODIFIED;
    
    public function __construct($dbAdapter = null)
    {
        parent::__construct($dbAdapter);
        
        $this->primary_key = 'UUID';
        $this->table = 'users';
    }
}