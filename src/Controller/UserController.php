<?php 
namespace User\Controller;

use User\Form\UserForm;
use User\Model\UserModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    public function indexAction()
    {
        $user = new UserModel($this->adapter);
        $users = $user->fetchAll();
        
        return ([
            'users' => $users,    
        ]);
    }
    
    public function createAction()
    {
        $form = new UserForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new UserModel($this->adapter);
            
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $user->exchangeArray($form->getData());
                
                $bcrypt = new Bcrypt();
                $user->PASSWORD = $bcrypt->create($user->PASSWORD);
                $user->create();
                
                return $this->redirect()->toRoute('user/default');
            }
        }
        
        return [
            'form' => $form,
        ];
    }
    
    public function updateAction()
    {
        $uuid = $this->params()->fromRoute('uuid',0);
        if (!$uuid) {
            return $this->redirect()->toRoute('user');
        }
        
        $user = new UserModel($this->adapter);
        $user->read(['UUID'=>$uuid]);
        
        $form = new UserForm();
        $form->bind($user);
        $form->get('SUBMIT')->setAttribute('value', 'Update');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $user->update();
                return $this->redirect()->toRoute('user/default');
            }
            
        }
        
        return [
            'uuid' => $uuid,
            'form' => $form,
        ];
    }
    
    public function deleteAction()
    {
        $uuid = $this->params()->fromRoute('uuid', 0);
        if (!$uuid) {
            return $this->redirect()->toRoute('user/default');
        }
        
        $user = new UserModel($this->adapter);
        $user->read(['UUID' => $uuid]);
        $user->delete();
        
        return $this->redirect()->toRoute('user');
    }
}