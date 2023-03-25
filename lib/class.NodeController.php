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
            $returnData[$nodeModel->ID]=[
                'keywords'=>file_get_contents($nodeModel->LocalFilePath),
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