<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."BaseController.php";

class LoginTest extends BaseController {

    public function testLogin(){
        $login=new Login([],['UserName'=>'admin','Password'=>'admin']);
        $loginResult=$login->Login();
        $this->assertTrue($loginResult['Status']==1);
    }

    /**
     * @after testLogin
     */
    public function testCheckLogin(){
        $login=new Login([],[],'cb4d65d9773ed8d9a77bcc57afb3c3b4');
        $loginResult=$login->CheckLogin();
        $this->assertTrue($loginResult['Status']==1);
    }
}