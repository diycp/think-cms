<?php
namespace app\common\logic\manage;

use app\common\logic\BaseLogic;
use app\common\model\manage\MenuModel;
use app\common\logic\common\CommonLogic;
use app\common\logic\manage\MemberLogic;

class MenuLogic extends BaseLogic
{

    /**
     * 菜单树
     */
    public static function getMenuTree()
    {
        $menu = [
            'main_menu' => [],
            'sub_menu' => [],
            'sub_sub_menu' => []
        ];
        
        // 一级菜单
        $map = [
            'menu_pid' => 0
        ];
        $list = MenuModel::where($map)->order('menu_sort asc')->select();
        
        $main_pids = [];
        foreach ($list as $vo) {
            $main_pids[] = $vo['id'];
            $menu['main_menu'][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
            $menu['sub_menu'][$vo['id']] = [];
        }
        
        // 二级菜单
        $map = [
            'menu_pid' => [
                'in',
                $main_pids
            ]
        ];
        $list = MenuModel::where($map)->order('menu_sort asc')->select();
        
        $sub_pids = [];
        foreach ($list as $vo) {
            $sub_pids[] = $vo['id'];
            $menu['sub_menu'][$vo['menu_pid']][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
            $menu['sub_sub_menu'][$vo['id']] = [];
        }
        
        // 三级菜单
        $map = [
            'menu_pid' => [
                'in',
                $sub_pids
            ]
        ];
        $list = MenuModel::where($map)->order('menu_sort asc')->select();
        
        $sub_pids = [];
        foreach ($list as $vo) {
            $sub_pids[] = $vo['id'];
            $menu['sub_sub_menu'][$vo['menu_pid']][$vo['id']] = [
                'menu_id' => $vo['id'],
                'menu_name' => $vo['menu_name']
            ];
        }
        
        return $menu;
    }

    /**
     * 新增菜单
     */
    public static function addMenu($data)
    {
        $data['menu_flag'] = self::getMenuRule($data['menu_url']);
        return MenuModel::add($data);
    }

    /**
     * 保存菜单
     */
    public static function saveMenu($data, $map)
    {
        $data['menu_flag'] = self::getMenuRule($data['menu_url']);
        return MenuModel::save($data, $map);
    }

    /**
     * 当前菜单
     */
    public static function getCurrentMenu($menu_flag = null)
    {
        // 当前菜单
        $current_menu = self::getMenuFlag($menu_flag);
        if (empty($current_menu)) {
            return null;
        }
        
        // 上级菜单
        $parent_menu = MenuModel::get($current_menu['menu_pid']);
        if ($parent_menu['menu_pid'] > 0) {
            return self::getCurrentMenu($parent_menu['menu_flag']);
        } else {
            return $current_menu;
        }
    }

    /**
     * 主菜单
     */
    public static function getMainMenu($user_id)
    {
        // 当前菜单
        $current_menu = self::getCurrentMenu();
        
        $main_menu = self::getMenuByPid(0, $user_id);
        foreach ($main_menu as &$menu) {
            if ($current_menu && $menu['menu_id'] == $current_menu['menu_pid']) {
                $menu['menu_active'] = 1;
            } else {
                $menu['menu_active'] = 0;
            }
        }
        unset($menu);
        
        return $main_menu;
    }

    /**
     * 侧边菜单
     */
    public static function getSiderMenu($user_id)
    {
        // 当前菜单
        $current_menu = self::getCurrentMenu();
        
        if (empty($current_menu)) {
            return array();
        } else {
            $sider_menu = self::getMenuByPid($current_menu['menu_pid'], $user_id);
            foreach ($sider_menu as &$menu) {
                if (isset($menu['sub_menu'])) {
                    $menu['menu_active'] = 0;
                    foreach ($menu['sub_menu'] as &$item) {
                        if ($item['menu_id'] == $current_menu['id']) {
                            $item['menu_active'] = 1;
                            $menu['menu_active'] = 1;
                        } else {
                            $item['menu_active'] = 0;
                        }
                    }
                    unset($item);
                } else {
                    if ($menu['menu_id'] == $current_menu['id']) {
                        $menu['menu_active'] = 1;
                    } else {
                        $menu['menu_active'] = 0;
                    }
                }
            }
            unset($menu);
            return $sider_menu;
        }
    }

    /**
     * 根据标识获取菜单
     */
    public static function getMenuFlag($menu_flag = null)
    {
        if (empty($menu_flag)) {
            $menu_flag = CommonLogic::getCurrentAction();
        }
        $map = array(
            'menu_flag' => $menu_flag,
            'menu_pid' => array(
                'gt',
                0
            )
        );
        return MenuModel::where($map)->find();
    }

    /**
     * 根据Pid获取菜单
     */
    public static function getMenuByPid($menu_pid, $user_id)
    {
        $auth_menu = MemberLogic::getAuthMenu($user_id);
        $map = array(
            'menu_status' => 1,
            'menu_pid' => $menu_pid,
            'id' => [
                'in',
                $auth_menu
            ]
        );
        $list = MenuModel::where($map)->order('menu_sort asc')->select();
        
        $menu = array();
        foreach ($list as $v) {
            if ($v['menu_group'] && $menu_pid > 0) {
                $key = 'group_' . md5($v['menu_group']);
                if (! isset($menu[$key])) {
                    $menu[$key] = array(
                        'menu_name' => $v['menu_group'],
                        'sub_menu' => array()
                    );
                }
                $menu[$key]['sub_menu'][] = self::getMenuItem($v);
            } else {
                $key = 'menu_' . $v['id'];
                $menu[$key] = self::getMenuItem($v);
            }
        }
        
        return $menu;
    }

    /**
     * 获取菜单项
     */
    public static function getMenuItem($item)
    {
        return array(
            'menu_id' => $item['id'],
            'menu_name' => $item['menu_name'],
            'menu_url' => url($item['menu_url'])
        );
    }

    /**
     * 获取url标识
     */
    public static function getMenuRule($url)
    {
        // 当前url
        $url = url($url);
        
        // 当前路径
        $path = CommonLogic::getWebPath();
        
        // 相对url
        list ($url_relative, $temp) = explode('.', $url);
        $url_relative = str_replace_once($path, '', $url_relative);
        
        // 获取rule
        $arr = explode('/', $url_relative);
        $arr = array_slice($arr, 0, 3);
        $flag = implode('/', $arr);
        
        return $flag;
    }
}