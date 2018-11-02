<?php 
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Form\UserForm;
use User\Model\UserModel;
use User\Traits\AdapterTrait;

class UserController extends AbstractActionController
{
    use AdapterTrait;
    
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
                $user->create();
                
                return $this->redirect()->toRoute('user');
            }
        }
        
        return [
            'form' => $form,
        ];
    }
    
    public function updateAction()
    {
        $params = $this->params()->fromRoute();
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
                return $this->redirect()->toRoute('user');
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
            return $this->redirect()->toRoute('user');
        }
        
        $user = new UserModel($this->adapter);
        $user->read(['UUID' => $uuid]);
        $user->delete();
        
        return $this->redirect()->toRoute('user');
    }
}