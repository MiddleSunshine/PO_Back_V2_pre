<?php

class WhiteBord extends Base{
    public function GetWhiteBord(){
        $id=$this->get['ID'] ?? '0';
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);

    }

    public static function getWhiteBordDirectory(){
        if (!defined('MD_FILE_INDEX')){
            throw new Exception("Please Set MD File Dir")
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