<?php

class SettingsModel extends BaseModel{
    public $ID;
    public $Name;
    public $Value;

    public static $table="Settings";

    public function GetSettings($ID){
        $this->where([sprintf("ID=%d",$ID)]);
        $this->getOneData();
        return $this->Value;
    }
}