<?php

class StorageModel extends BaseModel{
    public $ID;
    public $CachePath;
    public $Size;
    public $AddTime;

    public function insertOneData($data)
    {
        $data['AddTime']=date("Y-m-d H:i:s");
        return parent::insertOneData($data);
    }
}