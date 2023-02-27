<?php

class NodeInstance extends UserTableModel {
    public static $table='';
    public $ID;
    public $Type;
    public $AddTime;
    public $LastUpdateTime;

    public $WhiteBordID;

    public $LocalFilePath;


    public function setTable($user_id)
    {
        if (empty($user_id)){
            throw new Exception("Table Name Error !");
        }
        static::$table="Node_".$user_id;
    }
}