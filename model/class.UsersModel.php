<?php

class UsersModel extends BaseModel{
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
            "and",
            sprintf("`Password`='%s'",addslashes($Password))
        ]);
        /**
         * @var UsersModel $usrInstance
         */
        $userInstace=$this->getOneInstance();
        if(empty($userInstace)){
            return false;
        }
        $token=$this->getNewToken();
        return $token;
    }

    public function getNewToken(){
        if(defined('AUTH_TOKEN')){
            return md5($this->Name."|||".$this->Password."|||".date('Ym')."|||".AUTH_TOKEN);
        }
        throw new Exception("Please Update AuthToken");
    }
}

