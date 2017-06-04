<?php
namespace PMVC\PlugIn\view;

use PMVC\Event;
use PMVC\HashMap;
use UnexpectedValueException;

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\view_json';

class view_json extends ViewEngine
{
    private $_jsonData;

    public function init()
    {
        $this['headers']=[
            'Content-type: application/json'
        ];
        $this->_jsonData = new HashMap();
        \PMVC\callPlugin(
            'dispatcher',
            'attachBefore',
            [
                $this,
                Event\MAP_REQUEST
            ]
        );
        \PMVC\callPlugin(
            'dispatcher',
            'attachBefore',
            [
                $this,
                Event\B4_PROCESS_VIEW
            ]
        );
    }

    public function onMapRequest()
    {
        if (\PMVC\exists('controller','plugin')) {
            $accept = \PMVC\plug('getenv')->get('HTTP_ACCEPT');
            if ('application/json'===$accept) {
                \PMVC\plug('controller')[_VIEW_ENGINE]='json';
            }
        }
    }

    public function onB4ProcessView()
    {
        if (\PMVC\getOption(_VIEW_ENGINE)==='json') {
            \PMVC\unplug('view_config_helper');
        }
    }

    /**
     * Set theme folder
     */
    public function setThemeFolder($val) { }

    public function onFinish()
    {
        echo json_encode(\PMVC\get($this->_jsonData));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnexpectedValueException(json_last_error_msg());
        }
    }
    
    public function process()
    {
        $this->_jsonData[[]] = $this->get();
        if (!isset($this['run']) && $this->get('themePath')) {
            // should only keep one themePath
            $this->_jsonData['themePath'] = $this->get('themePath');
        }
        $this->clean();
        if (\PMVC\getOption(Event\FINISH) ||
            !\PMVC\exists('dispatcher','plugin')
        ) {
            // run directly if miss event
            return $this->onFinish();
        } else {
            // only run by finish event 
            \PMVC\plug('dispatcher')
                ->attachAfter($this, Event\FINISH);
        }
    }
}
