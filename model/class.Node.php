<?php

class Node extends BaseUserModel{

    public function updateNode(){

    }

    public static function getModel(): NodeModel
    {
        $mode=new NodeModel();
        $mode->setTable(static::$tableName);
        return $mode;
    }

    protected static function getTableName(): string
    {
        return 'Node';
    }

    protected static function getTableTemplate(): string
    {
        return <<<EOD
(
    ID             int auto_increment
        primary key,
    AddTime        datetime     null,
    LastUpdateTime datetime     null,
    LocalFilePath  varchar(500) null,
    Name           varchar(500) null,
    Type           varchar(100) null
);
create index search
    on Node_1 (Name);
EOD;

    }
}