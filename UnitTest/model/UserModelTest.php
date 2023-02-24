<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."DataBaseTest.php";

class UserModelTest extends DataBaseTest {
    public function testSelect(){
        $userModel=new UsersModel();
        $token=$userModel->checkUserExists('admin','admin');
        $this->assertIsString($token);
    }
}