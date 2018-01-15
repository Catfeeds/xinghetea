<?php

/**
 * alipayconnect
 *
 */

class AlipayconnectPlugin extends BasePlugin
{
	var $_config = array();
    
    function __construct($plugin_info)
    {
        $this->_config = $plugin_info;
    }
	function _config_info()
	{
		$data = array(
			'partner'	=> $this->_config['partner'],
			'key'		=> $this->_config['key'],
			'sign_type' => strtoupper('MD5'),
			'input_charset'=>strtolower(CHARSET),
			'transport'	=> 'http',
			'cacert'    => getcwd().'\\cacert.pem',
			'return_url'=> site_url() . "/index.php?app=alipayconnect&act=callback",
		);
		return $data;	
	}	
}

?>