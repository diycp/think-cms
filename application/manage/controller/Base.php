<?php
namespace app\manage\controller;

use think\Config;
use think\Request;
use newday\common\Format;
use app\common\logic\manage\MenuLogic;
use app\common\logic\manage\MemberLogic;
use app\manage\logic\AuthLogic;
use app\manage\logic\LoginLogic;
use app\manage\logic\ViewLogic;

class Base
{
    use \newday\traits\Get;

    protected $site_title = '';

    protected $user_id = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_initialize();
    }

    /**
     * 初始化
     */
    protected function _initialize()
    {
        $request = Request::instance();
        $public_action = Config::get('public_action_manage');
        if (! AuthLogic::isPublicAction($public_action)) {
            // 验证登录
            $this->verifyLogin();
            
            // 验证权限
            if (! AuthLogic::isAuthAction($this->user_id)) {
                $data = $this->error('你没有权限访问该页面');
                if ($request->isAjax()) {
                    responseReturn($data, 'json');
                } else {
                    responseReturn($data, 'text');
                }
            }
            
            // 创建菜单
            $request->isAjax() || $this->buildMenu();
        }
    }

    /**
     * 验证登录
     */
    protected function verifyLogin()
    {
        $user = LoginLogic::getLoginUser();
        if (empty($user)) {
            responseRedirect('start/login');
        } else {
            $this->user_id = $user['user_id'];
            
            $site_user = MemberLogic::getUser($user['user_id']);
            $this->assign('site_user', $site_user);
        }
    }

    /**
     * 创建菜单
     */
    protected function buildMenu()
    {
        // 主菜单
        $main_menu = MenuLogic::getMainMenu($this->user_id);
        $this->assign('main_menu', $main_menu);
        
        // 侧边菜单
        $sider_menu = MenuLogic::getSiderMenu($this->user_id);
        $this->assign('sider_menu', $sider_menu);
    }

    /**
     * 重写display
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        // 页面标题
        $this->assign('site_title', $this->site_title);
        
        // 处理view
        $this->view = ViewLogic::dealView($this->view);
        
        $html = $this->view->fetch($template, $vars, $replace, $config);
        return ViewLogic::dealContent($html);
    }

    /**
     * 成功
     */
    protected function success($msg = '', $url = '', $data = '', $wait = 3)
    {
        return $this->jump(1, $msg, $url, $data, $wait);
    }

    /**
     * 失败
     */
    protected function error($msg = '', $url = '', $data = '', $wait = 3)
    {
        return $this->jump(0, $msg, $url, $data, $wait);
    }

    /**
     * 跳转
     */
    protected function jump($code = 1, $msg = '', $url = '', $data = '', $wait = 3)
    {
        $request = Request::instance();
        $jump = Format::formatJump($code, $msg, $url, $data, $wait);
        if ($request->isAjax()) {
            return $jump;
        } else {
            $this->site_title || $this->site_title = $msg;
            
            $this->assign('jump', $jump);
            
            return $this->fetch('common/jump');
        }
    }

    /**
     * 视图
     */
    public function getView()
    {
        $this->view = new \think\View();
        return $this->view;
    }

    /**
     * 赋值
     */
    public function assign($name, $value)
    {
        $this->view->assign($name, $value);
    }
}