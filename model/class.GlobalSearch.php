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
        $cmd=sprintf("grep -li '%s' %s*",$keywords,WhiteBordFileManager::getNodeFileDir($this->usersModel->ID));
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
            return $returnData;
        }
        $localFilePaths=sprintf('"%s"',implode('","',$localFilePaths));
        $nodesData=$nodeInstance->searchNode('*',[sprintf('LocalFilePath in (%s)',$localFilePaths)]);
        foreach ($nodesData as $nodeItem){
            $instance=$nodeInstance->getNodeModel($nodeItem);
            $returnData[$instance->ID]=$instance;
        }
        return $returnData;
    }

    public function searchWhiteBoard($keywords,$isDraft=false):array
    {
        $returnData=[];
        $tempFileHandler=tmpfile();
        $tempFileInfo=stream_get_meta_data($tempFileHandler);
        $cmd=sprintf("grep -li '%s' %s*",$keywords,WhiteBordFileManager::getWhiteBordFileDir('',$this->usersModel->ID,$isDraft));
        $cmd=sprintf("%s > %s",$cmd,$tempFileInfo['uri']);
        exec($cmd);
        $searchResult=file_get_contents($tempFileInfo['uri']);
        $IDs=[];
        foreach (explode(PHP_EOL,$searchResult) as $item){
            $item=trim($item);
            if (empty($item)){
                continue;
            }
            $filesEachPart=explode(DIRECTORY_SEPARATOR,$item);
            list($ID)=explode(".",end($filesEachPart));
            if (!empty($ID)){
                $IDs[]=$ID;
            }
        }
        if (empty($IDs)){
            return $returnData;
        }
        $whiteBord=new WhiteBord($this->usersModel);
        $whiteBordModel=$whiteBord->getModel();
        $whiteBordModel->select("*");
        $whiteBordModel->where([sprintf('ID in (%s)',implode(",",$IDs))]);
        return $whiteBordModel->getAllData();
    }
}
