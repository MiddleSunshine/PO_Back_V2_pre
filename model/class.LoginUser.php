<?php

class LoginUser{
    private $token;
    private $storeData;
    private $loginTime;
    public function __construct($token)
    {
        $this->token=$token;
    }

    public function storeData($storeData){
        $data=[
            'User'=>$storeData,
            'LoginTime'=>date("Y-m-d H:i:s")
        ];
        file_put_contents(LOGIN_USERS.$this->token,json_encode($data));
    }

    public function getData(){
        $data=file_get_contents(LOGIN_USERS.$this->token);
        $data=json_decode($data,1);
        $this->loginTime=$data['LoginTime'];
        $this->storeData=$data['User'];
        return $data;
    }
}