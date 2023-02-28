<?php

abstract class BaseUserModel{
    protected $userModel;
    protected static $tableName;
    protected $dataBaseModel;
    public function __construct(UsersModel $usersModel)
    {
        $this->userModel=$usersModel;
        if (empty($this->userModel->ID)){
            return false;
        }
        static::$tableName=static::getTableName()."_".$this->userModel->ID;
        $this->dataBaseModel=new BaseModel();
        $this->dataBaseModel->setTable(static::$tableName);
    }

    abstract protected static function getTableName():string;
}