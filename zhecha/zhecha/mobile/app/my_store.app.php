<?php

class My_storeApp extends StoreadminbaseApp
{
    var $_store_id;
    var $_store_mod;

    function __construct()
    {
        $this->My_storeApp();
    }
    function My_storeApp()
    {
        parent::__construct();
        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_store_mod =& m('store');
    }
	
	function map()
	{
		if(!IS_POST)
		{
			$store = $this->_store_mod->get(array('conditions'=>'store_id='.$this->_store_id, 'fields'=>'latlng,store_name'));
			if(!empty($store['latlng'])) {
				$latlng = explode(',', $store['latlng']);
				$store['lat'] = $latlng[0];
				$store['lng'] = $latlng[1];
			}
			
			/* 当前页面信息 */
			$this->_config_seo('title', Lang::get('store_map') . ' - ' . $store['store_name']);
			$this->_get_curlocal_title('store_map');
			
			$this->assign('store', $store);
			$this->headtag('<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak='.Conf::get('baidukey.browser').'"></script>');
			$this->display('my_store.map.html');
		}
		else
		{
			if(!$this->_store_mod->edit($this->_store_id,  array('latlng' => trim($_POST['latlng'])))){
				$this->json_error('handle_fail');
				return;
			}
			$this->json_result('', 'handle_ok');
		}
		
	}
}

?>
