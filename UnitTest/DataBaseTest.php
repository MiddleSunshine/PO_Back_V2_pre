<?php

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";

use PHPUnit\Framework\TestCase;

class DataBaseTest extends TestCase{
    protected function getData(){
        return [];
    }

    protected function organizeData(){
        $data=$this->getData();
        $mysql=new MysqlPdo();
        $this->storeDataIntoDatabase($mysql,$data);
    }

    private function storeDataIntoDatabase($objMysql,&$data){
        foreach ($data as $table=>$rows){
            $sql=sprintf("truncate table %s;",$table);
            print PHP_EOL.$sql.PHP_EOL;
            $objMysql->query($sql);
            foreach ($rows as $row){
                $fiels=implode(',',array_keys($row));
                $value="";
                foreach ($row as $valueItem){
                    $value.=is_int($valueItem)?$valueItem:sprintf("'%s'",addslashes($valueItem));
                    $value.=",";
                }
                $value=substr($value,0,-1);
                $sql=sprintf("insert into %s (%s) value (%s);",$table,$fiels,$value);
                print PHP_EOL.$sql.PHP_EOL;
                $objMysql->query($sql);
            }
        }
    }
    /**
     * @var UsersModel
     */
    public $userModel;
    public function testCondition(){
        $this->assertTrue(true);
    }

    public function testSelect(){
        $userMode=new UsersModel();
        $userMode->select("ID");
        $userMode->where(['ID=1']);
        $userMode->getOneData();
        $this->userModel=$userMode;
        $this->assertTrue($userMode->ID==1);
    }

    public function testInsert(){
        $this->userModel=new UsersModel();
        $this->userModel->insertOneData([
            'ID'=>1,
            'Name'=>'admin',
            'Password'=>'admin'
        ]);
        $this->testSelect();
    }

    public function testUpdate(){
        $this->testSelect();
        $now=date("Y-m-d H:i:s");
        $this->userModel->updateOneData('LastUpdateTime',$now,'ID=1');
        $this->assertTrue($this->userModel->LastUpdateTime==$now);
        $this->userModel->updateData('ID=1',['LastUpdateTime'=>$now,'AddTime'=>$now]);
        $this->assertTrue($this->userModel->AddTime==$now);
    }

    public function testDelete(){
        $this->testSelect();
        $this->userModel->delete('ID=1');
        $this->userModel->where(['ID=1']);
        $data=$this->userModel->getOneData();
        $this->assertTrue(empty($data));
    }
}