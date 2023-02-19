<?php

class BaseModel{
    public $pdo;
    public static $table;
    public function __construct()
    {
        $this->pdo = new MysqlPdo(); 
    }
    /**
     * select
     */
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
        $this->reset();
        if(empty($sql)){
            return [];
        }
        return $this->pdo->getFirstRow($sql);
    }

    public function getAllData(){
        $sql=$this->organizeSql();
        $this->reset();
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

    /**
     * update
     */
    public function updateOneData(string $field,string $newData,string $where){
        if(empty($field) || empty($where) || empty(static::$table)){
            return false;
        }
        $sql=sprintf("update %s set %s='%s' where %s;",static::$table,$field,addslashes($newData),$where);
        return $this->pdo->query($sql);
    }

    public function updateData($where,$data){
        $sql=[];
        foreach($data as $field=>$newValue){
            if(empty($field)){
                continue;
            }
            $sql[]=sprintf("%s='%s'",$field,addslashes($newValue));
        }
        if(empty($sql) || empty(static::$table) || empty($where)){
            return false;
        }
        $sql=sprintf("update %s set %s where %s;",static::$table,implode(',',$sql),$where);
        return $this->pdo->query($sql);
        
    }

    protected function reset(){
        $this->fieldData=[];
        $this->whereData=[];
    }
}