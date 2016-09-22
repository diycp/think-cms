<?php
namespace app\common\logic\common;

class AuthLogic
{

    /**
     * 是否公共操作
     */
    public static function isPublicAction($public_action = array())
    {
        // 当前操作
        $current_action = strtolower(CommonLogic::getCurrentAction());
        
        // 公开操作
        $public_action_patern = '#(^' . implode(')|(^', $public_action) . ')#i';
        
        return preg_match($public_action_patern, $current_action);
    }
}