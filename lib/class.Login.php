<?php

class Login extends Base{
    public function CheckLogin(){
        $token=$this->get['token'] ?? '';
        $returnData=[
            'Logined'=>false
        ];
        if(empty($token)){
            return self::returnActionResult($returnData);
        }
        
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
        $userModel=new UserModel();
        $newToken=$userModel->checkUserExists($userName,$password);
        if(empty($newToken)){
            return self::returnActionResult($this->post,false,'用户不存在或密码错误');
        }
        return self::returnActionResult([
            'Token'=>$newToken
        ]);
    }
}