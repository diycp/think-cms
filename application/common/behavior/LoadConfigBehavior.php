<?php
namespace app\common\behavior;

use think\Config;
use app\common\logic\manage\ConfigLogic;

/**
 * 读取数据配置
 */
class LoadConfigBehavior
{

    public function run(&$params)
    {
        $config = ConfigLogic::getKeyValue();
        Config::set($config);
    }
}