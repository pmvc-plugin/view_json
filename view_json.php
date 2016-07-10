<?php
namespace PMVC\PlugIn\view;

use PMVC\Event;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\view_json';

\PMVC\initPlugin(['controller'=>null]);

class view_json extends ViewEngine
{
    public function init()
    {
        $this['headers']=[
            'Content-type: application/json'
        ];
    }

    /**
     * Set theme folder
     */
    public function setThemeFolder($val) { }

    public function onFinish()
    {
        $all = $this->get();
        echo json_encode($all);
        if (json_last_error() !== JSON_ERROR_NONE) {
            trigger_error(json_last_error_msg());
        }
    }
    
    public function process()
    {
        if (\PMVC\getOption(Event\FINISH) ||
            !\PMVC\exists('dispatcher','plugin')
        ) {
            return $this->onFinish();
        } else {
            // only need trigger when process
            \PMVC\plug('dispatcher')
                ->attachAfter($this, Event\FINISH);
        }
    }
}
