<?php 
namespace User\Model;

use Midnet\Model\DatabaseObject;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Sql;
use RuntimeException;

class UserModel extends DatabaseObject
{
    public $UUID;
    public $USERNAME;
    public $FNAME;
    public $LNAME;
    public $ADDR1;
    public $ADDR2;
    public $CITY;
    public $STATE;
    public $ZIP;
    public $PHONE;
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
    
    public function assignRole($data)
    {
        $sql = new Sql($this->dbAdapter);
        $columns = [
            'UUID',
            'USER',
            'ROLE',
        ];
        $values = [
            $data['UUID'],
            $data['USER'],
            $data['ROLE'],
        ];
        
        $insert = new Insert();
        $insert->into('user_roles');
        $insert->columns($columns);
        $insert->values($values);
        
        $statement = $sql->prepareStatementForSqlObject($insert);
        
        try {
            $statement->execute();
        } catch (RuntimeException $e) {
            return $e;
        }
        return $this;
    }
    
    public function unassignRole($data)
    {
        $sql = new Sql($this->dbAdapter);
        
        $delete = new Delete();
        $delete->from('user_roles')->where($data);
        $statement = $sql->prepareStatementForSqlObject($delete);
        
        try {
            $statement->execute();
        } catch (RuntimeException $e) {
            return $e;
        }
        return true;
    }
}