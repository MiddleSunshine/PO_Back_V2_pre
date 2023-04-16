<?php

class Base
{
    protected $get;
    protected $post;

    protected $loginUserToken;

    public function __construct($get = [], $post = [],$token='')
    {
        $this->get = $get;
        $this->post = $post;
        $this->loginUserToken=$token;
    }

    public static function returnActionResult($returnData = [], $isSuccess = true, $message = '',$forceLogin=false)
    {
        return [
            'Status' => $isSuccess ? 1 : 0,
            'Message' => $message,
            'Data' => $returnData,
            'NeedLogin'=>$forceLogin?1:0
        ];
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