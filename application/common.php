<?php

/**
 * 字符串替换一次
 */
function str_replace_once($needle, $replace, $haystack)
{
    $pos = strpos($haystack, $needle);
    
    if ($pos === false) {
        
        return $haystack;
    }
    
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}

/**
 * 接口返回
 */
function apiReturn($code = 1, $info = '', $data = '')
{
    $res = \newday\common\Format::formatResult($code, $info, $data);
    responseReturn($res, 'json');
}

/**
 * 返回json
 */
function responseReturn($data, $type = 'json')
{
    $response = \think\Response::create($data, $type);
    throw new \think\exception\HttpResponseException($response);
}

/**
 * 跳转url
 */
function responseRedirect($url, $param = array(), $need_build = true)
{
    if ($need_build) {
        $url = \think\Url::build($url, $param);
    } elseif (is_array($param) && count($param)) {
        $url .= strpos($url, '?') ? '&' : '?';
        $url .= http_build_query($param);
    }
    
    $response = new \think\Response();
    $response->header('Location', $url);
    throw new \think\exception\HttpResponseException($response);
}

/**
 * block
 */
function block($name, $data = [], $module = 'manage', $method = 'fetch')
{
    $class = '\\app\\' . $module . '\\block\\' . ucfirst($name) . 'Block';
    if (class_exists($class)) {
        return $class::$method($data);
    } else {
        return '';
    }
}

/**
 * search
 */
function search($name, $data = [], $module = 'manage', $method = 'field')
{
    $class = '\\app\\' . $module . '\\block\\' . ucfirst($name) . 'Block';
    if (class_exists($class)) {
        return $class::$method($data);
    } else {
        return '';
    }
}

/**
 * 记录log
 */
function addLog($content, $status = 1, $group = 'common')
{
    $data = array();
    $data['log_status'] = $status;
    $data['log_group'] = $group;
    $data['log_content'] = is_array($content) ? var_export($content, true) : $content;
    $data['create_time'] = time();
    \think\Db::connect([], true)->name('sys_log')->add($data);
}
