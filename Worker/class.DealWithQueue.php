<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";

use Workerman\Worker;
use Workerman\Lib\Timer;
class DealWithQueue{
    /**
     * @param $worker Worker
     * @return void
     */
    public function onWorkerStart($worker){
        Timer::add(10,[$this,'handleQueue'],[$worker->id]);
        Timer::add(10,[$this,'arrangeQueue'],[$worker->id]);
    }

    public function arrangeQueue($handlerId){
        $queueInstance=new Queues();
        $queueInstance->setQueue($handlerId);
    }

    public function handleQueue($handlerId){
        $queueInstance=new Queues();
        $queueInstance->handleQueue($handlerId);
    }
}