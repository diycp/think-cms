<?php
namespace app\manage\block;

class BaseBlock
{

    protected static $default = [
        'l_sm_num' => 4,
        'l_md_num' => 2,
        'r_sm_num' => 8,
        'r_md_num' => 6,
        
        'title' => '0',
        'type' => 'text',
        'rows' => 5,
        'holder' => '',
        'name' => '',
        'value' => '',
        'list' => [],
        'tip' => '',
        
        'class' => '',
        'style' => '',
        'attr' => '',
        
        'text_ok' => '确定操作',
        'text_cancel' => '取消操作',
        
        'target' => 'ajax-form'
    ];

    protected static $default_search = [
        'sm_num' => 12,
        'md_num' => 2,
        
        'holder' => '',
        'name' => '',
        'value' => '',
        'list' => [],
        'all' => 1,
        
        'class' => '',
        'style' => '',
        'attr' => '',
        
        'text' => '搜索',
        'target' => 'search-form'
    ];
}