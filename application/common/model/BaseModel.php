<?php
namespace app\common\model;

use think\Db;

abstract class BaseModel
{

    /**
     * 是否缓存
     */
    protected static $use_cache = false;

    /**
     * 数据库对象池
     */
    protected static $links = [];

    /**
     * 数据库配置
     */
    protected static $connection = [];

    /**
     * 数据表名称
     */
    protected static $table = '';

    /**
     * 当前类名称
     */
    protected static $name = '';

    /**
     * 自动写入时间戳
     */
    protected static $autoWriteTimestamp = false;

    /**
     * 创建时间字段
     */
    protected static $createTime = 'create_time';

    /**
     * 更新时间字段
     */
    protected static $updateTime = 'update_time';

    /**
     * 删除时间字段
     */
    protected static $deleteTime = 'delete_time';

    /**
     * 获取db
     */
    public static function db()
    {
        $model = get_called_class();
        if (! isset(static::$links[$model]) || ! static::$use_cache) {
            // 设置当前模型 确保查询返回模型对象
            $query = Db::connect(static::$connection);
            
            // 设置当前数据表和模型名
            if (! empty(static::$table)) {
                $query = $query->setTable(static::$table);
            } else {
                $query = $query->name(static::$name);
            }
            
            static::$links[$model] = $query;
        }
        // 返回当前模型的数据库查询对象
        return static::$links[$model];
    }

    /**
     * 单条记录
     */
    public static function get($value, $field = '*', $name = 'id')
    {
        $map = array(
            $name => $value
        );
        return self::where($map)->field($field)->find();
    }

    /**
     * 键值对
     */
    public static function getKeyValueList($key = 'id', $field = '*', $map = [])
    {
        $list = self::field($field)->where($map)->select();
        $res = [];
        foreach ($list as $vo) {
            $res[$vo[$key]] = $vo;
        }
        return $res;
    }

    /**
     * 添加
     */
    public static function add($data, $flag = true)
    {
        
        // 创建时间
        if (static::$autoWriteTimestamp) {
            $data[static::$createTime] = $data[static::$updateTime] = time();
        }
        
        // 是否获取自增ID
        if ($flag) {
            return self::insertGetId($data);
        } else {
            return self::insert($data);
        }
    }

    /**
     * 修改
     */
    public static function save($data, $map)
    {
        if (! is_array($map)) {
            $map = [
                'id' => $map
            ];
        }
        
        // 修改时间
        if (static::$autoWriteTimestamp) {
            $data[static::$updateTime] = time();
        }
        
        return self::where($map)->update($data);
    }

    /**
     * 更改
     */
    public static function modify($id, $field, $value)
    {
        $map = [
            'id' => $id
        ];
        $data = [
            $field => $value
        ];
        return self::save($data, $map);
    }

    /**
     * 删除
     */
    public static function del($map, $flag = true)
    {
        if (! is_array($map)) {
            $map = [
                'id' => $map
            ];
        }
        
        // 是否物理删除
        if ($flag) {
            return self::where($map)->delete();
        } else {
            $data = array(
                static::$deleteTime => time()
            );
            return self::save($map, $data);
        }
    }

    /**
     * 逻辑恢复
     */
    public static function recover($map)
    {
        $data = array(
            static::$deleteTime => 0
        );
        return self::save($data, $map);
    }

    /**
     * 获取name，用于join等操作
     */
    public static function getName()
    {
        return static::$name;
    }

    /**
     * 魔术方法
     */
    public static function __callStatic($method, $params)
    {
        $query = static::db();
        return call_user_func_array([
            $query,
            $method
        ], $params);
    }
}