<?php

class NodeController extends Base{
    public function SearchNode(){
        $keyword=$this->post['keyword'];
        if (empty($keyword)){
            return self::returnActionResult($this->post,false,'Please input the keyword');
        }
        $loginUser=LoginUser::getLoginUser($this->loginUserToken);
        $search=new GlobalSearch($loginUser);
        $nodeModels=$search->searchNote($keyword);
        $returnData=[];
        foreach ($nodeModels as $nodeModel){
            /**
             * @var $nodeModel NodeModel
             */
            if (!empty($nodeModel->LocalFilePath)){
                $returnData[]=[
                    'data'=>$nodeModel->toArray(),
                    'node_data'=>file_get_contents($nodeModel->LocalFilePath)
                ];
            }
        }
        return self::returnActionResult(
            [
                'nodes'=>$returnData
            ]
        );
    }
}