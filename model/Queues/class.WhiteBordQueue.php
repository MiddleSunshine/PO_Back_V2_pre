<?php

class WhiteBordQueue extends QueueInstance {
    protected $whiteBordModel;
    public function __construct(WhiteBordModel $whiteBordModel)
    {
       $this->whiteBordModel=$whiteBordModel;
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
            'TableName'=>$this->whiteBordModel->getTable()
        ];
    }

    public static function handleQueue($queueStoreData): bool
    {
        return true;
    }
}