<?php

abstract class QueueInstance{
    abstract public static function getType():string;
    abstract public function getStoreData():array|string;
    abstract static public function handleQueue($queueStoreData):bool;
}