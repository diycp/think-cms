<?php
namespace app\common\driver\login;

use think\Cookie;
use think\Cache;
use think\helper\Str;

class CookieLogin extends BaseLogin
{

    public static $cookie_prefix = 'cookie_login_';

    public static $cache_prefix = 'cache_login_';

    /**
     * 存储登录
     */
    public static function storageLogin($key, $data)
    {
        $ticket = md5(Str::random() . serialize($data));
        
        Cookie::set(static::$cookie_prefix . $key, $ticket);
        
        Cache::set($ticket, $data);
    }

    /**
     * 获取登录
     */
    public static function readLogin($key)
    {
        $ticket = Cookie::get(static::$cookie_prefix . $key);
        if ($ticket) {
            return Cache::get($ticket);
        }
        return null;
    }

    /**
     * 清除登录
     */
    public static function clearLogin($key)
    {
        Cookie::delete(static::$cookie_prefix . $key);
        
        $ticket = Cookie::get(static::$cookie_prefix . $key);
        if ($ticket) {
            return Cache::rm($ticket);
        }
    }
}