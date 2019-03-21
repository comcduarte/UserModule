<?php 
namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\{Text,Hidden, Csrf, Submit};
use Midnet\Model\Uuid;

class RoleForm extends Form
{
    public function __construct($name = NULL)
    {
        $uuid = new Uuid();
        $date = new \DateTime('now',new \DateTimeZone('EDT'));
        $today = $date->format('Y-m-d H:i:s');
        parent::__construct($uuid->value);
        
        $this->add([
            'name' => 'UUID',
            'type' => Hidden::class,
            'attributes' => [
                'value' => $uuid->value,
                'id' => 'UUID',
            ],
        ]);
        
        $this->add([
            'name' => 'ROLENAME',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ROLENAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Role Name',
            ],
        ]);
        
        $this->add([
            'name' => 'STATUS',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'STATUS',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Status',
            ],
        ]);
        
        $this->add([
            'name' => 'DATE_CREATED',
            'type' => Hidden::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'DATE_CREATED',
                'required' => 'true',
                'placeholder' => '',
                'value' => $today,
            ],
            'options' => [
                'label' => 'Date Created',
            ],
        ]);
        
        $this->add([
            'name' => 'DATE_MODIFIED',
            'type' => Hidden::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'DATE_MODIFIED',
                'required' => 'true',
                'placeholder' => '',
                'value' => $today,
            ],
            'options' => [
                'label' => 'Date Modified',
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
}