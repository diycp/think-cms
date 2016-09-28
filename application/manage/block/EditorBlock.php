<?php
namespace app\manage\block;

class EditorBlock extends BaseBlock
{

    /**
     * fetch
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        $data['class'] .= 'nd-editor-wang';
        
        $data['attr'] = 'nd-target="' . $data['name'] . '"';
        
        $data['rows'] = 30;
        
        return TextareaBlock::fetch($data);
    }
}