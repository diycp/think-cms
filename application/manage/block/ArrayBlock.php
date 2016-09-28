<?php
namespace app\manage\block;

class ArrayBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        
        $data['class'] .= ' nd-editor-ace am-hide';
        $data['attr'] = 'nd-type="json" nd-target="nd-editor-ace-' . $data['name'] . '"';
        
        return TextareaBlock::fetch($data);
    }
}