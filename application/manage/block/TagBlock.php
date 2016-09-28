<?php
namespace app\manage\block;

class TagBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        $data['class'] .= 'nd-tag';
        
        return TextBlock::fetch($data);
    }
}