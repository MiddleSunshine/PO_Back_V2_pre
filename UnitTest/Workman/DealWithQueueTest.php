<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."DataBaseTest.php";
require_once INDEX_FILE.DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR."Queues".DIRECTORY_SEPARATOR."class.WhiteBordQueue.php";

class DealWithQueueTest extends DataBaseTest {

    public function testAddQueue(){
        $queue=new Queues();
        $loginUser=LoginUser::getLoginUser('cb4d65d9773ed8d9a77bcc57afb3c3b4');
        $whiteBord=new WhiteBord($loginUser);
        $whiteBordModel=$whiteBord->addWhiteBord('Debug',WhiteBordModel::TYPE_DATA);
//        $whiteBordModel=$whiteBord->updateWhiteBord('1','');
        $whiteBordQueue=new WhiteBordQueue($whiteBordModel,$loginUser);
        $queue->addQueue(WhiteBordQueue::getType(),1,$whiteBordQueue->getStoreData());
        $this->assertFileExists(INDEX_FILE.DIRECTORY_SEPARATOR."Queues".DIRECTORY_SEPARATOR."todo".DIRECTORY_SEPARATOR.'WhiteBord_1');
    }

    public function testHandleQueue(){

    }
}
// /home/br/Code/PO_Back_V2_pre/Queues/todo/WhiteBord_1
// /home/br/Code/PO_Back_V2_pre/Queses/todo/WhiteBord_1