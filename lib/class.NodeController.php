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
            'node_data'=>'',
            'data'=>$nodeModel->toArray()
        ];
        if (!empty($nodeModel->LocalFilePath)){
            $returnData['node_data']=file_get_contents($nodeModel->LocalFilePath);
        }
        return self::returnActionResult($returnData);
    }

    public function UpdateNode(){
        $nodeData=$this->post['node_data'] ?? [];
        $data=$this->post['data'] ?? [];
        $data=json_decode($data,1);
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
            file_put_contents($nodeModel->LocalFilePath,$nodeData);
        }
    }

    public function CreateNode(){
        $type=$this->post['data']['Type'] ?? '';
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
            'LocalFilePath'=>WhiteBordFileManager::getNodeFileDir($loginUser->ID,time())
        ]);
        $node_data=$this->post['node_data'] ?? '[]';
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
