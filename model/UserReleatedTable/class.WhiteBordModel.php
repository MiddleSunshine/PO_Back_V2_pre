<?php

class WhiteBordModel extends BaseModel{
    const TYPE_DRAFT='Draft';
    const TYPE_DATA='Data';

    public $ID;

    public $AddTime;

    public $LastUpdateTimestamp;

    public $Title;

    public $LocalFilePath;

    public function insertOneData($data)
    {
        empty($data['Type']) && $data['Type']=self::TYPE_DRAFT;
        empty($data['AddTime']) && $data['AddTime']=date("Y-m-d H:i:s");
        empty($data['LastUpdateTime']) && $data['LastUpdateTime']=date("Y-m-d H:i:s");
        return parent::insertOneData($data);
    }

    public function updateData($where, $data)
    {
        $data['LastUpdateTime']=date("Y-m-d H:i:s");
        return parent::updateData($where, $data);
    }
}