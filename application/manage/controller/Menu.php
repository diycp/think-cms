<?php
namespace app\manage\controller;

use think\Url;
use think\Request;
use app\common\validate\manage\MenuValidate;
use app\common\model\manage\MenuModel;
use app\common\logic\manage\MenuLogic;

class Menu extends Base
{

    /**
     * 菜单列表
     */
    public function index(Request $request)
    {
        $this->site_title = '菜单管理';
        
        // menu_pid
        $menu_pid = $request->param('menu_pid', 0);
        $this->assign('menu_pid', $menu_pid);
        
        $map = [
            'menu_pid' => $menu_pid
        ];
        
        // group_list
        $group_list = MenuModel::field('id, menu_group')->where($map)
        ->group('menu_group')
        ->order('menu_sort asc')
        ->select();
        $this->assign('group_list', $group_list);
        
        // group_name
        $group_name = $request->param('group', '');
        if (! empty($group_name)) {
            $map['menu_group'] = $group_name;
        }
        $this->assign('group', $group_name);
        
        // list
        $list = MenuModel::where($map)->order('menu_sort asc')->select();
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 添加菜单
     */
    public function addMenu(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'menu_name' => $request->param('menu_name'),
                'menu_url' => $request->param('menu_url'),
                'menu_pid' => $request->param('menu_pid', 0),
                'menu_group' => $request->param('menu_group', ''),
                'menu_sort' => $request->param('menu_sort', 0),
                'menu_status' => $request->param('menu_status', 0)
            ];
            
            // 验证数据
            $validate = new MenuValidate();
            $res = $validate->scene('add')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MenuLogic::addMenu($data);
            return $this->success('添加菜单成功', Url::build('menu/index', [
                'menu_pid' => $request->param('menu_pid')
            ]));
        } else {
            $this->site_title = '新增菜单';
            
            $menu_pid = $request->param('menu_pid', 0);
            $this->assign('menu_pid', intval($menu_pid));
            
            $menu_select = MenuLogic::getMenuSelect();
            $this->assign('menu_select', $menu_select);
            
            $menu_status = $this->getStatus();
            $this->assign('menu_status', $menu_status);
            
            return $this->fetch();
        }
    }

    /**
     * 编辑菜单
     */
    public function editMenu(Request $request)
    {
        $menu_id = $request->param('menu_id');
        if (empty($menu_id)) {
            return $this->error('菜单ID为空');
        }
        
        if ($request->isPost()) {
            $data = [
                'menu_name' => $request->param('menu_name'),
                'menu_url' => $request->param('menu_url'),
                'menu_pid' => $request->param('menu_pid', 0),
                'menu_group' => $request->param('menu_group', ''),
                'menu_sort' => $request->param('menu_sort', 0),
                'menu_status' => $request->param('menu_status', 0)
            ];
            
            // 验证数据
            $validate = new MenuValidate();
            $res = $validate->scene('edit')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MenuLogic::saveMenu($data, $menu_id);
            return $this->success('修改菜单成功', Url::build('menu/index', [
                'menu_pid' => $request->param('menu_pid')
            ]));
        } else {
            $this->site_title = '编辑菜单';
            $this->assign('menu_id', $menu_id);
            
            $menu = MenuModel::get($menu_id);
            $this->assign('menu', $menu);
            
            $menu_select = MenuLogic::getMenuSelect();
            $this->assign('menu_select', $menu_select);
            
            $menu_status = $this->getStatus();
            $this->assign('menu_status', $menu_status);
            
            return $this->fetch();
        }
    }

    /**
     * 删除菜单
     */
    public function delMenu(Request $request)
    {
        $menu_id = $request->param('menu_id');
        if (empty($menu_id)) {
            return $this->error('菜单ID为空');
        }
        
        // 检查子菜单
        $map = [
            'menu_pid' => $menu_id
        ];
        $menu = MenuModel::where($map)->find();
        if (! empty($menu)) {
            return $this->error('请先删除该菜单下的子菜单');
        }
        
        // 删除菜单
        MenuModel::del($menu_id);
        return $this->success('删除菜单成功', 'history.go(0);');
    }

    /**
     * 更改菜单
     */
    public function modifyMenu(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            return $this->error('ID为空');
        }
        
        $field_arr = [
            'menu_group',
            'menu_sort',
            'menu_status'
        ];
        $field = $request->param('field');
        if (! in_array($field, $field_arr)) {
            return $this->error('非法的字段');
        }
        
        $value = $request->param('value', '');
        MenuModel::modify($id, $field, $value);
        
        return $this->success('更改成功', 'history.go(0);');
    }

    /**
     * 菜单状态
     */
    protected function getStatus()
    {
        return [
            [
                'name' => '显示',
                'value' => 1
            ],
            [
                'name' => '隐藏',
                'value' => 0
            ]
        ];
    }
}
