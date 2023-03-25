<?php

class GlobalSearch{
    public $usersModel;
    public function __construct(UsersModel $usersModel)
    {
        $this->usersModel=$usersModel;
    }

    public function searchNote($keywords,$type=''):array{
        $returnData=[];
        $tempFileHandler=tmpfile();
        $tempFileInfo=stream_get_meta_data($tempFileHandler);
        $cmd=sprintf("grep '%s' %s*",$keywords,WhiteBordFileManager::getNodeFileDir($this->usersModel->ID));
        $cmd=sprintf("%s > %s",$cmd,$tempFileInfo['uri']);
        exec($cmd);
        $searchResult=file_get_contents($tempFileInfo['uri']);
        $nodeInstance=new Node($this->usersModel);
        $localFilePaths=[];
        foreach (explode(PHP_EOL,$searchResult) as $item){
            $item=trim($item);
            if (empty($item)){
                continue;
            }
            list($localFilePath,$searchData)=explode(':',$item);
            $localFilePaths[]=$localFilePath;
        }
        if (empty($localFilePaths)){
            return [];
        }
        $localFilePaths=sprintf('"%s"',implode('","',$localFilePaths));
        $nodesData=$nodeInstance->searchNode('*',[sprintf('LocalFilePath in (%s)',$localFilePaths)]);
        foreach ($nodesData as $nodeItem){
            $returnData[]=$nodeInstance->getNodeModel($nodeItem);
        }
        return $returnData;
    }
}