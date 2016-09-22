<?php
namespace app\common\validate\manage;

use app\common\validate\BaseValidate;

class MenuValidate extends BaseValidate
{

    protected $rule = [
        'menu_name' => 'require',
        'menu_url' => 'require',
        'menu_pid' => 'require'
    ];

    protected $message = [
        'menu_name.require' => '菜单名称为空',
        'menu_url.require' => '菜单链接为空',
        'menu_pid.require' => '上级菜单为空'
    ];

    protected $scene = [
        'add' => [
            'menu_name',
            'menu_url',
            'menu_pid'
        ],
        'edit' => [
            'menu_name',
            'menu_url',
            'menu_pid'
        ]
    ];
}