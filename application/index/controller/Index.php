<?php
namespace app\index\controller;

use think\Config;
use app\manage\controller\Base;

class Index extends Base
{

    public function index()
    {
        $site_info = [
            'site_title' => Config::get('site_title'),
            'site_keyword' => Config::get('site_keyword'),
            'site_description' => Config::get('site_description')
        ];
        $this->assign('site_info', $site_info);
        
        return $this->fetch();
    }
}
 