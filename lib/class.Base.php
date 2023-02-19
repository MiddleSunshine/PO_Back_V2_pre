<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "class.MysqlPdo.php";

class Base
{
    public static $table = '';
    protected $get;
    protected $post;
    public $pdo;
    protected $authToken;
    protected $authCheck = false;
    protected $doNotCheckLogin=false;

    public function __construct($get = [], $post = '')
    {
        $this->get = $get;
        $this->post = $post;
        $this->em_getallheaders();
        $this->pdo = new MysqlPdo();
    }

    public static function returnActionResult($returnData = [], $isSuccess = true, $message = '')
    {
        return [
            'Status' => $isSuccess ? 1 : 0,
            'Message' => $message,
            'Data' => $returnData
        ];
    }

    public function getTableField($table = '')
    {
        $sql = "desc " . ($table ?: static::$table);
        $columns = $this->pdo->getRows($sql);
        return array_column($columns, 'Field');
    }

    public static function getDateRange($startTime, $endTime, $dateFormat)
    {
        $returnData[] = date($dateFormat, strtotime($startTime));
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $oneDay = 24 * 60 * 60;
        while ($startTime <= $endTime) {
            $returnData[] = date($dateFormat, $startTime + $oneDay);
            $startTime += $oneDay;
        }
        return $returnData;
    }

    public function em_getallheaders()
    {
        $this->authToken = $this->get['sign'] ?? '';
        if (empty($this->authToken) && defined('Login_Token')){
            $this->authToken=Login_Token;
        }
    }

    public static function getLocalDateN($timestamp){
        $return='';
        switch (date('N',$timestamp)){
            case 1:
                $return='一';
                break;
            case 2:
                $return='二';
                break;
            case 3:
                $return='三';
                break;
            case 4:
                $return='四';
                break;
            case 5:
                $return='五';
                break;
            case 6:
                $return='六';
                break;
            case 7:
                $return='日';
                break;
        }
        return $return;
    }
}