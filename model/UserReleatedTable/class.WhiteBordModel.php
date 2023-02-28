<?php

class WhiteBordModel extends BaseModel{
    public $ID;

    public $AddTime;

    public $LastUpdateTimestamp;

    public $Title;

    public $LocalFilePath;

    public function insertOneData($data)
    {
        empty($data['AddTime']) && $data['AddTime']=date("Y-m-d H:i:s");
        empty($data['LastUpdateTimestamp']) && $data['LastUpdateTimestamp']=time();
        return parent::insertOneData($data);
    }

    public function updateData($where, $data)
    {
        $data['LastUpdateTimestamp']=time();
        return parent::updateData($where, $data);
    }
}