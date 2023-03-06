<?php

class Queues{
    const QUEUE_NAME_WHITEBORD='WhiteBord';

    public $todoQueueIndex;
    public $finishedQueueIndex;
    public $errorQueueIndex;

    public $processingQueueIndex;
    public function __construct()
    {
        if (!defined('QUEUES_INDEX')){
            throw new Exception("Please set queue index");
        }
        if (!is_dir(QUEUES_INDEX)){
            throw new Exception("Queue index dir error");
        }
        $this->todoQueueIndex=QUEUES_INDEX."todo".DIRECTORY_SEPARATOR;
        if (!is_dir($this->todoQueueIndex)){
            mkdir($this->todoQueueIndex);
        }
        $this->finishedQueueIndex=QUEUES_INDEX."finished".DIRECTORY_SEPARATOR;
        if (!is_dir($this->finishedQueueIndex)){
            mkdir($this->finishedQueueIndex);
        }
        $this->errorQueueIndex=QUEUES_INDEX."error".DIRECTORY_SEPARATOR;
        if (!is_dir($this->errorQueueIndex)){
            mkdir($this->errorQueueIndex);
        }
        $this->processingQueueIndex=QUEUES_INDEX."processing".DIRECTORY_SEPARATOR;
        if (!is_dir($this->processingQueueIndex)){
            mkdir($this->processingQueueIndex);
        }
    }

    public function setQueue($handlerId){
        $files=scandir($this->todoQueueIndex);
        unset($files[0]);
        unset($files[1]);
        $newQueuePath=$this->processingQueueIndex.$handlerId.DIRECTORY_SEPARATOR;
        if (!is_dir($newQueuePath)){
            mkdir($newQueuePath);
        }
        $queueFilePath='';
        foreach ($files as $file){
            $fd=fopen($this->todoQueueIndex.$file,'r+');
            // 如果能获取到文件锁
            if (flock($fd,LOCK_EX)){
                // 将队列移动到指定的目录下，交给其他进程处理
                $queueFilePath=$newQueuePath.$file;
                copy($this->todoQueueIndex.$file,$queueFilePath);
                flock($fd,LOCK_UN);
                unlink($this->todoQueueIndex.$file);
                break;
            }
            fclose($fd);
        }
        return $queueFilePath;
    }

    public function addQueue(string $queueName,string $id,array|string $data,bool $updateQueueWhenExists=false){
        if (empty($queueName) || empty($id)){
            return false;
        }
        $storeFileName=$this->todoQueueIndex.$queueName."_".$id;
        if (file_exists($storeFileName) && !$updateQueueWhenExists){
            return true;
        }
        file_put_contents($storeFileName,json_encode(['type'=>$queueName,'data'=>$data]));
        return true;
    }

    public function handleQueue($handlerId){
        $queuesDir=$this->processingQueueIndex.$handlerId.DIRECTORY_SEPARATOR;
        $queues=scandir($queuesDir);
        unset($queues[0]);
        unset($queues[1]);
        if (empty($queues)){
            return false;
        }
        foreach ($queues as $queue){
            $queueContent=file_get_contents($queuesDir.$queue);
            $queueContent=json_decode($queueContent,1);
            $handleResult=true;
            switch ($queueContent['type']){
                case WhiteBordQueue::getType():
                    $handleResult=WhiteBordQueue::handleQueue($queueContent['data']);
                    break;
            }
            if ($handleResult){
                unlink($queuesDir.$queue);
            }else{
                copy($queuesDir.$queue,$this->errorQueueIndex.$queue);
                unlink($queuesDir.$queue);
            }
        }

    }
}