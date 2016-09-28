<?php
namespace app\manage\block;

class ColorBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        $data['class'] .= 'nd-color';
        
        return TextBlock::fetch($data);
    }
}