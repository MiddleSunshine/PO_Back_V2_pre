<?php

abstract class UserTableModel extends BaseModel{
    public static $table='';
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    abstract public function setTable($user_id);
}