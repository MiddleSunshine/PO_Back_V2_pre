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
        if (!$this->dataBaseModel->checkTableExists(static::$tableName)){
            $this->dataBaseModel->pdo->query($this->createTableSql());
        }
    }

    public function createTableSql():string
    {
        return sprintf("create table %s %s;",static::$tableName,static::getTableTemplate());
    }

    abstract protected static function getModel():BaseModel;
    abstract protected static function getTableName():string;
    abstract protected static function getTableTemplate():string;
}