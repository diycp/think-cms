<?php
namespace app\manage\block;

class SelectBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        $data = array_merge(self::$default, $data);
        
        $html = '<div class="am-g am-margin-top-sm">';
        $html .= '<div class="am-u-sm-' . $data['l_sm_num'] . ' am-u-md-' . $data['l_md_num'] . ' am-text-right">' . $data['title'] . '</div>';
        $html .= '<div class="am-u-sm-' . $data['r_sm_num'] . ' am-u-md-' . $data['r_md_num'] . ' am-u-end">';
        $html .= '<select name="' . $data['name'] . '" data-am-selected="{btnSize: \'sm\'}">';
        foreach ($data['list'] as $vo) {
            if ($data['value'] === $vo['value']) {
                $html .= '<option selected value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            }
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * field
     */
    public static function field($data = [])
    {
        $data = array_merge(self::$default_search, $data);
        
        $html = '<div class="am-u-sm-' . $data['sm_num'] . ' am-u-md-' . $data['md_num'] . ' am-u-end">';
        $html .= '<div class="am-form-group">';
        $html .= '<select name="' . $data['name'] . '" class="nd-search-field" data-am-selected="{btnSize: \'sm\'}">';
        if ($data['all']) {
            $html .= '<option value="**">不限</option>';
        }
        foreach ($data['list'] as $vo) {
            if ($data['value'] === $vo['value']) {
                $html .= '<option selected value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            }
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}