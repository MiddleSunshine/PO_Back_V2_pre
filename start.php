<?php
use Workerman\Worker;

require_once __DIR__.DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

require_once __DIR__ . DIRECTORY_SEPARATOR . "Worker" . DIRECTORY_SEPARATOR . "class.DealWithQueue.php";

$updateWhiteBord=new UpdateWhiteBord();

$w1=new Worker();
$w1->name='Update WhiteBord';
$w1->count=3;
$w1->onWorkerStart=[$updateWhiteBord,'onWorkerStart'];

Worker::runAll();