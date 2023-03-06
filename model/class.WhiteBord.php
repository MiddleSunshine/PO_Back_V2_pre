<?php
require_once __DIR__.DIRECTORY_SEPARATOR."UserReleatedTable".DIRECTORY_SEPARATOR."class.WhiteBordModel.php";

class WhiteBord extends BaseUserModel{

    public function addWhiteBord($title,$type=WhiteBordModel::TYPE_DRAFT):WhiteBordModel{
        $whiteBordModel=self::getModel();
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

    public function updateWhiteBord($ID,$data):WhiteBordModel
    {
        $whiteBordModel=self::getModel();
        $whiteBordModel->updateData(sprintf('ID=%d',$ID),$data);
        return $whiteBordModel;
    }

    public function deleteWhiteBord(){

    }

    public function seleteWhiteBord(){

    }

    public static function getModel():WhiteBordModel
    {
        $whiteBordModel=new WhiteBordModel();
        $whiteBordModel->setTable(static::$tableName);
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