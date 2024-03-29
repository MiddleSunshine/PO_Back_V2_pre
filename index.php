<?php
require_once __DIR__.DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php";

$withoutLogined=[
    'LoginController'=>[
        'CheckLogin'=>1,// 需要验证 sign
        'Login'=>0 // 不需要验证 sign
    ],
    'IndexController'=>[
        'Index'=>0
    ]
];

$action=ucwords($_GET['action'] ?? '');
$method=ucwords($_GET['method'] ?? '');
$postData=file_get_contents('php://input', 'r');
$postData=json_decode($postData,1);
try {
    if(!isset($withoutLogined[$action][$method])){
        // 并不是可以跳过登陆验证的路由
        $token=$_GET['sign'] ?? '';
        if(!LoginController::checkUserLogined($token)){
            // token 验证失败
            echo json_encode(Base::returnActionResult($_GET,false,'请先登陆',true));
            return false;
        }
    }
    if (empty($_GET['sign']) && $withoutLogined[$action][$method]){
        echo json_encode(Base::returnActionResult($_GET,false,'账号异常，请重新登陆'),true);
    }
    $instance=new $action($_GET,$postData ?? '',$_GET['sign']);
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

