<?php 
namespace User\Controller;

use User\Form\UserLoginForm;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    private $authService;
    
    public function loginAction()
    {
        $request = $this->getRequest();
        $form = new UserLoginForm();
        
        if ($request->isPost()) {
            /**
             * @ TODO: $form->bind(new UserModel()); Breaks when upgrading from zend-form 2.12.0 to 2.14.0
             */
//             $form->bind(new UserModel());
            $form->setData($request->getPost());
            if (!$form->isValid()) {
                $this->flashMessenger()->addErrorMessage('Form Invalid.');
                $this->redirect()->toRoute('user', ['controller' => 'auth','action' => 'login']);
            } else {
                $data = $form->getData();
                $adapter = $this->authService->getAdapter();
                $adapter->setUsername($data['USERNAME']);
                $adapter->setPassword($data['PASSWORD']);
                $result = $adapter->authenticate();
                if ($result->isValid()) {
                    $storage = $this->authService->getStorage();
                    $storage->write($data['USERNAME']);
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