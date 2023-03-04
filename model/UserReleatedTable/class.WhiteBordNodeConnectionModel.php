<?php

class WhiteBordNodeConnectionModel extends BaseModel{

    public function getAllConnection($whiteBordId):array
    {
        $sql=sprintf("select N_ID from %s where W_ID=%d;",static::$table,$whiteBordId);
        return $this->pdo->getRows($sql,'N_ID');
    }
    public function updateWhiteNodeConnection($whiteBordId,$nodeIds){
        $sql=sprintf("select N_ID from %s where W_ID=%d;",static::$table,$whiteBordId);
        $historyConnections=$this->pdo->getRows($sql,'N_ID');
        $insertConnection=$updateConnection=$deleteConnection=[];
        foreach ($nodeIds as $nodeId=>$label){
            // 与历史数据一致
            if (isset($historyConnections[$nodeId]) && $historyConnections[$nodeId]==$label){
                unset($historyConnections[$nodeId]);
                continue;
            }
            // 需要更新 Label
            if (isset($historyConnections[$nodeId])){
                unset($historyConnections[$nodeId]);
                $updateConnection[$nodeId]=$label;
                continue;
            }
            // 新的数据
            $insertConnection[$nodeId]=$label;
        }
        // 需要删除的数据
        $deleteConnection=array_keys($historyConnections);
        if (!empty($updateConnection)){
            foreach ($updateConnection as $nodeId=>$label){
                $this->updateData(sprintf('W_ID=%d and N_ID=%d',$whiteBordId,$nodeId),['Label'=>$label]);
            }
        }
        if (!empty($insertConnection)){
            foreach ($insertConnection as $nodeId=>$label){
                $this->insertOneData(['W_ID'=>$whiteBordId,'N_ID'=>$nodeId,'Label'=>$label]);
            }
        }
        if (!empty($deleteConnection)){
            foreach ($deleteConnection as $nodeId){
                $this->delete(sprintf('W_ID=%d and N_ID=%d',$whiteBordId,$nodeId));
            }
        }
    }
}