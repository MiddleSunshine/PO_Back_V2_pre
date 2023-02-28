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

    public function StoreWhiteBordAsDraft(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordDir=WhiteBordModel::getWhiteBordFileDir($id,$loginUser->ID,true);
        $storeData=$this->post['Data'] ?? [];
        file_put_contents($whiteBordDir,json_encode($storeData));
        return self::returnActionResult();
    }

    public function StoreWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordDir=WhiteBordModel::getWhiteBordFileDir($id,$loginUser->ID);
        $storeData=$this->post['Data'] ?? [];
        // todo 这里考虑减少存储的数据
        file_put_contents($whiteBordDir,json_encode($storeData));
        return self::returnActionResult();
    }
}