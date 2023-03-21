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
        if (!$this->setData()){
            return false;
        }
        return true;
    }

    public function storeData(array $storeData):void{
        $this->userData=$storeData;
        $this->loginTime=date("Y-m-d H:i:s");
        $data=[
            'User'=>$this->userData,
            'LoginTime'=>$this->loginTime
        ];
        file_put_contents(LOGIN_USERS.$this->token,json_encode($data));
    }

    public function setData():array|bool{
        $filePath=LOGIN_USERS.$this->token;
        if(!file_exists($filePath)){
            return false;
        }
        $data=file_get_contents($filePath);
        $data=json_decode($data,1);
        $this->loginTime=date('Y-m-d H:i:s');
        $this->userData=$data['User'];
        return $data;
    }

    /**
     * @param $token string
     * @return UsersModel
     */
    public static function getLoginUser(string $token): UsersModel
    {
        $self=new self($token);
        return new UsersModel($self->userData);
    }
}