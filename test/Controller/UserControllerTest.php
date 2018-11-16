<?php
namespace UserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class UserControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testSessionManager()
    {
        $sessionManager = new SessionManager();
        $sessionContainer = new Container('userModuleSessionContainer', $sessionManager);
               
        $sessionContainer->testSession = "Session test successful";
        
        $testSession = $sessionContainer->testSession;
    }
}