<?php
require_once __DIR__.DIRECTORY_SEPARATOR."UserReleatedTable".DIRECTORY_SEPARATOR."class.StorageModel.php";

class Storage extends BaseUserModel{

    public function addRecord(string $cacheFilePath,string $dataType,string $size):bool
    {
        if (empty($cacheFilePath)){
            return false;
        }
        $data=[
            'CachePath'=>$cacheFilePath,
            'DataType'=>$dataType,
            'Size'=>$size
        ];
        $model=$this->getModel();
        $model->insertOneData($data);
        return true;
    }
    protected function getModel(): BaseModel
    {
        $model=new StorageModel();
        $model->setTable($this->getModalTableName());
        return $model;
    }

    protected static function getTableTemplate(): string
    {
        return <<<EOD
(
    ID        int auto_increment
        primary key,
    CachePath varchar(500) null,
    Size      int          null,
    AddTime   datetime     null,
    DataType  varchar(10)  null
);
EOD;

    }

    protected static function getTableName(): string
    {
        return 'Storage';
    }
}