<?php
namespace app\manage\block;

class TextareaBlock extends BaseBlock
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
        $html .= '<textarea name="' . $data['name'] . '" placeholder="' . $data['holder'] . '" class="' . $data['class'] . '" style="' . $data['style'] . '" rows="' . $data['rows'] . '" ' . $data['attr'] . '>' . $data['value'] . '</textarea>';
        
        // 提示
        if (! empty($data['tip'])) {
            $html .= '(' . $data['tip'] . ')';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}