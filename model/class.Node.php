<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "UserReleatedTable" . DIRECTORY_SEPARATOR . "class.NodeModel.php";

class Node extends BaseUserModel
{


    public function getNodeModel($data)
    {
        $node = new NodeModel($data);
        $node->setTable($this->getModalTableName());
        return $node;
    }

    public function searchNode($fields, array $where): array
    {
        $model = $this->getModel();
        $model->select($fields);
        $model->where($where);
        return $model->getAllData();
    }

    public function updateNode($nodes): array
    {
        $nodeIds = [];
        foreach ($nodes as $index => $node) {
            $nodeModel = new NodeModel();
            $nodeModel->setTable($this->getModalTableName());
            if (!empty($node['ID'])) {
                // update
                $nodeModel->where([sprintf('ID=%d', $node['ID'])]);
                $nodeModel->getOneInstance();
                $nodeModel->updateNode($node);
            } else {
                // 搜索 node_id
                $nodeModel->where([sprintf('node_id="%s"', $node['node_id'])]);
                $nodeModel->getOneData();
                // 如果 ID 不为0,表示在其他地方已经更新了数据，这是老数据
                if (empty($nodeModel->ID)) {
                    // insert
                    $node['LocalFilePath'] = WhiteBordFileManager::getNodeFileDir($this->userModel->ID, time() . "_" . $index);
                    $nodeModel->newNode($node);
                }
            }
            $nodeIds[$index] = $nodeModel;
        }
        return $nodeIds;
    }

    public function getModel(): NodeModel
    {
        $mode = new NodeModel();
        $mode->setTable($this->getModalTableName());
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
    Type           varchar(100) null,
    node_id        varchar(500) null
);
EOD;
    }
}