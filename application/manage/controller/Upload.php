<?php
namespace app\manage\controller;

use think\Request;
use newday\common\Format;
use app\common\logic\common\UploadLogic;
use app\common\driver\upload\UpyunUpload;

class Upload extends Base
{

    /**
     * 上传参数
     */
    public function getSign(Request $request)
    {
        $data = input('post.data/a');
        $res = UpyunUpload::getSign($data);
        responseReturn($res);
    }

    /**
     * 上传通知
     */
    public function uploadNotify(Request $request)
    {
        UpyunUpload::uploadNotify($request->param());
    }

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
}