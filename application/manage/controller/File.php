<?php
namespace app\manage\controller;

use think\Config;
use think\Request;
use app\common\model\manage\FileModel;
use app\common\logic\manage\FileLogic;
use app\common\logic\common\CommonLogic;

class File extends Base
{

    /**
     * 文件列表
     */
    public function index(Request $request)
    {
        $this->site_title = '附件列表';
        
        $map = [];
        
        // 类型
        $type = $request->param('type', '');
        if (! empty($type)) {
            $map['file_type'] = $type;
        }
        $this->assign('type', $type);
        
        // start_time
        $start_time = $request->param('start_time', '');
        $this->assign('start_time', $start_time);
        
        // end_time
        $end_time = $request->param('end_time', '');
        $this->assign('end_time', $end_time);
        
        if (! empty($start_time) && ! empty($end_time)) {
            $map['create_time'] = [
                'between',
                [
                    strtotime($start_time),
                    strtotime($end_time)
                ]
            ];
        } elseif (! empty($start_time)) {
            $map['create_time'] = [
                'egt',
                strtotime($start_time)
            ];
        } elseif (! empty($end_time)) {
            $map['create_time'] = [
                'elt',
                strtotime($end_time)
            ];
        }
        
        // 关键词
        $keyword = $request->param('keyword', '');
        if (! empty($keyword)) {
            $map['file_hash'] = [
                'like',
                '%' . $keyword . '%'
            ];
        }
        $this->assign('keyword', $keyword);
        
        $total_count = FileModel::where($map)->count();
        $this->assign('total_count', $total_count);
        
        $list = FileModel::where($map)->order('id desc')->paginate(10);
        $res = array();
        foreach ($list as $vo) {
            $vo['file_size'] = CommonLogic::formatBytes($vo['file_size']);
            $res[] = $vo;
        }
        $this->assign('list', $res);
        $this->assign('page', $list->render());
        
        // 上传类型
        $upload_type = Config::get('upload_type');
        $this->assign('upload_type', $upload_type);
        
        return $this->fetch();
    }

    /**
     * 删除文件
     */
    public function delFile(Request $request)
    {
        $file_id = $request->param('file_id');
        if (empty($file_id)) {
            return $this->error('文件ID为空');
        }
        
        $res = FileLogic::delFile($file_id);
        if ($res['code'] == 1) {
            return $this->success('删除文件成功', 'history.go(0)');
        } else {
            return $this->error($res['msg']);
        }
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        $this->site_title = '文件上传';
        
        return $this->fetch();
    }
}