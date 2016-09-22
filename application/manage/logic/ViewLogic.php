<?php
namespace app\manage\logic;

use think\Config;
use think\Request;

class ViewLogic
{

    /**
     * 处理View
     */
    public static function dealView($view)
    {
        $request = Request::instance();
        $base_url = Config::get('site_base');
        
        // 版本
        $view->assign('site_version', Config::get('site_version'));
        
        // 前端库路径
        $lib_path = $base_url . 'lib';
        $view->assign('lib_path', $lib_path);
        
        // 静态库路径
        $static_path = $base_url . 'static';
        $view->assign('static_path', $static_path);
        
        // 当前模块路径
        $module_path = $static_path . '/' . $request->module();
        $view->assign('module_path', $module_path);
        
        return $view;
    }

    /**
     * 处理content
     */
    public static function dealContent($content)
    {
        if (Config::get('site_compress')) {
            $content = self::compressHtml($content);
        }
        return $content;
    }

    /**
     * 压缩html
     */
    public static function compressHtml($string)
    {
        return str_replace([
            "\n",
            "\r",
            "\t",
            '  '
        ], [
            '',
            '',
            '',
            ' '
        ], $string);
    }
}