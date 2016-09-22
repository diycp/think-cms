<?php
namespace app\manage\controller;

use think\Url;
use think\Request;
use app\common\validate\manage\MemberValidate;
use app\common\model\manage\MemberModel;
use app\common\model\manage\MemberGroupModel;
use app\common\logic\manage\MemberLogic;

class Member extends Base
{

    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        $this->site_title = '用户管理';
        
        $member_group = MemberGroupModel::select();
        $this->assign('member_group', $member_group);
        
        $list = MemberModel::select();
        $this->assign('list', $list);
        
        return $this->fetch();
    }

    /**
     * 添加用户
     */
    public function addMember(Request $request)
    {
        if ($request->isPost()) {
            $data = [
                'user_name' => $request->param('user_name'),
                'user_nick' => $request->param('user_nick'),
                'user_passwd' => $request->param('user_passwd'),
                're_passwd' => $request->param('re_passwd'),
                'group_id' => $request->param('group_id'),
                'user_status' => $request->param('user_status', 0)
            ];
            
            // 验证数据
            $validate = new MemberValidate();
            $res = $validate->scene('add')->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MemberLogic::addMember($data);
            return $this->success('添加用户成功', Url::build('member/index'));
        } else {
            $this->site_title = '新增用户';
            
            $member_group = MemberGroupModel::select();
            $this->assign('member_group', $member_group);
            
            return $this->fetch();
        }
    }

    /**
     * 编辑用户
     */
    public function editMember(Request $request)
    {
        $user_id = $request->param('user_id');
        if (empty($user_id)) {
            return $this->error('用户ID为空');
        }
        
        if ($request->isPost()) {
            $data = [
                'user_name' => $request->param('user_name'),
                'user_nick' => $request->param('user_nick'),
                'user_passwd' => $request->param('user_passwd'),
                're_passwd' => $request->param('re_passwd'),
                'group_id' => $request->param('group_id'),
                'user_status' => $request->param('user_status', 0)
            ];
            
            // 验证数据
            $validate = new MemberValidate();
            $scene = empty($data['user_passwd']) ? 'edit_info' : 'edit_passwd';
            $res = $validate->scene($scene)->check($data);
            if (! $res) {
                return $this->error($validate->getError());
            }
            
            MemberLogic::saveMember($data, $user_id);
            return $this->success('修改用户成功', Url::build('member/index'));
        } else {
            $this->site_title = '编辑菜单';
            $this->assign('user_id', $user_id);
            
            $member = MemberModel::get($user_id);
            $this->assign('member', $member);
            
            $member_group = MemberGroupModel::select();
            $this->assign('member_group', $member_group);
            
            return $this->fetch();
        }
    }

    /**
     * 删除用户
     */
    public function delMember(Request $request)
    {
        $user_id = $request->param('user_id');
        if (empty($user_id)) {
            return $this->error('用户ID为空');
        }
        
        // 超级用户
        if (MemberLogic::isAdmin($user_id)) {
            return $this->error('超级用户不能删除');
        }
        
        // 删除用户
        MemberModel::del($user_id);
        return $this->success('删除用户成功');
    }

    /**
     * 更改用户
     */
    public function modifyMember(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            return $this->error('ID为空');
        }
        
        $field_arr = [
            'group_id',
            'user_status'
        ];
        $field = $request->param('field');
        if (! in_array($field, $field_arr)) {
            return $this->error('非法的字段');
        }
        
        $value = $request->param('value', '');
        MemberModel::modify($id, $field, $value);
        
        return $this->success('更改成功', Url::build('member/index'));
    }
}
