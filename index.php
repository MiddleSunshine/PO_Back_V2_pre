<?php
require_once __DIR__.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";

$withoutLogined=[
    'Login'=>[
        'CheckLogin'=>1,
        'Login'=>1
    ]
];

$action=ucwords($_GET['action'] ?? '');
$method=ucwords($_GET['method'] ?? '');
$postData=file_get_contents('php://input', 'r');
try {
    if(!isset($withoutLogined[$action][$method])){
        // 并不是可以跳过登陆验证的路由
        $token=$_GET['sign'] ?? '';
        if(Login::checkUserLogined($token)){
            // token 验证失败
            echo json_encode(Base::returnActionResult($_GET,false,'请先登陆',true));
            return false;
        }

    }
    $instance=new $action($_GET,$postData ?? '');
    echo json_encode($instance->$method());
}catch (\Exception $e){
    echo json_encode(Base::returnActionResult(
        [
            'Exception'=>$e->getMessage(),
            'File'=>$e->getFile(),
            'Line'=>$e->getLine()
        ],
        false
    ));
}
catch (\Throwable $e){
    echo json_encode(Base::returnActionResult(
        [
            'Throwable'=>$e->getMessage(),
            'File'=>$e->getFile(),
            'Line'=>$e->getLine()
        ],
        false
    ));
}

