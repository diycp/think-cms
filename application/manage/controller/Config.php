<?php
namespace app\manage\controller;

use think\Request;
use think\Url;
use app\common\model\manage\ConfigModel;
use app\common\validate\manage\ConfigValidate;
use app\common\logic\manage\ConfigLogic;

class Config extends Base
{

    /**
     * 配置列表
     */
    public function index(Request $request)
    {
        $this->site_title = '配置列表';
        
        $map = [];
        
        // group_name
        $group_name = $request->param('group', '');
        if (! empty($group_name)) {
            $map['config_group'] = $group_name;
        }
        $this->assign('group', $group_name);
        
        $total_count = ConfigModel::where($map)->count();
        $this->assign('total_count', $total_count);
        
        $list = ConfigModel::where($map)->order('config_sort asc')->paginate(10);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        
        // group_list
        $group_list = ConfigModel::field('id, config_group')->group('config_group')
            ->order('config_sort asc')
            ->select();
        $this->assign('group_list', $group_list);
        
        return $this->fetch();
    }

    /**
     * 添加配置
     */
    public function addConfig(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'config_name' => $request->param('config_name'),
                'config_type' => $request->param('config_type'),
                'config_title' => $request->param('config_title'),
                'config_group' => $request->param('config_group'),
                'config_sort' => $request->param('config_sort', 0),
                'config_extra' => $request->param('config_extra'),
                'config_remark' => $request->param('config_remark')
            ];
            
            // 验证数据
            $validate = new ConfigValidate();
            $res = $validate->scene('add')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            ConfigLogic::addConfig($data);
            return $this->success('添加配置成功', Url::build('config/index'));
        } else {
            $this->site_title = '添加配置';
            
            return $this->fetch();
        }
    }

    /**
     * 编辑配置
     */
    public function editConfig(Request $request)
    {
        $config_id = $request->param('config_id');
        if (empty($config_id)) {
            return $this->error('配置ID为空');
        }
        
        if ($request->isPost()) {
            $data = [
                'config_name' => $request->param('config_name'),
                'config_type' => $request->param('config_type'),
                'config_title' => $request->param('config_title'),
                'config_group' => $request->param('config_group'),
                'config_sort' => $request->param('config_sort', 0),
                'config_extra' => $request->param('config_extra'),
                'config_remark' => $request->param('config_remark')
            ];
            
            // 验证数据
            $validate = new ConfigValidate();
            $res = $validate->scene('edit')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            ConfigLogic::saveConfig($data, $config_id);
            return $this->success('修改配置成功', Url::build('config/index'));
        } else {
            $this->site_title = '编辑配置';
            $this->assign('config_id', $config_id);
            
            $config = ConfigModel::get($config_id);
            $this->assign('config', $config);
            
            return $this->fetch();
        }
    }

    /**
     * 删除配置
     */
    public function delConfig(Request $request)
    {
        $config_id = $request->param('config_id');
        if (empty($config_id)) {
            return $this->error('配置ID为空');
        }
        
        // 删除菜单
        ConfigLogic::delConfig($config_id);
        return $this->success('删除配置成功', Url::build('config/index'));
    }

    /**
     * 更改配置
     */
    public function modifyConfig(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            return $this->error('ID为空');
        }
        
        $field_arr = [
            'config_group',
            'config_sort'
        ];
        $field = $request->param('field');
        if (! in_array($field, $field_arr)) {
            return $this->error('非法的字段');
        }
        
        $value = $request->param('value', '');
        ConfigLogic::modifyConfig($id, $field, $value);
        
        return $this->success('更改成功', Url::build('config/index'));
    }

    /**
     * 网站设置
     */
    public function setting()
    {
        $this->site_title = '网站设置';
        
        $list = ConfigLogic::getGroupList();
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 保存设置
     */
    public function saveConfig(Request $request)
    {
        $config = $request->param('config/a', []);
        
        foreach ($config as $co => $vo) {
            $map = [
                'config_name' => $co
            ];
            $data = [
                'config_value' => is_array($vo) ? json_encode($vo, JSON_UNESCAPED_UNICODE) : $vo
            ];
            ConfigLogic::saveConfig($data, $map);
        }
        
        return $this->success('保存设置成功', 'history.go(0);');
    }
}