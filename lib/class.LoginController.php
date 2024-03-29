<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.LoginUser.php";
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.UsersModel.php";

class LoginController extends Base{
    public function CheckLogin(){
        $token=$this->get['token'] ?? '';
        $returnData=[
            'Logined'=>false
        ];
        if(empty($token)){
            return self::returnActionResult($returnData);
        }
        $returnData['Logined']=static::checkUserLogined($token);
        return self::returnActionResult($returnData);
    }

    public static function checkUserLogined($token){
        if(empty($token)){
            return false;
        }
        $loginUser=new LoginUser($token);
        return !empty($loginUser->userData);
    }

    public function Login(){
        $userName=$this->post['UserName'] ?? '';
        $password=$this->post['Password'] ?? '';
        if(empty($userName)){
            return self::returnActionResult($this->post,false,"请输入用户名");
        }
        if(empty($password)){
            return self::returnActionResult($this->post,false,'请输入密码');
        }
        $userModel=new UsersModel();
        $newToken=$userModel->checkUserExists($userName,$password);
        if(empty($newToken)){
            return self::returnActionResult($this->post,false,'用户不存在或密码错误');
        }
        $loginUser=new LoginUser($newToken);
        $loginUser->storeData($userModel->toArray());
        return self::returnActionResult([
            'Token'=>$newToken
        ]);
    }
}