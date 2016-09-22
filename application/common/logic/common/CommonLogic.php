<?php
namespace app\common\logic\common;

use think\Request;

class CommonLogic
{

    /**
     * 获取ip
     */
    public static function getIp()
    {
        return Request::instance()->server('REMOTE_ADDR');
    }

    /**
     * 当前action
     */
    public static function getCurrentAction()
    {
        $request = Request::instance();
        return $request->module() . '/' . $request->controller() . '/' . $request->action();
    }

    /**
     * 读取文件内容
     */
    public static function readFile($file_path)
    {
        try {
            $file = fopen($file_path, 'r');
            $content = fread($file, filesize($file_path));
            fclose($file);
            return $content;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 格式化字节大小
     */
    public static function formatBytes($size, $delimiter = '')
    {
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB'
        ];
        for ($i = 0; $size >= 1024 && $i < 5; $i ++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

    /**
     * 当前path
     */
    public static function getWebPath()
    {
        static $path;
        if (empty($path)) {
            
            // 当前文件路径
            $file = Request::instance()->server('SCRIPT_NAME');
            
            // 去除文件名
            $arr = explode('/', $file);
            array_pop($arr);
            
            // 当前文件夹路径
            if (count($arr) == 0) {
                $path = '/';
            } else {
                $path = implode('/', $arr) . '/';
            }
        }
        return $path;
    }
}