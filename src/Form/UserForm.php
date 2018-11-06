<?php 
namespace User\Form;

use Midnet\Model\Uuid;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;

class UserForm extends Form
{
    public function __construct($name = null)
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
            'name' => 'EMAIL',
            'type' => Text::class,
            'attributes' => [
                'class' => 'form-control',
                'id' => 'EMAIL',
                'required' => 'true',
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
            'type' => Text::class,
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
            'type' => Text::class,
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