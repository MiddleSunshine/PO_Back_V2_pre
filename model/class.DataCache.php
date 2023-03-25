<?php

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";
use OSS\OssClient;
class DataCache{

    const ACCESS_KEY_ID = 'LTAI5t5wQ415i8qz4sbC9bqj';
    const ACCESS_KEY_SECRET = 'RXpqlgjsw5MAHfyccEhGGUY9QuMuk7';
    const END_POINT = 'oss-cn-shanghai.aliyuncs.com';
    const LONG_STORE_BUCKET = 'oss-file-cache';
// 建议短期存储时使用
    protected $oss;

    public function uploadFile($uploadFilePath,$storeFileName)
    {
        return $this->oss->uploadFile(
            self::LONG_STORE_BUCKET,
            $storeFileName,
            $uploadFilePath
        );
    }
}