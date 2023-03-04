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
        Timer::add(5,[$this,'handleQueue'],[$worker->id]);
    }

    public function handleQueue($handlerId){
        $queueInstance=new Queues();
        $queueInstance->handleQueue($handlerId);
    }
}