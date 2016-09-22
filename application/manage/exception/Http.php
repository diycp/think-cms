<?php
namespace app\manage\exception;

use think\Request;
use think\View;
use think\Response;
use newday\common\Format;
use think\exception\Handle;
use app\manage\logic\ViewLogic;

class Http extends Handle
{

    public function render(\Exception $e)
    {
        // 父类处理
        parent::render($e);
        
        $request = Request::instance();
        $msg = '程序发生意外,' . $e->getCode() . ' : ' . $e->getMessage();
        
        if ($request->isAjax()) {
            $data = Format::formatJump(0, $msg);
            $response = Response::create($data, 'json');
        } else {
            
            $view = new View();
            
            // 标题
            $view->assign('site_title', $msg);
            
            // 跳转
            $jump = Format::formatJump(0, $msg);
            $view->assign('jump', $jump);
            
            // 处理view
            $view = ViewLogic::dealView($view);
            
            // 渲染
            $data = $view->fetch(APP_PATH . 'manage/view/common/jump.html');
            $data = ViewLogic::dealContent($data);
            
            $response = Response::create($data);
        }
        
        return $response;
    }
}