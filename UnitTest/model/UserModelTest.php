<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."DataBaseTest.php";
require_once dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.UsersModel.php";

class UserModelTest extends DataBaseTest {
    public function testSelect(){
        $userModel=new UserModel();
        $token=$userModel->checkUserExists('admin','admin');
        $this->assertIsString($token);
    }
}