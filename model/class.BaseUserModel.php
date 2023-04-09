<?php

abstract class BaseUserModel{
    protected $userModel;
    private $dataBaseModel;
    public function __construct(UsersModel $usersModel)
    {
        $this->userModel=$usersModel;
        if (empty($this->userModel->ID)){
            return false;
        }
        // 构建制定的表
        $this->dataBaseModel=new BaseModel();
        $this->dataBaseModel->setTable($this->getModalTableName());
        if (!$this->dataBaseModel->checkTableExists($this->getModalTableName())){
            $this->dataBaseModel->pdo->query($this->createTableSql());
            $this->afterCreateTable();
        }
    }

    public function afterCreateTable(){}

    public function getModalTableName(){
        return static::getTableName()."_".$this->userModel->ID;
    }

    public function createTableSql():string
    {
        return sprintf("create table %s %s;",$this->getModalTableName(),static::getTableTemplate());
    }

    abstract protected function getModel():BaseModel;
    abstract protected static function getTableName():string;
    abstract protected static function getTableTemplate():string;
}