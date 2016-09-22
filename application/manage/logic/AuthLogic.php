<?php
namespace app\manage\logic;

use app\common\logic\manage\MenuLogic;
use app\common\logic\manage\MemberLogic;

class AuthLogic extends \app\common\logic\common\AuthLogic
{

    /**
     * 是否授权操作
     */
    public static function isAuthAction($user_id)
    {
        // 超级管理员
        if (MemberLogic::isAdmin($user_id)) {
            return true;
        }
        
        // 菜单不存在
        $current_ment = MenuLogic::getMenuFlag();
        if (empty($current_ment)) {
            return false;
        }
        
        // 授权菜单
        $auth_menu = MemberLogic::getAuthMenu($user_id);
        return in_array($current_ment['id'], $auth_menu);
    }
}