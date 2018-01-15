<?php

class BankApp extends MemberbaseApp
{
	var $_bank_mod;
	
	/* 构造函数 */
    function __construct()
    {
         $this->BankApp();
    }

    function BankApp()
    {
        parent::__construct();
		$this->_bank_mod = &m('bank');
    }
    function index()
    {
		
    }
	
	function add()
	{
		if(!IS_POST)
		{
			$this->_get_curlocal_title('bank_add');
			$this->assign('bank_inc', $this->_get_bank_inc());
			$this->display('bank.form.html');
		}
		else
		{
			$short_name = trim($_POST['short_name']);
			$account_name = trim($_POST['account_name']);
			$type	= trim($_POST['type']);
			$num 	= trim($_POST['num']);
			
			if(empty($short_name)) {
				$this->json_error('short_name_error');
				return;
			}
			if(empty($num)) {
				$this->json_error('num_empty');
				return;
			}
			if(empty($account_name) || strlen($account_name)<6 || strlen($account_name)>30) {
				$this->json_error('account_name_error');
				return;
			}
			if(!in_array($type, array('debit','credit'))){
				$this->json_error('type_error');
				return;
			}
			$bank_name = $this->_get_bank_name($short_name);
			if(empty($bank_name))
			{
				$this->json_error('bank_name_error');
				return;
			}
			
			if (base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
            {
                $this->json_error('captcha_failed');
                return;
            }
			
			$data = array(
				'user_id'		=>	$this->visitor->get('user_id'),
				'bank_name'		=>	$bank_name,
				'short_name'	=>	strtoupper($short_name),
				'account_name'	=>	$account_name,
				'open_bank'		=>  trim($_POST['open_bank']),
				'type'			=> 	$type,
				'num'			=>	$num,
			);
			
			if(!$this->_bank_mod->add($data)){
				$this->json_error('add_error');
				return;
			}
			$this->json_result('', 'add_ok');
		}
	}
	
	function edit()
	{
		$short_name = trim($_GET['short_name']);
		$bid = intval($_GET['bid']);

		if(!IS_POST)
		{
			if($bid) 
			{
				$card = $this->_bank_mod->get($bid);
			}
			else
			{
				if(!$this->_check_short_name($short_name)){
					$this->show_warning('short_name_error');
					return;
				}

				$card = $this->_bank_mod->get(array('conditions'=>"user_id=".$this->visitor->get('user_id')." AND short_name='".strtoupper($short_name)."'"));
			}
			
			
			
			$this->_get_curlocal_title('bank_edit');
			$this->assign('bank_inc', $this->_get_bank_inc());
			$this->assign('card', $card);
			$this->display('bank.form.html');
		}
		else
		{
			
			$short_name = trim($_POST['short_name']);
			$account_name = trim($_POST['account_name']);
			$type	= trim($_POST['type']);
			$num 	= trim($_POST['num']);
			
			if(empty($short_name)) {
				$this->json_error('short_name_empty');
				return;
			}
			if(empty($num)) {
				$this->json_error('num_empty');
				return;
			}
			if(empty($account_name) || strlen($account_name)<6 || strlen($account_name)>30) {
				$this->json_error('account_name_error');
				return;
			}
			if(!in_array($type, array('debit','credit'))){
				$this->json_error('type_error');
				return;
			}

			$bank_name = $this->_get_bank_name($short_name);
			if(empty($bank_name))
			{
				$this->json_error('bank_name_error');
				return;
			}
			
			if (base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
            {
                $this->json_error('captcha_failed');
                return;
            }
			
			$data = array(
				'user_id'		=>	$this->visitor->get('user_id'),
				'bank_name'		=>	$bank_name,
				'short_name'	=>	strtoupper($short_name),
				'account_name'	=>	$account_name,
				'open_bank'		=>  trim($_POST['open_bank']),
				'type'			=> 	$type,
				'num'			=>	$num,
			);

			if(!$this->_bank_mod->edit($card['bid'], $data)){
				$this->json_error('edit_error');
				return;
			}
			$this->json_result('', 'edit_ok');
		}
	}
	
	function drop()
	{
		$bid = intval($_GET['bid']);
		if(!$bid)
		{
			$this->json_error('no_such_bank');
			return;
		}
		
		if(!$this->_bank_mod->drop("user_id=".$this->visitor->get('user_id').' AND bid='.$bid))
		{
			$this->json_error('drop_bank_error');
			return;
		}
		$this->json_result('', 'drop_ok');
	}
	
	function _check_short_name($short_name)
	{
		$bank_inc = $this->_get_bank_inc();
		
		if(!is_array($bank_inc) || count($bank_inc)<1){
			return false;
		}
		
		foreach($bank_inc as $key=>$bank)
		{
			if(strtoupper($short_name)==strtoupper($key)) {
				return true;
			}
		}
		return false;
	}
	
	function _get_bank_name($short_name)
	{
		if(!$this->_check_short_name($short_name)) return '';
		$bank_inc = $this->_get_bank_inc();
		return $bank_inc[$short_name];
	}
}

?>
