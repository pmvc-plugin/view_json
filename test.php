<?php
PMVC\Load::plug();
PMVC\addPlugInFolders(['../']);
class ViewJsonTest extends PHPUnit_Framework_TestCase
{
   /**
    * @runInSeparateProcess
    */
    function testHelloJson()
    {
        $json = PMVC\plug('view_json');
        $json->set('text','hello world');
        ob_start();
        $json->process();
        $output = ob_get_contents();
        ob_end_clean();
        $expected = '{"text":"hello world"}';
        $this->assertEquals($expected,trim($output));
    }

    function testAutoChangeViewEngine()
    {
        $c=\PMVC\plug('controller');
        \PMVC\plug('getenv', [
            'HTTP_ACCEPT'=>'application/json'
        ]);
        $json = PMVC\plug('view_json');
        $json->onMapRequest();
        $this->assertEquals('json',$c[_VIEW_ENGINE]);
    }
}
