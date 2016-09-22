<?php
namespace app\manage\logic;

use think\Config;
use newday\common\Format;
use app\common\model\manage\MemberModel;
use app\common\logic\manage\MemberLogic;
use app\common\logic\common\CommonLogic;

class LoginLogic
{

    const LOGIN_KEY = 'manage';

    /**
     * 管理首页
     */
    public static function getManageUrl($user_id)
    {
        $group = MemberLogic::getUserGroup($user_id);
        return $group['home_page'] ? $group['home_page'] : 'index/index';
    }

    /**
     * 用户登录
     */
    public static function doLogin($data)
    {
        $map = [
            'user_name' => $data['user_name'],
            'user_passwd' => MemberLogic::encryptPasswd($data['user_passwd'])
        ];
        $user = MemberModel::where($map)->find();
        
        if (empty($user)) {
            // 未找到用户
            return Format::formatResult(- 1, '账号或者密码错误');
        } elseif ($user['user_status'] == 0) {
            // 用户未启用
            return Format::formatResult(- 2, '未启用的账号');
        } elseif ($user['user_status'] == - 1) {
            // 用户被禁用
            return Format::formatResult(- 3, '该账号已经被禁用');
        }
        
        $group = MemberLogic::getUserGroup($user['id']);
        if (empty($group)) {
            // 用户群组不存在
            return Format::formatResult(- 4, '尚未分配用户群组');
        } elseif ($group['group_status'] == 0) {
            // 用户群组被禁用
            return Format::formatResult(- 5, '用户所在群组被禁止登录');
        }
        
        // 登录账号
        self::loginUser($user);
        
        return Format::formatResult(1, '登录成功', [
            'user' => $user
        ]);
    }

    /**
     * 注销登录
     */
    public static function loginOut()
    {
        $login_driver = self::getLoginDriver();
        $login_driver::clearLogin(self::LOGIN_KEY);
    }

    /**
     * 用户登录
     */
    public static function loginUser($user)
    {
        $data = [
            'login_count' => [
                'exp',
                'login_count + 1'
            ],
            'login_time' => time(),
            'login_ip' => CommonLogic::getIp()
        ];
        MemberLogic::saveUser($user['id'], $data);
        
        $login_driver = self::getLoginDriver();
        $data = [
            'user_id' => $user['id']
        ];
        $login_driver::storageLogin(self::LOGIN_KEY, $data);
    }

    /**
     * 获取登录用户
     */
    public static function getLoginUser()
    {
        $login_driver = self::getLoginDriver();
        return $login_driver::readLogin(self::LOGIN_KEY);
    }

    /**
     * 获取登录用户信息
     */
    public static function getLoginUserInfo()
    {
        $user = self::getLoginUser();
        if (empty($user)) {
            return null;
        }
        
        return MemberLogic::getUser($user['user_id']);
    }

    /**
     * 登录驱动
     */
    public static function getLoginDriver()
    {
        $login_driver = Config::get('login_driver_manage');
        if ($login_driver && class_exists($login_driver)) {
            return $login_driver;
        } else {
            return '\\app\\common\\driver\\login\\SessionLogin';
        }
    }
}