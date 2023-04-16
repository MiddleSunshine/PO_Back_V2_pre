<?php
require_once __DIR__.DIRECTORY_SEPARATOR."class.QueueInstance.php";
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
        $whiteBordData=json_decode($whiteBordData,1);
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
            $node['data']['data']['Type']=$node['type'];
            if (!($node['data']['save_into_database'] ?? true)){
                continue;
            }
//            switch ($node['type']){
//                case 'WhiteBoardNode':
//                    $node['data']['data']['Name']=$node['data']['node_data']['Title'];
//                    break;
//            }
            $nodes[]=$node['data']['data'];
        }
        // 更新 node 信息
        $nodeModels=$nodeInstance->updateNode($nodes);
        $nodeIds=[];
        foreach (($whiteBordData['data']['nodes'] ?? []) as $index=>$node){
            /**
             * @var $nodeModel NodeModel
             */
            $nodeModel=$nodeModels[$index] ?? false;
            if (!empty($nodeModel->ID)){
                $nodeIds[$nodeModel->ID]='';
                // 将数据写回进 whitebord.json 中
                $whiteBordData['data']['nodes'][$index]['data']['data']=$nodeModel->toArray();
                $saveNodeData=true;
                switch ($nodeModel->Type){
//                    case 'WhiteBoardNode':
//                        $data=$node['data']['node_data'];
//                        $whiteBord=new WhiteBord($userModel);
//                        $whiteBordModel=$whiteBord->getModel();
//                        if (!empty($data['ID'])){
//                            $whiteBordModel->updateData(sprintf("ID=%d",$data['ID']),$data);
//                            $node['data']['node_data']=$whiteBordModel->toArray();
//                        }
//                        break;
                    case 'DrawNode':
                        $saveNodeData=false;
                        break;
                }
                // 保存node中需要保存的数据
                $saveNodeData && file_put_contents($nodeModel->LocalFilePath,json_encode($node['data']['node_data'] ?? []));
            }
        }
        // 更新 WhiteBord 和 Node 之间的对应关系
        $whiteBordNodeConnection=new WhiteBordNodeConnection($userModel);
        $whiteBordNodeConnection->updateConnection($queueStoreData['ID'],$nodeIds);
        file_put_contents($whiteBordFilePath,json_encode($whiteBordData));
        return true;
    }
}