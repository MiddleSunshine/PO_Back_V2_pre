<?php

class Queues{
    public $todoQueueIndex;
    public $finishedQueueIndex;
    public $errorQueueIndex;
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
    }

    public function addQueue(string $queueName,string $id,array $data,bool $updateQueueWhenExists=false){
        if (empty($queueName) || empty($id)){
            return false;
        }
        $storeFileName=$this->todoQueueIndex.$queueName."_".$id;
        if (file_exists($storeFileName) && !$updateQueueWhenExists){
            return true;
        }
        file_put_contents($storeFileName,json_encode($data));
        return true;
    }
}