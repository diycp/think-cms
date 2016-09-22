<?php
namespace app\common\logic\common;

use think\Image;
use think\Config;
use newday\file\FileInfo;
use newday\common\Format;
use app\common\logic\manage\FileLogic;
use app\common\logic\common\CommonLogic;

class UploadLogic
{

    /**
     * 根据FILE上传
     */
    public static function uploadByFile($file, $type = '', $option = array())
    {
        if ($file['error'] > 0) {
            switch ($file['error']) {
                case 1:
                    $info = '文件超过服务器允许上传的大小';
                    break;
                case 2:
                    $info = '文件超过表单允许上传的大小';
                    break;
                case 3:
                    $info = '文件只有部分被上传';
                    break;
                case 4:
                    $info = '没有找到要上传的文件';
                    break;
                case 5:
                    $info = '服务器临时文件夹丢失';
                    break;
                case 6:
                    $info = '没有找到临时文件夹';
                    break;
                case 7:
                    $info = '写入临时文件失败';
                    break;
                case 8:
                    $info = 'PHP不允许上传文件';
                    break;
                default:
                    $info = '未知的上传错误';
                    break;
            }
            return Format::formatResult(0, $info);
        } else {
            $option['mime'] = $file['type'];
            return self::uploadByPath($file['tmp_name'], $type, $option);
        }
    }

    /**
     * 根据路径上传
     */
    public static function uploadByPath($path, $type = '', $option = array())
    {
        // 文件是否存在
        if (empty($path) || ! is_file($path)) {
            return Format::formatResult(0, '文件不存在');
        }
        
        // 没有指定后缀
        if (! isset($option['ext'])) {
            $option['ext'] = FileInfo::getFileExt($path);
        }
        
        // 类型识别出错
        if (empty($option['ext']) && isset($option['mime'])) {
            $option['ext'] = FileInfo::mimeToExt($option['mime']);
        }
        
        // 检验文件类型
        $res = self::checkFileExt($option['ext']);
        if ($res['code'] == 0) {
            return $res;
        } else {
            $type = $res['data'];
        }
        
        // 处理图片
        if ($type == 'image') {
            self::processImage($path, $option);
        }
        
        // 没有文件Md5
        if (! isset($option['md5'])) {
            $option['md5'] = md5_file($path);
        }
        
        // 文件是否上传
        $url = FileLogic::getFile($option['md5']);
        if (! empty($url)) {
            return Format::formatResult(1, '上传成功', [
                'url' => $url,
                'md5' => $option['md5']
            ]);
        }
        
        // 上传文件
        $file_size = filesize($path);
        $target_path = $type . '/' . $option['md5'] . '.' . $option['ext'];
        $upload_driver = self::getUploadDriver();
        $res = $upload_driver::uploadFile($path, $target_path);
        
        // 保存上传记录
        if ($res['code'] == 1) {
            FileLogic::addFile($option['md5'], $res['data'], $type, $file_size);
        }
        
        return Format::formatResult(1, '上传成功', [
            'url' => $res['data'],
            'md5' => $option['md5']
        ]);
    }

    /**
     * 图片特殊处理
     */
    public static function processImage($path, $option = array())
    {
        try {
            // 图片重力
            if (extension_loaded('exif')) {
                $image = imagecreatefromstring(CommonLogic::readFile($path));
                $exif = exif_read_data($path);
                if (! empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 8:
                            $image = imagerotate($image, 90, 0);
                            break;
                        case 3:
                            $image = imagerotate($image, 180, 0);
                            break;
                        case 6:
                            $image = imagerotate($image, - 90, 0);
                            break;
                    }
                    imagejpeg($image, $path);
                }
            }
            
            // 图片大小
            if (extension_loaded('gd')) {
                if (isset($option['width']) || isset($option['height'])) {
                    $image = Image::open($path);
                    if (empty($image)) {
                        return Format::formatResult(0, '不是有效的图片');
                    }
                    
                    $width = isset($option['width']) && ! empty($option['width']) ? $option['width'] : $image->width();
                    $height = isset($option['height']) && ! empty($option['height']) ? $option['height'] : $image->height();
                    $image = $image->thumb($width, $height);
                    $image->save($path);
                }
            }
        } catch (\Exception $e) {
            return Format::formatResult(0, '处理图片发生意外:' . $e->getMessage());
        }
    }

    /**
     * 检验文件类型
     */
    public static function checkFileExt($ext, $type = '')
    {
        // 可上传类型
        $upload_type = Config::get('upload_type');
        
        // 没有指定上传类型
        if ($type == '') {
            foreach ($upload_type as $c => $v) {
                if (in_array($ext, $v)) {
                    $type = $c;
                    break;
                }
            }
        }
        
        // 验证上传类型
        if ($type == '' || ! isset($upload_type[$type]) || ! in_array($ext, $upload_type[$type])) {
            return Format::formatResult(0, '不合法的文件类型');
        } else {
            return Format::formatResult(1, '合法的文件类型', $type);
        }
    }

    /**
     * 上传驱动
     */
    public static function getUploadDriver()
    {
        $login_driver = Config::get('upload_driver');
        if ($login_driver && class_exists($login_driver)) {
            return $login_driver;
        } else {
            return '\\app\\common\\driver\\upload\\LocalUpload';
        }
    }
}