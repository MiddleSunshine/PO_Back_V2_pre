<?php

class UserModel extends BaseModel{
    public static $table='Users';

    public $ID;
    public $Name;
    public $Password;
    public $Token;
    public $AddTime;
    public $LastUpdateTime;


    public function checkUserExists($UserName,$Password){
        $this->where([
            sprintf("Name='%s'",addslashes($UserName)),
            sprintf("Password='%s'",addslashes($Password))
        ]);
        $userInstace=$this->getOneInstance();
        if(empty($userInstace)){
            return false;
        }
        return $this->getNewToken();
    }

    public function getNewToken(){
        if(defined('AUTH_TOKEN')){
            return md5($this->Password.date('Ym').AUTH_TOKEN);
        }
        throw new Exception("Please Update AuthToken");
    }
}

