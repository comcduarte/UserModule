<?php 
namespace User\Model;

use Midnet\Model\DatabaseObject;

class RoleModel extends DatabaseObject
{
    public $UUID;
    public $ROLENAME;
    public $STATUS;
    public $DATE_CREATED;
    public $DATE_MODIFIED;
    
    public function __construct($dbAdapter = null)
    {
        parent::__construct($dbAdapter);
        
        $this->primary_key = 'UUID';
        $this->table = 'roles';
    }
}