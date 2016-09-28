<?php
namespace app\manage\controller;

use think\Request;
use app\common\model\manage\MemberModel;
use app\common\model\manage\ActionLogModel;

class ActionLog extends Base
{

    /**
     * 日志列表
     */
    public function index(Request $request)
    {
        $this->site_title = '日志列表';
        
        $map = [];
        
        // uid
        $uid = $request->param('uid', '');
        if (! empty($uid)) {
            $map['log_uid'] = $uid;
        }
        $this->assign('uid', intval($uid));
        
        // start_time
        $start_time = $request->param('start_time', '');
        $this->assign('start_time', $start_time);
        
        // end_time
        $end_time = $request->param('end_time', '');
        $this->assign('end_time', $end_time);
        
        if (! empty($start_time) && ! empty($end_time)) {
            $map['log_time'] = [
                'between',
                [
                    strtotime($start_time),
                    strtotime($end_time)
                ]
            ];
        } elseif (! empty($start_time)) {
            $map['log_time'] = [
                'egt',
                strtotime($start_time)
            ];
        } elseif (! empty($end_time)) {
            $map['log_time'] = [
                'elt',
                strtotime($end_time)
            ];
        }
        
        // method
        $method = $request->param('method', '');
        if (! empty($method)) {
            $map['log_method'] = [
                'like',
                '%' . $method . '%'
            ];
        }
        $this->assign('method', $method);
        
        // action
        $action = $request->param('action', '');
        if (! empty($action)) {
            $map['log_module|log_controller|log_action'] = $action;
        }
        $this->assign('action', $action);
        
        // keyword
        $keyword = $request->param('keyword', '');
        if (! empty($keyword)) {
            $map['log_data'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        // user_list
        $user_list = MemberModel::getKeyValueList('id', 'id, user_name, user_nick');
        
        // uid_list
        $uid_list = ActionLogModel::field('log_uid')->where($map)
            ->group('log_uid')
            ->select();
        foreach ($uid_list as &$vo) {
            if (isset($user_list[$vo['log_uid']])) {
                $vo = [
                    'name' => $user_list[$vo['log_uid']]['user_name'],
                    'value' => $vo['log_uid']
                ];
            } else {
                $vo = [
                    'name' => 'unknown',
                    'value' => $vo['log_uid']
                ];
            }
        }
        unset($vo);
        $this->assign('uid_list', $uid_list);
        
        // total_count
        $total_count = ActionLogModel::where($map)->count();
        $this->assign('total_count', $total_count);
        
        // list
        $list = ActionLogModel::where($map)->order('id desc')->paginate(10);
        $list_new = [];
        foreach ($list as $vo) {
            if (isset($user_list[$vo['log_uid']])) {
                $vo['log_user_name'] = $user_list[$vo['log_uid']]['user_name'];
            } else {
                $vo['log_user_name'] = 'unknown';
            }
            $list_new[] = $vo;
        }
        $this->assign('list', $list_new);
        $this->assign('page', $list->render());
        
        return $this->fetch();
    }
}