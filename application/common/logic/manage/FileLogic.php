<?php
namespace app\common\logic\manage;

use newday\common\Format;
use app\common\logic\BaseLogic;
use app\common\model\manage\FileModel;
use app\common\logic\common\UploadLogic;

class FileLogic extends BaseLogic
{

    /**
     * 获取文件
     */
    public static function getFile($hash)
    {
        $map = array(
            'file_hash' => $hash
        );
        $file = FileModel::field('file_url')->where($map)->find();
        return $file ? $file['file_url'] : null;
    }

    /**
     * 保存文件
     */
    public static function addFile($hash, $url, $type = '', $size = 0)
    {
        $data = array(
            'file_hash' => $hash,
            'file_type' => $type,
            'file_size' => $size,
            'file_url' => $url,
            'create_time' => time()
        );
        return FileModel::insert($data);
    }

    /**
     * 删除文件
     */
    public static function delFile($file_id)
    {
        $file = FileModel::get($file_id);
        if (empty($file)) {
            return Format::formatJump(0, '文件不存在');
        }
        
        FileModel::del($file_id);
        
        $upload_driver = UploadLogic::getUploadDriver();
        return $upload_driver::deleteFile($file['file_url']);
    }
}