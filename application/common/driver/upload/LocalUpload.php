<?php
namespace app\common\driver\upload;

use UpYun;
use think\Config;
use newday\common\Format;
use app\common\logic\common\CommonLogic;

class LocalUpload extends BaseUpload
{

    /**
     * 上传文件
     */
    public static function uploadFile($path, $target_path)
    {
        try {
            
            // 上传配置
            $upload_config = self::getUploadConfig();
            $save_path = $upload_config['local_root'] . $target_path;
            
            // 文件夹检查
            $save_dir = dirname($save_path);
            if (! is_dir($save_dir)) {
                mkdir($save_dir, 0777, true);
            }
            
            // 移动文件
            rename($path, $save_path);
            
            // 拼接链接
            $url = $upload_config['local_url'] . $target_path;
            return Format::formatResult(1, '上传成功', $url);
        } catch (\Exception $e) {
            return Format::formatResult(0, '上传文件发生意外:' . $e->getMessage());
        }
    }

    /**
     * 获取文件
     */
    public static function fetchFile($target_url)
    {
        try {
            $upload_config = self::getUploadConfig();
            
            $target_path = str_replace($upload_config['local_url'], '', $target_url);
            $save_path = $upload_config['local_root'] . $target_path;
            
            $content = CommonLogic::readFile($save_path);
            return Format::formatJump(1, '读取文件成功', $content);
        } catch (\Exception $e) {
            return Format::formatJump(0, '读取文件发生意外');
        }
    }

    /**
     * 删除文件
     */
    public static function deleteFile($target_url)
    {
        try {
            $upload_config = self::getUploadConfig();
            
            $target_path = str_replace($upload_config['local_url'], '', $target_url);
            $save_path = $upload_config['local_root'] . $target_path;
            unlink($save_path);
            
            return Format::formatJump(1, '删除文件成功');
        } catch (\Exception $e) {
            return Format::formatJump(0, '删除文件发生意外');
        }
    }

    /**
     * 上传配置
     */
    public static function getUploadConfig()
    {
        static $upload_config;
        if (empty($upload_config)) {
            $upload_config = Config::get('local_config');
        }
        return $upload_config;
    }
}