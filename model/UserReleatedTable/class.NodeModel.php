<?php

class NodeModel extends BaseModel{
    public $ID;
    public $AddTime;
    public $LsatUpdateTime;
    public $Name;
    public $Type;
    public $LocalFilePath;
    public function updateNode($newNode){
        $historyNodeLastUpdateTimestamp=strtotime($this->LsatUpdateTime);
        $lastUpdateTimestamp=strtotime($newNode['LastUpdateTime']);
        // 如果数据库的数据更新，那么不更新
        if ($historyNodeLastUpdateTimestamp>=$lastUpdateTimestamp){
            return false;
        }
        return $this->updateData(sprintf("ID=%d",$newNode['ID']),$newNode);
    }

    public function newNode($nodeData)
    {
        empty($nodeData['AddTime']) && $nodeData['AddTime']=date("Y-m-d H:i:s");
        empty($nodeData['LastUpdateTime']) && $nodeData['LastUpdateTime']=date("Y-m-d H:i:s");
        $this->insertOneData($nodeData);
        return $this->getLastestData();
    }
}