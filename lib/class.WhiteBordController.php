<?php
require_once INDEX_FILE.DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."Queues".DIRECTORY_SEPARATOR."class.WhiteBordQueue.php";
require_once INDEX_FILE.DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.Node.php";

class WhiteBordController extends Base{
    public function GetWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordInstance=new WhiteBord($loginUser);
        $whiteBordModel=$whiteBordInstance->seleteWhiteBord('*',[sprintf('ID=%d',$id)]);
        $whiteBordDir=$whiteBordModel->LocalFilePath;
        $returnData=[
            'WhiteBordContent'=>[
                'data'=>[
                    'nodes'=>[],
                    'edges'=>[]
                ],
                'settings'=>[]
            ]
        ];
        if (file_exists($whiteBordDir)){
            $content=file_get_contents($whiteBordDir);
            $returnData['WhiteBordContent']=json_decode($content,1);
        }
        if (!empty($returnData['WhiteBordContent']['data']['nodes'])){
            $nodeInstance=new Node($loginUser);
            $nodeIds=[];
            $nodeIdsLastUpdateTime=[];
            foreach ($returnData['WhiteBordContent']['data']['nodes'] as $index=>$node){
                $data=$node['data']['data'];
                if (!empty($data['ID'])){
                    $nodeIds[$data['ID']]=$index;
                    $nodeIdsLastUpdateTime[$data['ID']]=strtotime($data['LastUpdateTime']);
                }
            }
            $nodeData=[];
            if (!empty($nodeData)){
                $nodeData=$nodeInstance->searchNode('*',[sprintf("ID in (%s)",implode(',',array_keys($nodeIds)))]);
            }
            foreach ($nodeData as $nodeItem){
                $dataBaseLastUpdateTimestamp=strtotime($nodeItem['LastUpdateTime']);
                // 拥有本地文件 && 数据库数据更新
                if (!empty($nodeItem['LocalFilePath']) && $dataBaseLastUpdateTimestamp>$nodeIdsLastUpdateTime[$nodeItem['ID']]){
                    $nData=file_get_contents($nodeItem['LocalFilePath']);
                    $nData=json_decode($nData,1);
                    if (isset($nodeIds[$nodeItem['ID']])){
                        $index=$nodeIds[$nodeItem['ID']];
                        $returnData['WhiteBordContent']['data']['nodes'][$index]['data']['data']=$nodeItem;
                        $returnData['WhiteBordContent']['data']['nodes'][$index]['data']['node_data']=$nData;
                    }
                }
            }
        }
        return self::returnActionResult($returnData);
    }

    public function CreateWhiteBord()
    {
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBord=new WhiteBord($loginUser);
        $title=$this->post['Title'] ?? 'Unknown';
        $type=$this->post['Type'] ?? WhiteBordModel::TYPE_DRAFT;
        $whiteBordModel=$whiteBord->addWhiteBord($title,$type);
        return self::returnActionResult(
            [
                'ID'=>$whiteBordModel->ID
            ]
        );
    }

    public function StoreWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $storeData=$this->post['Data'] ?? [];
        $isDraft=$this->post['IsDraft'] ?? true;
        $whiteBordDir=WhiteBordFileManager::getWhiteBordFileDir($id,$loginUser->ID,$isDraft);
        file_put_contents($whiteBordDir,json_encode($storeData));
        $whiteBord=new WhiteBord($loginUser);
        $whiteBordModel=$whiteBord->updateWhiteBord($id,[
            'ID'=>$id,
            'LocalFilePath'=>$whiteBordDir,
            'Type'=>$isDraft?WhiteBordModel::TYPE_DRAFT:WhiteBordModel::TYPE_DATA
        ],true);
        if (!$isDraft){
            $queue=new Queues();
            $whiteBordQueue=new WhiteBordQueue($whiteBordModel,$loginUser);
            $queue->addQueue(WhiteBordQueue::getType(),$id."_".$loginUser->ID,$whiteBordQueue->getStoreData(),true);
        }
        return self::returnActionResult();
    }
}