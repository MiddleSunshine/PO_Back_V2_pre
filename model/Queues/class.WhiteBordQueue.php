<?php

class WhiteBordQueue extends QueueInstance {
    protected $whiteBordModel;
    protected $loginUserInstance;

    public function __construct(WhiteBordModel $whiteBordModel,UsersModel $loginUser)
    {
       $this->whiteBordModel=$whiteBordModel;
       $this->loginUserInstance=$loginUser;
    }

    public static function getType(): string
    {
        return 'WhiteBord';
    }

    public function getStoreData(): array
    {
        return [
            'FilePath'=>$this->whiteBordModel->LocalFilePath,
            'ID'=>$this->whiteBordModel->ID,
            'TableName'=>$this->whiteBordModel->getTable(),
            'User_ID'=>$this->loginUserInstance->ID
        ];
    }

    public static function handleQueue($queueStoreData): bool
    {
        $whiteBordFilePath=$queueStoreData['FilePath'];
        if (!file_exists($whiteBordFilePath)){
            return false;
        }
        $whiteBordData=file_get_contents($whiteBordFilePath);
        $loginUserID=$queueStoreData['User_ID'];
        if (empty($loginUserID)){
            return false;
        }
        $userModel=new UsersModel();
        $userModel->where([sprintf('ID=%d',$loginUserID)]);
        $userModel->getOneData();
        $nodeInstance=new Node($userModel);
        $nodes=[];
        foreach ($whiteBordData['data']['nodes'] as $node){
            $nodes[]=$node['data'];
        }
        $nodeModels=$nodeInstance->updateNode($nodes);
        
    }
}