<?php

// 允许跨域
header("Access-Control-Allow-Origin: *");
ini_set('date.timezone','Asia/Shanghai');
define("PROD_DB_NAME","PO_V2");
define("DEV_PROD_DB_NAME","PO_V2_Pre");
define("PROD_DB_HOST","127.0.0.1");
define("PROD_DB_USER","debian-sys-maint");
define("PROD_DB_PASS","tmXzyEBBwd1mbiBI");
define("PROD_DB_SOCKET","");
define("DEBUG_MODE",true);
define("MYSQL_SET_NAMES","utf8");
//define('TIME_ZONE','Asia/Shanghai');
define("INDEX_FILE",dirname(__DIR__));
define("DATA_INDEX",INDEX_FILE.DIRECTORY_SEPARATOR."AllData".DIRECTORY_SEPARATOR);
define("MD_FILE_INDEX",DATA_INDEX."md".DIRECTORY_SEPARATOR);
define("QUEUES_INDEX",DATA_INDEX."Queues".DIRECTORY_SEPARATOR);
define('LOGIN_USERS',DATA_INDEX."users".DIRECTORY_SEPARATOR);

define("ES_SERVER","http://127.0.0.1:7700");
define("AUTH_TOKEN",'h48hsihoshohsjijop803i0josnohog');

function __autoload2($class){
    $fileName=INDEX_FILE.DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."class.".$class.".php";
    if (file_exists($fileName)){
        require_once $fileName;
    }else{
        $fileName=INDEX_FILE.DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."class.".$class.".php";
        if (file_exists($fileName)){
            require_once $fileName;
        }
    }
}

 spl_autoload_register("__autoload2");


if (defined('DATA_INDEX') && !is_dir(DATA_INDEX)){
    mkdir(DATA_INDEX);
    chmod(DATA_INDEX,0777);
}
if (defined("QUEUES_INDEX") && !is_dir(QUEUES_INDEX)){
    mkdir(QUEUES_INDEX);
    chmod(QUEUES_INDEX,0777);
}

if (defined('MD_FILE_INDEX') && !is_dir(MD_FILE_INDEX)){
    mkdir(MD_FILE_INDEX);
    chmod(QUEUES_INDEX,0777);
}

if(defined('LOGIN_USERS') && !is_dir(LOGIN_USERS)){
    mkdir(LOGIN_USERS);
    chmod(QUEUES_INDEX,0777);
}

function debug($logFileName,$content){
    file_put_contents(INDEX_FILE.DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR.$logFileName.".log",date("Y-m-d H:i:s").PHP_EOL.$content.PHP_EOL,FILE_APPEND);
}


function getFirstAndLastDay($date)
{
    $firstday = date('Y-m-01',strtotime($date));
    $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
    return [
        $firstday,
        $lastday
    ];
}

function check_process($process) {
    $cmd = `ps aux | grep $process | grep 'grep' -v | grep '/bin/sh' -v -c`;
    $count = '' . $cmd . '';
    if ($count > 1) {
        return false;
    } else {
        return true;
    }
}