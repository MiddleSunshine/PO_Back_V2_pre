<?php

class UserModel extends BaseModel{
    public static $table='Users';

    public $ID;
    public $Name;
    public $Password;
    public $Token;
    public $AddTime;
    public $LastUpdateTime;
}

