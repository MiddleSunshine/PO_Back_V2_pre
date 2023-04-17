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

    public static function getNodeFileDir($user_id,$nodeId=''):string
    {
        $dir=static::getStoreFileDir().$user_id.DIRECTORY_SEPARATOR;
        if (!is_dir($dir)){
            mkdir($dir);
            chmod($dir,0777);
        }
        $dir.="Node".DIRECTORY_SEPARATOR;
        if (!is_dir($dir)){
            mkdir($dir);
            chmod($dir,0777);
        }
        if (empty($nodeId)){
            return $dir;
        }
        $filePath=$dir.$nodeId.".json";
        $endLessPrevent=0;
        while (file_exists($filePath) && $endLessPrevent<10){
            $endLessPrevent++;
            $filePath=$dir.$nodeId."_".rand(0,1000).".json";
        }
        if (!file_exists($filePath)){
            touch($filePath);
            chmod($filePath, 0777);
        }
        return $filePath;
    }

    public static function getWhiteBordFileDir($id,$user_id,$isDraft=false):string
    {
        $userDir=self::getStoreFileDir().$user_id.DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
            chmod($userDir,0777);
        }
        $userDir.=($isDraft?'Draft':'WhiteBord').DIRECTORY_SEPARATOR;
        if (!is_dir($userDir)){
            mkdir($userDir);
            chmod($userDir,0777);
        }
        if (empty($id)){
            return $userDir;
        }
        $filePath=$userDir.$id.".json";
        if (!file_exists($filePath)){
            touch($filePath);
            chmod($filePath,0777);
        }
        return $filePath;
    }
}