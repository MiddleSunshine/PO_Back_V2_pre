<?php
use Workerman\Worker;
require_once __DIR__.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";
require_once __DIR__.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

require_once __DIR__ . DIRECTORY_SEPARATOR . "Worker" . DIRECTORY_SEPARATOR . "class.DealWithQueue.php";

$dealWithQueue=new DealWithQueue();

$w1=new Worker();
$w1->name='Update WhiteBord';
$w1->count=3;
$w1->onWorkerStart=[$dealWithQueue,'onWorkerStart'];

Worker::runAll();