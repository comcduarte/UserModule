<?php 
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Select;
use Zend\Form\Element\Csrf;
use User\Model\UserModel;
use Midnet\Model\Uuid;
use Zend\Db\Adapter\AdapterAwareTrait;
use RuntimeException;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select as SqlSelect;

class UserRolesForm extends Form
{
    use AdapterAwareTrait;
    
    public $user;
    
    public function __construct($name = NULL, $options = [])
    {
        parent::__construct($name);
    }
    
    public function init()
    {
        $uuid = new Uuid();
        
        
        $this->add([
            'name' => 'UUID',
            'type' => Hidden::class,
            'attributes' => [
                'value' => $uuid->value,
                'id' => 'UUID',
            ],
        ]);
        
        $this->add([
            'name' => 'USER',
            'type' => Hidden::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'USER',
                'required' => 'true',
                'placeholder' => '',
                'value' => $this->getUser()->UUID,
            ],
            'options' => [
                'label' => 'User',
            ],
        ]);
        
        $this->add([
            'name' => 'ROLE',
            'type' => Select::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ROLE',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Role',
                'value_options' => $this->getSelectValueOptions('roles','uuid','rolename'),
            ],
        ]);
        
        $this->add(new Csrf('SECURITY'));
        
        $this->add([
            'name' => 'SUBMIT',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-primary',
                'id' => 'SUBMIT',
            ],
        ]);
    }
    
    public function getUser()
    {
        if ($this->user) {
            return $this->user;
        }
        
        return FALSE;
    }
    
    public function setUser($params = [])
    {
        $user = new UserModel($this->adapter);
        $user->read($params);
        $this->user = $user;
        
        return $this->user;
    }
    
    public function getSelectValueOptions($table = null, $id_col = null, $val_col = null)
    {
        $options = [];
        
        $sql = new Sql($this->adapter);
        
        $select = new SqlSelect();
        $select->from($table);
        $select->columns([$id_col => $id_col, $val_col => $val_col]);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        
        try {
            $resultSet = $statement->execute();
        } catch (RuntimeException $e) {
            return $e;
        }
        
        foreach ($resultSet as $object) {
            $options[$object[$id_col]] = $object[$val_col];
        }
        
        return $options;
    }
}