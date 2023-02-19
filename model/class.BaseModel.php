<?php

class BaseModel{
    public $pdo;
    public static $table;
    public function __construct()
    {
        $this->pdo = new MysqlPdo(); 
    }

    protected $whereData=[];
    protected $fieldData=[];

    public function select($field){
        if(is_string($field)){
            $field=explode(',',$field);
        }
        $this->fieldData=array_merge($this->fieldData,$field);
    }

    public function where(array $where){
        $where=array_filter($where,function($item){
            return !empty($item); 
        });
        if(empty($where)){
            return false;
        }
        $this->whereData=array_merge($this->whereData,$where);
    }

    public function getOneData(){
        $sql=$this->organizeSql();
        if(empty($sql)){
            return [];
        }
        return $this->pdo->getFirstRow($sql);
    }

    public function getAllData(){
        $sql=$this->organizeSql();
        if(empty($sql)){
            return [];
        }
        return $this->pdo->getRows($sql);
    }

    protected function organizeSql(){
        if(empty(static::$table)){
            return '';
        }
        if(empty($this->fieldData)){
            $this->fieldData=["*"];
        }
        $this->fieldData=array_unique($this->fieldData);
        if(is_array($this->fieldData)){
            $this->fieldData=implode(',',$this->fieldData);
        }
        $sql=sprintf("select %s from %s",$this->fieldData,static::$table);
        if(!empty($this->whereData)){
            $sql.=" where ".implode(' ',array_unique($this->whereData));
        }
        return $sql;
    }
}