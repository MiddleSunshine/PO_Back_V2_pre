<?php
require_once INDEX_FILE . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "Queues" . DIRECTORY_SEPARATOR . "class.WhiteBordQueue.php";
require_once INDEX_FILE . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "class.Node.php";
require_once INDEX_FILE . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "UserReleatedTable" . DIRECTORY_SEPARATOR . "class.WhiteBordModel.php";

class WhiteBordController extends Base
{
    public function WhiteBoardRoad()
    {
        $Ids = $this->post['Ids'] ?? "0";
        $loginUser = LoginUser::getLoginUser($this->loginUserToken);
        $whiteBoard = new WhiteBord($loginUser);
        $model = $whiteBoard->getModel();
        $model->select("ID,Title");
        $model->where([sprintf("ID in (%s)",$Ids)]);
        $whiteBoards=$model->getAllData('ID');
        $Ids=explode(',',$Ids);
        $returnData=[];
        $roads=[];
        foreach ($Ids as $Id){
            $roads[]=$Id;
            if(isset($whiteBoards[$Id])){
                $returnData[]=array_merge($whiteBoards[$Id],[
                    'path'=>implode(',',$roads)
                ]);
            }
        }
        return self::returnActionResult(
            [
                'roads'=>$returnData
            ]
        );
    }
    public function SearchWhiteBoard()
    {
        $keyword = $this->post['Keywords'] ?? '';
        $isDraft = ($this->post['Type'] ?? WhiteBordModel::TYPE_DATA);
        if (empty($keyword)) {
            return self::returnActionResult($this->post, false, "Please input the keywords");
        }
        $search = new GlobalSearch(LoginUser::getLoginUser($this->loginUserToken));
        return self::returnActionResult(
            [
                'WhiteBoards' => $search->searchWhiteBoard($keyword, $isDraft)
            ]
        );
    }

    public function GetWhiteBord()
    {
        $id = $this->get['ID'] ?? '0';
        $loginUser = LoginUser::getLoginUser($this->loginUserToken);
        $whiteBordInstance = new WhiteBord($loginUser);
        $whiteBordModel = $whiteBordInstance->seleteWhiteBord('*', [sprintf('ID=%d', $id)]);
        $whiteBordDir = $whiteBordModel->LocalFilePath;
        $returnData = [
            'WhiteBoard' => $whiteBordModel->toArray(),
            'WhiteBordContent' => [
                'data' => [
                    'nodes' => [],
                    'edges' => []
                ],
                'settings' => []
            ]
        ];
        if (file_exists($whiteBordDir)) {
            $content = file_get_contents($whiteBordDir);
            $returnData['WhiteBordContent'] = json_decode($content, 1);
        }
        if (!empty($returnData['WhiteBordContent']['data']['nodes'])) {
            $nodeInstance = new Node($loginUser);
            $nodeIds = [];
            $nodeIdsLastUpdateTime = [];
            foreach ($returnData['WhiteBordContent']['data']['nodes'] as $index => $node) {
                $data = $node['data']['data'];
                if (!empty($data['node_id'])) {
                    !isset($nodeIds[$data['node_id']]) && $nodeIds[$data['node_id']] = [];
                    $nodeIds[$data['node_id']][] = $index;
//                    $nodeIdsLastUpdateTime[$data['node_id']] = strtotime($data['LastUpdateTime']);
                }
            }
            $nodeData = [];
            if (!empty($nodeIds)) {
                $nodeData = $nodeInstance->searchNode('*', [
                    sprintf(
                        "node_id in (%s)",
                        '"' . implode('","', array_keys($nodeIds)) . '"'
                    )
                ]);
            }
            foreach ($nodeData as $nodeItem) {
//                $dataBaseLastUpdateTimestamp = empty($nodeItem['LastUpdateTime']) ? 0 : strtotime($nodeItem['LastUpdateTime']);
                // 拥有本地文件 && 数据库数据更新
                if (!empty($nodeItem['LocalFilePath']) && isset($nodeIdsLastUpdateTime[$nodeItem['node_id']])) {
                    $nData = file_get_contents($nodeItem['LocalFilePath']);
                    $nData = json_decode($nData, 1);
                    if (isset($nodeIds[$nodeItem['node_id']])) {
                        foreach ($nodeIds[$nodeItem['node_id']] as $index) {
                            $returnData['WhiteBordContent']['data']['nodes'][$index]['data']['data'] = $nodeItem;
                            $returnData['WhiteBordContent']['data']['nodes'][$index]['data']['node_data'] = $nData;
                        }
                    }
                }
            }
        }
        return self::returnActionResult($returnData);
    }

    public function CreateWhiteBord()
    {
        $loginUser = LoginUser::getLoginUser($this->loginUserToken);
        $whiteBord = new WhiteBord($loginUser);
        $title = $this->post['Title'] ?? 'Unknown';
        $type = $this->post['Type'] ?? WhiteBordModel::TYPE_DRAFT;
        $whiteBordModel = $whiteBord->addWhiteBord($title, $type);
        return self::returnActionResult(
            [
                'data' => $whiteBordModel->toArray()
            ]
        );
    }

    public function StoreWhiteBord()
    {
        $id = $this->get['ID'] ?? '0';
        $loginUser = LoginUser::getLoginUser($this->loginUserToken);
        $storeData = $this->post['Data'] ?? [];
        $isDraft = $this->post['IsDraft'] ?? true;
        $whiteBordDir = WhiteBordFileManager::getWhiteBordFileDir($id, $loginUser->ID, $isDraft);
        file_put_contents($whiteBordDir, json_encode($storeData, JSON_UNESCAPED_UNICODE));
        $whiteBord = new WhiteBord($loginUser);
        $whiteBordModel = $whiteBord->updateWhiteBord($id, [
            'ID' => $id,
            'LocalFilePath' => $whiteBordDir,
            'Type' => $isDraft ? WhiteBordModel::TYPE_DRAFT : WhiteBordModel::TYPE_DATA
        ], true);
        if (!$isDraft) {
            $queue = new Queues();
            $whiteBordQueue = new WhiteBordQueue($whiteBordModel, $loginUser);
            $queue->addQueue(WhiteBordQueue::getType(), $id . "_" . $loginUser->ID, $whiteBordQueue->getStoreData(), true);
        }
        return self::returnActionResult();
    }
}
