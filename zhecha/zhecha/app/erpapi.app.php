<?php

/* erp接口 */
class ErpapiApp extends MallbaseApp
{
    function index(){
        $str = $_REQUEST;
        addLog('erp', $str);
    }
    
}

?>
