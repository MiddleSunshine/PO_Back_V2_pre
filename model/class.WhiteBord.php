<?php
require_once __DIR__.DIRECTORY_SEPARATOR."UserReleatedTable".DIRECTORY_SEPARATOR."class.WhiteBordModel.php";

class WhiteBord extends BaseUserModel{

    public function addWhiteBord($title):WhiteBordModel{
        $whiteBordModel=new WhiteBordModel();
        $whiteBordModel->setTable(static::$tableName);
        $whiteBordModel->insertOneData(['Title'=>$title]);
        $whiteBordModel->select('ID');
        $whiteBordModel->orderBy("ID desc");
        $whiteBordModel->getOneData();
        return $whiteBordModel;
    }

    public function updateWhiteBord(){

    }

    public function deleteWhiteBord(){

    }

    public function seleteWhiteBord(){

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
                LastUpdateTimestamp timestamp    null,
                Title               varchar(300)          null,
                LocalFilePath       varchar(300) null,
                Type                varchar(10) null
            );";
    }
}