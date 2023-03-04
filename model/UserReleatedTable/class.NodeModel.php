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
}