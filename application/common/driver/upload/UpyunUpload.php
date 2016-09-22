<?php
namespace app\common\driver\upload;

use UpYun;
use think\Config;
use Crocodile\File;
use Crocodile\Upload;
use Crocodile\Signature;
use newday\common\Format;

class UpyunUpload extends BaseUpload
{

    /**
     * 上传文件
     */
    public static function uploadFile($path, $target_path)
    {
        // 允许客户端断开连接
        ignore_user_abort(true);
        
        try {
            
            // 上传配置
            $upload_config = self::getUploadConfig();
            
            // 文件大于设定值进行分片上传
            $file_size = filesize($path);
            $save_path = $upload_config['upyun_root'] . $target_path;
            if ($file_size > $upload_config['upyun_maxsize'] * 1048576) {
                $upyun = self::getMultiUpYun();
                $upload_option = array(
                    'path' => $save_path,
                    'return_url' => $upload_config['upyun_return'],
                    'notify_url' => $upload_config['upyun_notify']
                );
                $file = new File($path);
                $rsp = $upyun->upload($file, $upload_option);
            } else {
                $upyun = self::getUpYun();
                $fh = fopen($path, 'rb');
                $rsp = $upyun->writeFile($save_path, $fh, true);
                fclose($fh);
            }
            
            // 拼接链接
            $url = $upload_config['upyun_url'] . $target_path;
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
            $upyun = self::getUpYun();
            $upload_config = self::getUploadConfig();
            
            $target_path = str_replace($upload_config['upyun_url'], '', $target_url);
            $save_path = $upload_config['upyun_root'] . $target_path;
            $content = $upyun->readFile($save_path);
            
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
            $upyun = self::getUpYun();
            $upload_config = self::getUploadConfig();
            
            $target_path = str_replace($upload_config['upyun_url'], '', $target_url);
            $save_path = $upload_config['upyun_root'] . $target_path;
            $upyun->delete($save_path);
            
            return Format::formatJump(1, '删除文件成功');
        } catch (\Exception $e) {
            return Format::formatJump(0, '删除文件发生意外');
        }
    }

    /**
     * 普通上传对象
     */
    public static function getUpYun()
    {
        static $upyun;
        if (empty($upyun)) {
            $upload_config = self::getUploadConfig();
            $upyun = new UpYun($upload_config['upyun_bucket'], $upload_config['upyun_user'], $upload_config['upyun_pass']);
        }
        return $upyun;
    }

    /**
     * 分片上传对象
     */
    public static function getMultiUpYun()
    {
        static $upyun;
        if (empty($upyun)) {
            $upload_config = self::getUploadConfig();
            
            $sign = new Signature($upload_config['upyun_key']);
            $upyun = new Upload($sign);
            $upyun->setBucketName($upload_config['upyun_bucket']);
            
            // 分片大小
            $upyun->setBlockSize($upload_config['upyun_size'] * 1048576);
        }
        return $upyun;
    }

    /**
     * 上传配置
     */
    public static function getUploadConfig()
    {
        static $upload_config;
        if (empty($upload_config)) {
            $upload_config = Config::get('upyun_config');
        }
        return $upload_config;
    }
}