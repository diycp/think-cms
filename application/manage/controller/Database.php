<?php
namespace app\manage\controller;

use \mysqli;
use \MySQLDump;
use think\Db;
use think\Config;
use think\Url;
use think\Request;
use app\common\model\manage\DatabaseModel;
use app\common\logic\common\CommonLogic;

class DataBase extends Base
{

    /**
     * 数据表
     */
    public function index()
    {
        $this->site_title = '备份数据';
        
        $list = array_map('array_change_key_case', Db::query('SHOW TABLE STATUS'));
        foreach ($list as &$vo) {
            $vo['data_format'] = CommonLogic::formatBytes($vo['data_length']);
        }
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 备份表
     */
    public function bakup()
    {
        try {
            $database = Config::get('database');
            $bakup_path = Config::get('bakup_path');
            $file_path = '/dump_' . date('Ymd_His') . '.sql';
            $dump = new MySQLDump(new mysqli($database['hostname'], $database['username'], $database['password'], $database['database'], $database['hostport']));
            $dump->save($bakup_path . $file_path);
            
            $data = [
                'dump_uid' => $this->user_id,
                'dump_size' => filesize($bakup_path . $file_path),
                'dump_file' => $file_path,
                'dump_time' => time()
            ];
            DatabaseModel::add($data);
            
            return $this->success('备份数据库成功', Url::build('database/bakupLog'));
        } catch (\Exception $e) {
            return $this->error('备份数据库失败:' . $e->getMessage());
        }
    }

    /**
     * 优化表
     */
    public function optimize()
    {
        $list = array_map('array_change_key_case', Db::query('SHOW TABLE STATUS'));
        foreach ($list as $vo) {
            Db::query('OPTIMIZE TABLE `' . $vo['name'] . '`');
        }
        return $this->success('优化表成功', Url::build('database/index'));
    }

    /**
     * 修复表
     */
    public function repair()
    {
        $list = array_map('array_change_key_case', Db::query('SHOW TABLE STATUS'));
        foreach ($list as $vo) {
            Db::query('REPAIR TABLE `' . $vo['name'] . '`');
        }
        return $this->success('修复表成功', Url::build('database/index'));
    }

    /**
     * 备份记录
     */
    public function bakupLog()
    {
        $this->site_title = '备份记录';
        
        $list = DatabaseModel::order('id desc')->select();
        foreach ($list as &$vo) {
            $vo['dump_size'] = CommonLogic::formatBytes($vo['dump_size']);
        }
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 删除记录
     */
    public function delBakup(Request $request)
    {
        $bakup_id = $request->param('bakup_id');
        if (empty($bakup_id)) {
            return $this->error('备份ID为空');
        }
        
        // 删除物理文件
        try {
            $bakup_path = Config::get('bakup_path');
            $bakup = DatabaseModel::get($bakup_id);
            unlink($bakup_path . $bakup['dump_file']);
        } catch (\Exception $e) {}
        
        DatabaseModel::del($bakup_id);
        return $this->success('删除备份记录成功', Url::build('database/bakupLog'));
    }
}