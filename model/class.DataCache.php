<?php

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
use OSS\OssClient;
class DataCache{

    const ACCESS_KEY_ID = '';
    const ACCESS_KEY_SECRET = '';
    const END_POINT = 'oss-cn-shanghai.aliyuncs.com';
    const LONG_STORE_BUCKET = '';
// 建议短期存储时使用
    protected $oss;

    public function uploadFile($uploadFilePath,$storeFileName)
    {
        $this->oss=new OssClient(self::ACCESS_KEY_ID,self::ACCESS_KEY_SECRET,self::END_POINT);
        return $this->oss->uploadFile(
            self::LONG_STORE_BUCKET,
            $storeFileName,
            $uploadFilePath
        );
    }
}