<?php

class IndexController extends Base{
    public function Index(){
        $settingsModel=new SettingsModel();
        return self::returnActionResult([
            'Title'=>$settingsModel->GetSettings(1) // 首页标语
        ]);
    }
}