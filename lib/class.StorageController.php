<?php

class StorageController extends Base
{
    public function StoreData()
    {
        $fileInfo=current($_FILES);
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $dataCache=new DataCache();
        $result=$dataCache->uploadFile($fileInfo['tmp_name'],"UserDataStorage".DIRECTORY_SEPARATOR.$loginUser->ID.DIRECTORY_SEPARATOR.time());
        $storage=new Storage($loginUser);
        $storage->addRecord(
            $result['oss-request-url'],
            $result['info']['content_type'],
            $result['info']['upload_content_length']
        );
        return self::returnActionResult([
            'FilePath'=>$result['oss-request-url']
        ]);
    }
}