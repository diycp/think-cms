<?php
namespace app\manage\block;

class DateBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        if (isset($data['format']) && ! empty($data['format'])) {
            $data['attr'] = 'data-am-datepicker="{format: \'' . $data['format'] . '\'}"';
        } else {
            $data['attr'] = 'data-am-datepicker';
        }
        
        return TextBlock::fetch($data);
    }

    /**
     * field
     */
    public static function field($data = [])
    {
        if (isset($data['format']) && ! empty($data['format'])) {
            $data['attr'] = 'data-am-datepicker="{format: \'' . $data['format'] . '\'}"';
        } else {
            $data['attr'] = 'data-am-datepicker';
        }
        
        return TextBlock::field($data);
    }
}