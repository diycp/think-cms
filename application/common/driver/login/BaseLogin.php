<?php
namespace app\common\driver\login;

interface LoginInterface
{

    /**
     * 存储登录
     */
    public static function storageLogin($key, $data);

    /**
     * 获取登录
     */
    public static function readLogin($key);

    /**
     * 清除登录
     */
    public static function clearLogin($key);
}

abstract class BaseLogin implements LoginInterface
{
}