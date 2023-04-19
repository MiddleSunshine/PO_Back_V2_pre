<?php

class NodeController extends Base{
    public function GetNodeDetail(){
        $ID=$this->get['ID'] ?? 0;
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $node=new Node($loginUser);
        $nodeModel=$node->getNodeModel([]);
        $nodeModel->select("*");
        $nodeModel->where([sprintf("ID=%d",$ID)]);
        $nodeModel->getOneData();
        $returnData=[
            'node_data'=>'[]',
            'data'=>$nodeModel->toArray()
        ];
        if (!empty($nodeModel->LocalFilePath)){
            $returnData['node_data']=file_get_contents($nodeModel->LocalFilePath);
            empty($returnData['node_data']) && $returnData['node_data']='[]';
        }
        return self::returnActionResult($returnData);
    }

    public function UpdateNode(){
        $nodeData=$this->post['node_data'] ?? [];
        $data=$this->post['data'] ?? [];
        is_string($data) && $data=json_decode($data,1);
        if (empty($data['ID'])){
            return self::returnActionResult($this->post,false,'Data Error');
        }
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $node=new Node($loginUser);
        $nodeModel=$node->getNodeModel($data);
        $nodeModel->select("*");
        $nodeModel->where([sprintf("ID=%d;",$data['ID'])]);
        $nodeModel->getOneData();
        if ($nodeModel->updateNode($data) && !empty($nodeModel->LocalFilePath)){
            is_array($nodeData) && $nodeData=json_encode($nodeData,JSON_UNESCAPED_UNICODE);
            file_put_contents($nodeModel->LocalFilePath,$nodeData);
        }
        return self::returnActionResult([],true);
    }

    public function CreateNode(){
        $type=$this->post['data']['Type'] ?? '';
        $node_id=$this->post['data']['node_id'] ?? '';
        if (empty($node_id)){
            return  self::returnActionResult($this->post,false,'Data Error');
        }
        if (empty($type)){
            return self::returnActionResult($this->post,false,'Data Error');
        }
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $node=new Node($loginUser);
        $nodeModel=$node->getNodeModel([]);
        $nodeModel->insertOneData([
            'Type'=>$type,
            'Name'=>$this->post['data']['Name'] ?? '',
            'AddTime'=>date("Y-m-d H:i:s"),
            'LastUpdateTime'=>date("Y-m-d H:i:s"),
            'LocalFilePath'=>WhiteBordFileManager::getNodeFileDir($loginUser->ID,time()),
            'node_id'=>addslashes($node_id)
        ]);
        $node_data=$this->post['node_data'] ?? '[]';
        is_array($node_data) && $node_data=json_encode($node_data,JSON_UNESCAPED_UNICODE);
        $nodeModel->getLastestData();
        file_put_contents($nodeModel->LocalFilePath,$node_data);
        return self::returnActionResult([
            'data'=>$nodeModel->toArray()
        ]);
    }
    public function SearchNode(){
        $keyword=$this->post['keyword'];
        if (empty($keyword)){
            return self::returnActionResult($this->post,false,'Please input the keyword');
        }
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $search=new GlobalSearch($loginUser);
        $nodeModels=$search->searchNote($keyword);
        $returnData=[];
        $filterType=$this->post['type'] ?? '';
        foreach ($nodeModels as $nodeModel){        
            /**
             * @var $nodeModel NodeModel
             */
            if (!empty($filterType) && $nodeModel->Type!=$filterType) {
                continue;
            }
            $returnData[$nodeModel->ID]=[
                'keywords'=>file_get_contents($nodeModel->LocalFilePath),
                'node'=>[
                    'data'=>[
                        'data'=>$nodeModel->toArray(),
                        'node_data'=>json_decode(file_get_contents($nodeModel->LocalFilePath),1)
                    ]
                ],
                'Whiteboards'=>[]
            ];
            $connection=new WhiteBordNodeConnection($loginUser);
            $whiteBordIds=$connection->getAllWhiteBord($nodeModel->ID);
            if (!empty($whiteBordIds)){
                $whiteBord=new WhiteBord($loginUser);
                foreach ($whiteBordIds as $whiteBordId){
                    $whiteBordModel=$whiteBord->seleteWhiteBord("ID,Title",['ID='.$whiteBordId]);
                    $returnData[$nodeModel->ID]['Whiteboards'][]=$whiteBordModel->toArray();
                }
            }
        }
        return self::returnActionResult(
            ['nodes'=>array_values($returnData)]
        );
    }
}
