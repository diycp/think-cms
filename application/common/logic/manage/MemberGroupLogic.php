<?php
namespace app\common\logic\manage;

use app\common\logic\BaseLogic;
use app\common\model\manage\MemberGroupModel;

class MemberGroupLogic extends BaseLogic
{

    protected static $menus = [];

    /**
     * 用户权限
     */
    public static function getGroupMenu($group_id)
    {
        if (empty(self::$menus[$group_id])) {
            $group = MemberGroupModel::get($group_id, 'group_menus');
            self::$menus[$group_id] = $group['group_menus'] ? explode(',', $group['group_menus']) : [];
        }
        return self::$menus[$group_id];
    }
}