<?php 
namespace User\Controller;

use User\Form\UserForm;
use User\Form\UserRolesForm;
use User\Model\UserModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use RuntimeException;
use User\Model\RoleModel;
use Midnet\Model\Uuid;
use Annotation\Model\AnnotationModel;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\Like;

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
                
                $uuid = new Uuid();
                $user->UUID = $uuid->value;
                
                $date = new \DateTime('now',new \DateTimeZone('EDT'));
                $today = $date->format('Y-m-d H:i:s');
                $user->DATE_CREATED = $today;
                
                $user->STATUS = $user::ACTIVE_STATUS;
                
                $bcrypt = new Bcrypt();
                $user->PASSWORD = $bcrypt->create($user->PASSWORD);
                $user->create();
                
                return $this->redirect()->toRoute('user/default', ['controller' => 'user', 'action' => 'update', 'uuid' => $user->UUID]);
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
                $this->flashmessenger()->addSuccessMessage('Update Successful');
                return $this->redirect()->toRoute('user/default');
            }
            
        }
        
        //-- BEGIN: Retrieve Annotations --//
        $annotation = new AnnotationModel($this->adapter);
        //$where = new Where(['TABLENAME' => 'dogs','PRIKEY' => $uuid]);
        $where = new Where([
            new Like('TABLENAME', 'users'),
            new Like('PRIKEY', $uuid),
        ]);
        $annotations = $annotation->fetchAll($where, ['DATE_CREATED DESC']);
        
        $notes = [];
        foreach ($annotations as $annotation) {
            $user = new UserModel($this->adapter);
            $user->read(['UUID' => $annotation['USER']]);
            
            $notes[] = [
                'USER' => $user->USERNAME,
                'ANNOTATION' => $annotation['ANNOTATION'],
                'DATE_CREATED' => $annotation['DATE_CREATED'],
            ];
        }
        //-- END: Retrieve Annotations --//
        
        return [
            'uuid' => $uuid,
            'form' => $form,
            'annotations' => $notes,
            'annotations_prikey' => $uuid,
            'annotations_tablename' => 'users',
            'annotations_user' => '',
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
    
    public function assignAction()
    {
        //-- Retrieve User Record from URL --//
        $uuid = $this->params()->fromRoute('uuid', 0);
        
        //-- Create User Model from Record --//
        $model = new UserModel($this->adapter);
        $model->read(['UUID' => $uuid]);
        
        //-- Create UserRolesForm --//
        $form = new UserRolesForm();
        $form->setDbAdapter($this->adapter);
        $form->setUser(['UUID' => $uuid]);
        $form->init();
        $form->get('SUBMIT')->setAttribute('value', 'Add');
        
        
        
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            //-- Capture POST --//
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $model->assignRole($form->getData('ROLE'));
                $this->redirect()->toRoute('user/default');
            }
        }
        
        //-- BEGIN: Retrieve currently assigned roles --//
        $sql = new Sql($this->adapter);
        
        $select = new Select();
        $select->from('user_roles');
        $select->where(['USER' => $uuid]);
        
        $statement = $sql->prepareStatementForSqlObject($select);
        
        try {
            $resultSet = $statement->execute();
        } catch (RuntimeException $e) {
            return $e;
        }
        
        $roles = [];
        foreach ($resultSet as $role) {
            $rolemodel = new RoleModel($this->adapter);
            $rolemodel->read(['UUID' => $role['ROLE']]);
            $roles[] = [
                'ROLENAME' => $rolemodel->ROLENAME,
                'ROLEUUID' => $rolemodel->UUID,
                'UUID' => $role['UUID'],
            ];
        }
        //-- END: Retrieve currently assigned roles --//
        
        
        return ([
            'form' => $form,
            'username' => $model->USERNAME,
            'user-uuid' => $model->UUID,
            'roles' => $roles,
        ]);
    }
    
    public function unassignAction()
    {
        $uuid = $this->params()->fromRoute('uuid', 0);
        if (!$uuid) {
            return $this->redirect()->toRoute('user/default');
        }
        
        $user = new UserModel($this->adapter);
        $user->unassignRole(['UUID' => $uuid]);
        
        return $this->redirect()->toRoute('user/default');
    }
}