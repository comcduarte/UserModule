<?php 
namespace User\Controller;

use User\Form\RoleForm;
use User\Model\RoleModel;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class RoleController extends AbstractActionController
{
    use AdapterAwareTrait;
    
    public function indexAction()
    {
        $role = new RoleModel($this->adapter);
        $roles = $role->fetchAll();
        
        return ([
            'roles' => $roles,
        ]);
    }
    
    public function createAction()
    {
        $form = new RoleForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $model = new RoleModel($this->adapter);
            
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $model->exchangeArray($form->getData());
                $model->create();
                
                return $this->redirect()->toRoute('role/default');
            }
        }
        
        return ([
            'form' => $form,
        ]);
    }
    
    public function updateAction()
    {
        $uuid = $this->params()->fromRoute('uuid',0);
        if (!$uuid) {
            return $this->redirect()->toRoute('role');
        }
        
        $model = new RoleModel($this->adapter);
        $model->read(['UUID' => $uuid]);
        
        $form = new RoleForm();
        $form->bind($model);
        $form->get('SUBMIT')->setAttribute('value', 'Update');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $model->update();
                
                $this->flashmessenger()->addSuccessMessage('Update Successful');
                return $this->redirect()->toRoute('role/default');
            }
        }
        
        return ([
            'uuid' => $uuid,
            'form' => $form,
        ]);
    }
    
    public function deleteAction()
    {
        $uuid = $this->params()->fromRoute('uuid', 0);
        if (!$uuid) {
            return $this->redirect()->toRoute('role');
        }
        
        $model = new RoleModel($this->adapter);
        $model->read(['UUID' => $uuid]);
        $model->delete();
        
        return $this->redirect()->toRoute('role');
    }
}