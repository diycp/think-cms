<?php
namespace app\common\driver\login;

use think\Session;

class SessionLogin extends BaseLogin
{

    public static $prefix = 'session_login_';

    /**
     * 存储登录
     */
    public static function storageLogin($key, $data)
    {
        Session::set(static::$prefix . $key, $data);
    }

    /**
     * 获取登录
     */
    public static function readLogin($key)
    {
        return Session::get(static::$prefix . $key);
    }

    /**
     * 清除登录
     */
    public static function clearLogin($key)
    {
        Session::delete(static::$prefix . $key);
    }
}