<?php

class WhiteBordQueue extends QueueInstance {
    public $whiteBordId;
    public $whiteBordFilePath;
    public function __construct(WhiteBordModel $whiteBordModel)
    {
        $this->whiteBordId=$whiteBordModel->ID;
        $this->whiteBordFilePath=$whiteBordModel->LocalFilePath;
    }

    public static function getType(): string
    {
        return 'WhiteBord';
    }

    public function getStoreData(): array
    {
        return [
            'FilePath'=>$this->whiteBordFilePath,
            'ID'=>$this->whiteBordId
        ];
    }

    public static function handleQueue($queueStoreData): bool
    {
        // TODO: Implement handleQueue() method.
    }
}