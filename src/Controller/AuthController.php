<?php 
namespace User\Controller;

use Midnet\Traits\AdapterTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\UserModel;
use User\Form\UserForm;

class AuthController extends AbstractActionController
{
    use AdapterTrait;
    
    private $authService;
    
    public function loginAction()
    {
        $request = $this->getRequest();
        $form = new UserForm();
        
        if ($request->isPost()) {
            $form->bind(new UserModel());
            $form->setData($request->getPost());
            if (!$form->isValid()) {
                $message = self::FORM_INVALID;
            } else {
                $user = $form->getData();
                $adapter = $this->authService->getAdapter();
                $adapter->setUsername($user->USERNAME);
                $adapter->setPassword($user->PASSWORD);
                $result = $adapter->authenticate();
                if ($result->isValid()) {
                    $storage = $this->authService->getStorage();
                    $storage->write($user->USERNAME);
                    $this->flashMessenger()->addMessage('You have successfully logged in');
                    $this->redirect()->toRoute('home');
                } else {
                    $this->flashMessenger()->addMessage('Login Failed. Invalid username or password.');
                    $this->redirect()->toRoute('user', ['controller' => 'auth','action' => 'login']);
                }
            }
            
        }
        return new ViewModel([
            'form' => $form,
        ]);
    }
    
    public function logoutAction()
    {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('home');
    }
    
    public function getAuthService()
    {
        return $this->authService;
    }

    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

}