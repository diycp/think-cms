<?php
namespace app\manage\controller;

use think\Request;
use think\Url;
use app\common\validate\manage\MemberGroupValidate;
use app\common\model\manage\MemberModel;
use app\common\model\manage\MemberGroupModel;
use app\common\logic\manage\MenuLogic;
use app\common\logic\manage\MemberGroupLogic;

class MemberGroup extends Base
{

    /**
     * 群组列表
     */
    public function index(Request $request)
    {
        $this->site_title = '用户群组';
        
        $list = MemberGroupModel::select();
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 添加群组
     */
    public function addGroup(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'home_page' => $request->param('home_page', ''),
                'group_status' => $request->param('group_status', 0)
            ];
            
            // 验证数据
            $validate = new MemberGroupValidate();
            $res = $validate->scene('add')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MemberGroupModel::add($data);
            return $this->success('添加群组成功', Url::build('memberGroup/index'));
        } else {
            $this->site_title = '新增群组';
            
            $group_status = [
                [
                    'name' => '启用',
                    'value' => 1
                ],
                [
                    'name' => '禁用',
                    'value' => 0
                ]
            ];
            $this->assign('group_status', $group_status);
            
            return $this->fetch();
        }
    }

    /**
     * 编辑群组
     */
    public function editGroup(Request $request)
    {
        $group_id = $request->param('group_id');
        if (empty($group_id)) {
            return $this->error('群组ID为空');
        }
        
        if ($request->isPost()) {
            $data = [
                'group_name' => $request->param('group_name'),
                'group_info' => $request->param('group_info', ''),
                'home_page' => $request->param('home_page', ''),
                'group_status' => $request->param('group_status', 0)
            ];
            
            // 验证数据
            $validate = new MemberGroupValidate();
            $res = $validate->scene('edit')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MemberGroupModel::save($data, $group_id);
            return $this->success('修改群组成功', Url::build('memberGroup/index'));
        } else {
            
            $this->site_title = '编辑群组';
            $this->assign('group_id', $group_id);
            
            $group = MemberGroupModel::get($group_id);
            $this->assign('group', $group);
            
            $group_status = [
                [
                    'name' => '启用',
                    'value' => 1
                ],
                [
                    'name' => '禁用',
                    'value' => 0
                ]
            ];
            $this->assign('group_status', $group_status);
            
            return $this->fetch();
        }
    }

    /**
     * 编辑权限
     */
    public function editAuth(Request $request)
    {
        $group_id = $request->param('group_id');
        if (empty($group_id)) {
            return $this->error('群组ID为空');
        }
        
        if ($request->isPost()) {
            
            $group_menus = $request->param('group_menus/a');
            if (empty($group_menus) || count($group_menus) == 0) {
                return $this->error('权限菜单为空');
            }
            
            $data = [
                'group_menus' => implode(',', $group_menus)
            ];
            MemberGroupModel::save($data, $group_id);
            return $this->success('保存权限成功', Url::build('memberGroup/index'));
        } else {
            
            $this->site_title = '编辑权限';
            $this->assign('group_id', $group_id);
            
            $group_menus = MemberGroupLogic::getGroupMenu($group_id);
            $this->assign('group_menus', $group_menus);
            
            $menu_tree = MenuLogic::getMenuTree();
            $this->assign('menu_tree', $menu_tree);
            
            return $this->fetch();
        }
    }

    /**
     * 删除群组
     */
    public function delGroup(Request $request)
    {
        $group_id = $request->param('group_id');
        if (empty($group_id)) {
            return $this->error('群组ID为空');
        }
        
        // 删除菜单
        MemberGroupModel::del($group_id);
        return $this->success('删除群组成功', 'history.go(0);');
    }

    /**
     * 更改群组
     */
    public function modifyGroup(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            return $this->error('ID为空');
        }
        
        $field_arr = [
            'group_status'
        ];
        $field = $request->param('field');
        if (! in_array($field, $field_arr)) {
            return $this->error('非法的字段');
        }
        
        $value = $request->param('value', '');
        MemberGroupModel::modify($id, $field, $value);
        
        return $this->success('更改成功', 'history.go(0);');
    }
}
