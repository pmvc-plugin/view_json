<?php
namespace PMVC\PlugIn\view;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\view_json';

class view_json extends ViewEngine
{
    public function init()
    {
        $this['headers']=[
            'Content-type: application/json'
        ];
    }
    
    public function process()
    {
        if (!empty($this['forward']->action)) {
            return;
        }
        $all = $this->get();
        echo json_encode($all);
    }
}
