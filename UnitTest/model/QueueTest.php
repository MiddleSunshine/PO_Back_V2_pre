<?php
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."DataBaseTest.php";

class QueueTest extends DataBaseTest{
    public function testAddQueue(){
        $queue=new Queues();
        $queue->addQueue('PHPUnitTest',1,[]);
        $this->assertFileExists($queue->todoQueueIndex.'PHPUnitTest_1');
    }

    /**
     * @depends  testAddQueue
     */
    public function testGetQueue(){
        $queue=new Queues();
        $queue->getQueue(1);
        $this->assertFileExists($queue->processingQueueIndex."1".DIRECTORY_SEPARATOR."PHPUnitTest_1");
    }
}