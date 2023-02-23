<?php
require_once dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
require_once dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.UsersModel.php";

use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase {
    public function testSelect(){
        $userModel=new UserModel();
        $token=$userModel->checkUserExists('admin','admin');
        $this->assertTrue(is_array($token));
    }
}