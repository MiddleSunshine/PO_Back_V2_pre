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
        $connection=new WhiteBordNodeConnection($loginUser);
        $whiteBordIds=[];
        foreach ($nodeModels as $nodeModel){
            /**
             * @var $nodeModel NodeModel
             */
            $whiteBordIds=array_merge(
                $whiteBordIds,
                $connection->getAllWhiteBord($nodeModel->ID)
            );
        }
        $returnData=[];
        if (!empty($whiteBordIds)){
            $whiteBordIds=array_unique($whiteBordIds);

        }
        return self::returnActionResult(

        );
    }
}