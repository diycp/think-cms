<?php
namespace app\manage\behavior;

use think\Config;
use think\Request;
use app\common\model\manage\ActionLogModel;
use app\manage\logic\LoginLogic;

/**
 * 记录访问记录
 */
class ActionLogBehavior
{

    public function run(&$params)
    {
        $user = LoginLogic::getLoginUser();
        if (empty($user)) {
            return false;
        }
        
        $request = Request::instance();
        $param = $request->param();
        $log_level = Config::get('log_level_manage');
        
        $log_method = [];
        if ($request->isAjax()) {
            $log_method[] = 'ajax';
        }
        if ($request->isOptions()) {
            $log_method[] = 'options';
        }
        if ($request->isPost()) {
            $log_method[] = 'post';
        }
        if ($request->isGet()) {
            $log_method[] = 'get';
        }
        
        if ($log_level == 0) {
            return false;
        } elseif ($log_level == 1) {
            // ajax或者post方法，有数据
            if (! ($request->isAjax() || $request->isPost()) || count($param) == 0) {
                return false;
            }
        } elseif ($log_level == 2) {
            // ajax或者post方法
            if (! ($request->isAjax() || $request->isPost())) {
                return false;
            }
        } elseif ($log_level == 3) {
            // 有数据
            if (count($param) == 0) {
                return false;
            }
        } else {
            // 所有记录
        }
        
        $data = [
            'log_uid' => $user['user_id'],
            'log_method' => implode(',', $log_method),
            'log_module' => $request->module(),
            'log_controller' => $request->controller(),
            'log_action' => $request->action(),
            'log_data' => var_export($param, true),
            'log_time' => time()
        ];
        ActionLogModel::add($data);
        
        return true;
    }
}