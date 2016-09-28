<?php
namespace app\manage\controller;

use think\Request;
use newday\common\Format;
use app\common\logic\common\UploadLogic;

class Upload extends Base
{

    /**
     * 上传文件
     */
    public function upload(Request $request)
    {
        $type = $request->param('upload_type', '');
        
        // 上传文件
        $upload_file = isset($_FILES['upload_file']) ? $_FILES['upload_file'] : null;
        if (empty($upload_file)) {
            responseReturn(Format::formatResult(0, '上传文件不存在'));
        }
        
        // 额外配置
        $upload_option = $request->param('upload_option', '');
        if (! empty($upload_option)) {
            $upload_option = json_decode($upload_option, true);
        } else {
            $upload_option = [];
        }
        
        $res = UploadLogic::uploadByFile($upload_file, $type, $upload_option);
        responseReturn($res);
    }

    /**
     * wangEditor
     */
    public function wang(Request $request)
    {
        // 上传文件
        $upload_file = isset($_FILES['upload_file']) ? $_FILES['upload_file'] : null;
        if (empty($upload_file)) {
            return 'error|上传文件不存在';
        }
        
        // 额外配置
        $upload_option = [
            'width' => 1920,
            'height' => 1080
        ];
        
        $res = UploadLogic::uploadByFile($upload_file, '', $upload_option);
        if ($res['code'] == 1) {
            return $res['data']['url'];
        } else {
            return 'error|' . $res['msg'];
        }
    }
}