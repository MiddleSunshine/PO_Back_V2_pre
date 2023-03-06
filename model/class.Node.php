<?php
require_once __DIR__.DIRECTORY_SEPARATOR."UserReleatedTable".DIRECTORY_SEPARATOR."class.NodeModel.php";
class Node extends BaseUserModel{

    public function updateNode($nodes):array
    {
        $nodeIds=[];
        foreach ($nodes as $index=>$node){
            $nodeModel=new NodeModel();
            if (!empty($node['ID'])){
                // update
                $nodeModel->where([sprintf('ID=%d',$node['ID'])]);
                $nodeModel->getOneInstance();
                $nodeModel->updateNode($node);
            }else{
                // insert
                $node['LocalFilePath']=WhiteBordFileManager::getNodeFileDir($this->userModel->ID,time()."_".$index);
                $nodeModel->newNode($node);
            }
            $nodeIds[$index]=$nodeModel;
        }
        return $nodeIds;
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