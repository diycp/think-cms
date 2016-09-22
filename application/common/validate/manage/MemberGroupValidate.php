<?php
namespace app\common\validate\manage;

use app\common\validate\BaseValidate;

class MemberGroupValidate extends BaseValidate
{

    protected $rule = [
        'group_name' => 'require',
        'group_menus' => 'require'
    ];

    protected $message = [
        'group_name.require' => '分组名称为空',
        'group_menus.require' => '用户组允许访问的菜单为空'
    ];

    protected $scene = [
        'add' => [
            'group_name'
        ],
        'edit' => [
            'group_name'
        ]
    ];
}