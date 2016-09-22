<?php
namespace app\manage\controller;

use think\Url;
use think\Request;
use app\common\validate\manage\MemberValidate;
use app\manage\logic\LoginLogic;
use app\common\logic\common\CaptchaVerifyLogic;

class Start extends Base
{

    /**
     * 登录状态
     */
    private function loginState()
    {
        $user = LoginLogic::getLoginUser();
        if (! empty($user)) {
            responseRedirect(LoginLogic::getManageUrl($user['user_id']));
        }
    }

    /**
     * 登录页面
     */
    public function login()
    {
        $this->loginState();
        
        $this->site_title = '登录后台';
        
        $login_url = Url::build('start/doLogin');
        $this->assign('login_url', $login_url);
        
        $code_url = CaptchaVerifyLogic::getCodeSrc('manage_login');
        $this->assign('code_url', $code_url);
        
        return $this->fetch();
    }

    /**
     * 验证登录
     */
    public function doLogin(Request $request)
    {
        $data = [
            'user_name' => $request->param('user_name'),
            'user_passwd' => $request->param('user_passwd')
        ];
        
        $validate = new MemberValidate();
        $res = $validate->scene('login')->check($data);
        if (! $res) {
            return $this->error($validate->getError());
        }
        
        if (! CaptchaVerifyLogic::checkCode($request->param('verify_code'), 'manage_login')) {
            return $this->error('验证码错误');
        }
        
        $res = LoginLogic::doLogin($data);
        if ($res['code'] == 1) {
            return $this->success($res['msg'], Url::build('start/login'));
        } else {
            return $this->error($res['msg']);
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        LoginLogic::loginOut();
        return $this->success('退出登录成功', Url::build('start/login'));
    }
}