<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.BaseModel.php";

class WhiteBordFileManager {
    public static function getWhiteBordDirectory():string
    {
        if (!defined('MD_FILE_INDEX')){
            throw new Exception("Please Set MD File Dir");
        }
        if (!is_dir(MD_FILE_INDEX)){
            throw new Exception("MD Dir Error");
        }
        return MD_FILE_INDEX;
    }

    public static function getWhiteBordFileDir($id,$user_id,$isDraft=false):string
    {
        $userDir=self::getWhiteBordDirectory().$user_id.DIRECTORY_SEPARATOR.($isDraft?'Draft':'WhiteBord').DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
        }
        return $userDir.$id.".json";
    }
}