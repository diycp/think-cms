<?php
namespace app\common\logic\common;

class CaptchaVerifyLogic
{

    const VERIFY_PREFIX = 'verify_captcha_';

    /**
     * 校验code
     */
    public static function checkCode($code, $id, $config = array())
    {
        $key = self::getKey($id);
        
        return captcha_check($code, $key, $config);
    }

    /**
     * 获取Image
     */
    public static function getCodeImage($id, $config = array())
    {
        $key = self::getKey($id);
        
        return captcha($key, $config);
    }

    /**
     * 获取Src
     */
    public static function getCodeSrc($id)
    {
        $key = self::getKey($id);
        
        return captcha_src($key);
    }

    /**
     * 获取key
     */
    public static function getKey($id)
    {
        if (empty($id)) {
            $id = 'common';
        }
        return self::VERIFY_PREFIX . $id;
    }
}