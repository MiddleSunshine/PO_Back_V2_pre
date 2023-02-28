<?php

class WhiteBordController extends Base{
    public function GetWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordDir=WhiteBordFileManager::getWhiteBordFileDir($id,$loginUser->ID,true);
        $returnData=[
            'WhiteBordContent'=>[]
        ];
        if (file_exists($whiteBordDir)){
            $content=file_get_contents($whiteBordDir);
            $returnData['WhiteBordContent']=json_decode($content,1);
        }
        // todo 这里还要加上 node 和 edges 解析的部分
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
        $whiteBord->updateWhiteBord($id,[
            'LocalFilePath'=>$whiteBordDir,
            'Type'=>$isDraft?WhiteBordModel::TYPE_DRAFT:WhiteBordModel::TYPE_DATA
        ]);
        if (!$isDraft){
            $queue=new Queues();
            $queue->addQueue(WhiteBordQueue::QUEUE_NAME,$id,WhiteBordQueue::updateQueue(),true);
        }
        return self::returnActionResult();
    }
}