<?php 
namespace User\Form;

use User\Model\UserModel;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        
        $this->add([
            'name' => 'USERNAME',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'USERNAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Username',
            ],
        ]);
        
        $this->add([
            'name' => 'FNAME',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'FNAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'First Name',
            ],
        ]);
        
        $this->add([
            'name' => 'LNAME',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'LNAME',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Last Name',
            ],
        ]);
        
        $this->add([
            'name' => 'ADDR1',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ADDR1',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Address',
            ],
        ]);
        
        $this->add([
            'name' => 'ADDR2',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ADDR2',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Address 2',
            ],
        ]);
        
        $this->add([
            'name' => 'CITY',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'CITY',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'City',
            ],
        ]);
        
        $this->add([
            'name' => 'STATE',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'STATE',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'State',
            ],
        ]);
        
        $this->add([
            'name' => 'ZIP',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'ZIP',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Zip Code',
            ],
        ]);
        
        $this->add([
            'name' => 'PHONE',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'PHONE',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Phone',
            ],
        ]);
        
        $this->add([
            'name' => 'EMAIL',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'EMAIL',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Email Address',
            ],
        ]);
        
        $this->add([
            'name' => 'PASSWORD',
            'type' => Password::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'PASSWORD',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);
        
        $this->add([
            'name' => 'CONFIRM_PASSWORD',
            'type' => Password::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'CONFIRM_PASSWORD',
                'required' => 'true',
                'placeholder' => '',
            ],
            'options' => [
                'label' => 'Confirm Password',
            ],
        ]);
        
        $this->add([
            'name' => 'STATUS',
            'type' => Select::class,
            'attributes' => [
                'id' => 'STATUS',
                'class' => 'form-control',
                'required' => 'true',
                'value' => UserModel::ACTIVE_STATUS,
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    UserModel::INACTIVE_STATUS => 'Inactive',
                    UserModel::ACTIVE_STATUS => 'Active',
                ],
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