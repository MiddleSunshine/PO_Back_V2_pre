<?php
require_once __DIR__.DIRECTORY_SEPARATOR."UserReleatedTable".DIRECTORY_SEPARATOR."class.WhiteBordModel.php";

class WhiteBord extends BaseUserModel{

    public function afterCreateTable()
    {
        $this->addWhiteBord("Index");
    }

    public function addWhiteBord($title,$type=WhiteBordModel::TYPE_DRAFT):WhiteBordModel{
        $whiteBordModel=$this->getModel();
        $whiteBordModel->insertOneData(
            [
                'Title'=>$title,
                'Type'=>$type
            ]
        );
        $whiteBordModel->select('ID');
        $whiteBordModel->orderBy("ID desc");
        $whiteBordModel->getOneData();
        return $whiteBordModel;
    }

    public function updateWhiteBord($ID,$data,$storeDataWhenExists=false):WhiteBordModel
    {
        $whiteBordModel=$this->getModel();
        $whiteBordModel->where([sprintf("ID=%d",$ID)]);
        $whiteBordModel->getOneData();
        if ($whiteBordModel->ID){
            $whiteBordModel->updateData(sprintf('ID=%d',$ID),$data);
        }else if ($storeDataWhenExists){
            $whiteBordModel->insertOneData($data);
        }
        return $whiteBordModel;
    }

    public function deleteWhiteBord(){

    }

    public function seleteWhiteBord(string $field,array $where){
        $model=$this->getModel();
        $model->select($field);
        $model->where($where);
        $model->getOneData();
        return $model;
    }

    public function getModel():WhiteBordModel
    {
        $whiteBordModel=new WhiteBordModel();
        $whiteBordModel->setTable($this->getModalTableName());
        return $whiteBordModel;
    }

    protected static function getTableName(): string
    {
        return "WhiteBord";
    }

    protected static function getTableTemplate(): string
    {
        return "(
                ID                  int auto_increment
                    primary key,
                AddTime             datetime     null,
                LastUpdateTime      datetime    null,
                Title               varchar(300)          null,
                LocalFilePath       varchar(300) null,
                Type                varchar(10) null
            );";
    }
}