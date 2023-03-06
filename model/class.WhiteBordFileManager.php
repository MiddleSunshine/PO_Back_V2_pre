<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.BaseModel.php";

class WhiteBordFileManager {
    public static function getStoreFileDir():string
    {
        if (!defined('MD_FILE_INDEX')){
            throw new Exception("Please Set MD File Dir");
        }
        if (!is_dir(MD_FILE_INDEX)){
            throw new Exception("MD Dir Error");
        }
        return MD_FILE_INDEX;
    }

    public static function getNodeFileDir($user_id,$nodeId):string
    {
        $dir=static::getStoreFileDir().$user_id.DIRECTORY_SEPARATOR;
        if (!is_dir($dir)){
            mkdir($dir);
        }
        $dir.="Node".DIRECTORY_SEPARATOR;
        if (!is_dir($dir)){
            mkdir($dir);
        }
        return $dir.$nodeId.".json";
    }

    public static function getWhiteBordFileDir($id,$user_id,$isDraft=false):string
    {
        $userDir=self::getStoreFileDir().$user_id.DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
        }
        $userDir.=($isDraft?'Draft':'WhiteBord').DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
        }
        return $userDir.$id.".json";
    }
}