<?php
namespace app\common\logic\manage;

use think\Cache;
use app\common\logic\BaseLogic;
use app\common\model\manage\ConfigModel;

class ConfigLogic extends BaseLogic
{

    const cache_key = 'common_config_cache';

    /**
     * 配置分组列表
     */
    public static function getGroupList()
    {
        $list = ConfigModel::order('config_sort asc')->select();
        $res = [];
        foreach ($list as $vo) {
            $group = $vo['config_group'];
            if (! isset($res[$group])) {
                $res[$group] = [
                    'name' => $group,
                    'key' => md5($group),
                    'list' => []
                ];
            }
            $res[$group]['list'][] = self::processGroupItem($vo);
        }
        return $res;
    }

    /**
     * 处理分组内容
     */
    public static function processGroupItem($item)
    {
        switch ($item['config_type']) {
            case 'array':
                $arr = empty($item['config_extra']) ? [] : explode(',', $item['config_extra']);
                $value = empty($item['config_value']) ? [] : json_decode($item['config_value'], true);
                foreach ($arr as $vo) {
                    if (! isset($value[$vo])) {
                        $value[$vo] = '';
                    }
                }
                $item['config_value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
                break;
            case 'radio':
            case 'checkbox':
            case 'select':
                $arr = empty($item['config_extra']) ? [] : explode('|', $item['config_extra']);
                $extra = [];
                foreach ($arr as $vo) {
                    list ($value, $name) = explode(':', $vo);
                    $extra[] = [
                        'name' => $name,
                        'value' => $value
                    ];
                }
                $item['config_extra'] = $extra;
                break;
        }
        return $item;
    }

    /**
     * 获取配置
     */
    public static function getKeyValue()
    {
        $config = Cache::get(self::cache_key);
        if (empty($config)) {
            $list = ConfigModel::select();
            $config = [];
            foreach ($list as $vo) {
                switch ($vo['config_type']) {
                    case 'checkbox':
                    case 'array':
                        $vo['config_value'] = empty($vo['config_value']) ? [] : @json_decode($vo['config_value'], true);
                        break;
                }
                $config[$vo['config_name']] = $vo['config_value'];
            }
            
            // 处理变量
            $config = self::processConfig($config);
            
            Cache::set(self::cache_key, $config);
        }
        return $config;
    }

    /**
     * 添加配置
     */
    public static function addConfig($data)
    {
        ConfigModel::add($data);
        return self::clearCache();
    }

    /**
     * 保存配置
     */
    public static function saveConfig($data, $map)
    {
        ConfigModel::save($data, $map);
        return self::clearCache();
    }

    /**
     * 更改配置
     */
    public static function modifyConfig($id, $field, $value)
    {
        ConfigModel::modify($id, $field, $value);
        return self::clearCache();
    }

    /**
     * 删除配置
     */
    public static function delConfig($map)
    {
        ConfigModel::del($map);
        return self::clearCache();
    }

    /**
     * 清除缓存
     */
    public static function clearCache()
    {
        return Cache::rm(self::cache_key);
    }

    /**
     * 处理配置
     */
    public static function processConfig($config)
    {
        $var_list = self::getVariableList();
        foreach ($config as $co => $vo) {
            if (is_array($vo)) {
                $config[$co] = self::processConfig($vo);
            } else {
                $config[$co] = str_replace($var_list[0], $var_list[1], $config[$co]);
            }
        }
        return $config;
    }

    /**
     * 配置类型
     */
    public static function getConfigType()
    {
        return [
            [
                'name' => '文本',
                'value' => 'text'
            ],
            [
                'name' => '文本域',
                'value' => 'textarea'
            ],
            [
                'name' => '标签',
                'value' => 'tag'
            ],
            [
                'name' => '日期',
                'value' => 'date'
            ],
            [
                'name' => '颜色',
                'value' => 'color'
            ],
            [
                'name' => '图片',
                'value' => 'image'
            ],
            [
                'name' => '文件',
                'value' => 'file'
            ],
            [
                'name' => '多选',
                'value' => 'checkbox'
            ],
            [
                'name' => '单选',
                'value' => 'radio'
            ],
            [
                'name' => '下拉',
                'value' => 'select'
            ],
            [
                'name' => '数组',
                'value' => 'array'
            ],
            [
                'name' => '富文本',
                'value' => 'editor'
            ]
        ];
    }

    /**
     * 变量列表
     */
    public static function getVariableList()
    {
        static $var_list;
        if (empty($var_list)) {
            $list = [
                '{EXT}' => EXT,
                '{DS}' => DS,
                '{THINK_PATH}' => THINK_PATH,
                '{WEB_PATH}' => WEB_PATH,
                '{ROOT_PATH}' => ROOT_PATH,
                '{APP_PATH}' => APP_PATH,
                '{CONF_PATH}' => CONF_PATH,
                '{LIB_PATH}' => LIB_PATH,
                '{CORE_PATH}' => CORE_PATH,
                '{TRAIT_PATH}' => TRAIT_PATH,
                '{EXTEND_PATH}' => EXTEND_PATH,
                '{VENDOR_PATH}' => VENDOR_PATH,
                '{RUNTIME_PATH}' => RUNTIME_PATH,
                '{LOG_PATH}' => LOG_PATH,
                '{CACHE_PATH}' => CACHE_PATH,
                '{TEMP_PATH}' => TEMP_PATH
            ];
            $var_list = [
                [],
                []
            ];
            foreach ($list as $co => $vo) {
                $var_list[0][] = $co;
                $var_list[1][] = $vo;
            }
        }
        return $var_list;
    }
}