<?php
namespace app\common\logic\manage;

use app\common\logic\BaseLogic;
use app\common\model\manage\MemberModel;
use app\common\model\manage\MemberGroupModel;

class MemberLogic extends BaseLogic
{

    protected static $users = [];

    /**
     * 超级用户
     */
    public static function isAdmin($user_id)
    {
        return $user_id == 1;
    }

    /**
     * 用户权限
     */
    public static function getAuthMenu($user_id)
    {
        $user = self::getUser($user_id);
        return MemberGroupLogic::getGroupMenu($user['group_id']);
    }

    /**
     * 获取用户分组
     */
    public static function getUserGroup($user_id)
    {
        $user = self::getUser($user_id);
        return MemberGroupModel::get($user['group_id']);
    }

    /**
     * 添加用户
     */
    public static function addMember($data)
    {
        $data['user_passwd'] = self::encryptPasswd($data['user_passwd']);
        unset($data['re_passwd']);
        
        return MemberModel::add($data);
    }

    /**
     * 修改用户
     */
    public static function saveMember($data, $user_id)
    {
        if (empty($data['user_passwd'])) {
            unset($data['user_passwd']);
        } else {
            $data['user_passwd'] = self::encryptPasswd($data['user_passwd']);
        }
        unset($data['re_passwd']);
        
        return self::saveUser($user_id, $data);
    }

    /**
     * 获取用户
     */
    public static function getUser($user_id)
    {
        if (empty(self::$users[$user_id])) {
            $map = [
                'id' => $user_id
            ];
            self::$users[$user_id] = MemberModel::where($map)->find();
        }
        return self::$users[$user_id];
    }

    /**
     * 保存用户
     */
    public static function saveUser($user_id, $data)
    {
        // 清除缓存
        unset(self::$users[$user_id]);
        
        $map = [
            'id' => $user_id
        ];
        return MemberModel::save($data, $map);
    }

    /**
     * 加密密码
     */
    public static function encryptPasswd($passwd)
    {
        $passwd = sha1($passwd);
        return md5(gzcompress($passwd) . base64_decode($passwd));
    }
}