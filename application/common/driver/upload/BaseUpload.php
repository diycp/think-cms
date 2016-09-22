<?php
namespace app\common\driver\upload;

interface UploadInterface
{

    /**
     * 上传文件
     */
    public static function uploadFile($path, $target_path);

    /**
     * 获取文件
     */
    public static function fetchFile($target_url);

    /**
     * 删除文件
     */
    public static function deleteFile($target_url);
}

abstract class BaseUpload implements UploadInterface
{
}