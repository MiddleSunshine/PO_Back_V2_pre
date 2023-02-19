<?php

class LoginUser{
    private $token;
    public $userData;
    public $loginTime;
    public function __construct($token)
    {
        $this->token=$token;
        if(empty($this->token)){
            return false;
        }
        $this->getData($token);
    }

    public function storeData($storeData){
        $this->userData=$storeData;
        $this->loginTime=date("Y-m-d H:i:s");
        $data=[
            'User'=>$this->userData,
            'LoginTime'=>$this->loginTime
        ];
        file_put_contents(LOGIN_USERS.$this->token,json_encode($data));
    }

    public function getData(){
        $filePath=LOGIN_USERS.$this->token;
        if(!file_exists($filePath)){
            return false;
        }
        $data=file_get_contents($filePath);
        $data=json_decode($data,1);
        $this->loginTime=$data['LoginTime'];
        $this->userData=$data['User'];
        return $data;
    }
}