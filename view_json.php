<?php
namespace PMVC\PlugIn\view;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\view_json';

class view_json extends ViewEngine
{
    public function process()
    {
        if (!empty($this['forward']->lazyOutput)) {
            return;
        }
        $all = $this->get();
        unset($all[_CLASS]);
        header('Content-type: application/json');
        echo json_encode($all);
    }
}
