<?php

/**
 *    搜索并选择商品
 *
 *    @author    Hyber
 *    @usage    none
 */

class GselectorApp extends MallbaseApp
{
    var $_is_dialog;      // 是否是对话框
    var $_title;
    var $_store_id = 0;     // 店铺ID

    var $_store_mod;

    function __construct()
    {
        $this->GselectorApp();
    }
    function GselectorApp()
    {
        parent::__construct();
        $this->_is_dialog = isset($_GET['dialog']);
        $this->_store_id = empty($_GET['store_id']) ? 0 : intval($_GET['store_id']);
        $this->_title = empty($_GET['title']) ? 'gselector' : trim($_GET['title']);

        $this->assign('title', Lang::get($this->_title));
    }
	
	/* 显示通用验证码弹出层 */
	function captcha()
	{
		if ($this->_is_dialog)
        {
            header('Content-Type:text/html;charset=' . CHARSET);
        }
		$member_mod = &m('member');
		if($this->visitor->has_login) {
			$member = $member_mod->get(array('conditions'=>'user_id='.$this->visitor->get('user_id'), 'fields'=>'phone_mob, email'));
		} elseif($_GET['user_name']) {
			$member = $member_mod->get(array('conditions'=>'user_name="'.trim($_GET['user_name']).'"', 'fields'=>'phone_mob, email'));
		}
		$member['phone_mob'] = cut_str($member['phone_mob'], 3, 3);
		$member['email']     = cut_str($member['email'], 3, 5);
		$this->assign('captcha', array('from' => 'find_password'));
		$this->assign('user', $member);
		header('Content-Type:text/html;charset=' . CHARSET);
		$this->display('captcha.form.html'); 
	}
}

?>