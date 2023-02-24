<?php

class WhiteBord extends Base{
    public function GetWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordDir=self::getWhiteBordFileDir($id,$loginUser->ID);
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

    public function StoreWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordDir=self::getWhiteBordFileDir($id,$loginUser->ID);
        $storeData=$this->post['Data'] ?? [];
        // todo 这里考虑减少存储的数据
        file_put_contents($whiteBordDir,json_encode($storeData));
        return self::returnActionResult();
    }

    public static function getWhiteBordDirectory(){
        if (!defined('MD_FILE_INDEX')){
            throw new Exception("Please Set MD File Dir");
        }
        if (!is_dir(MD_FILE_INDEX)){
            throw new Exception("MD Dir Error");
        }
        return MD_FILE_INDEX;
    }

    public static function getWhiteBordFileDir($id,$user_id){
        $userDir=MD_FILE_INDEX.$user_id.DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
        }
        return $userDir.$id.".json";
    }
}