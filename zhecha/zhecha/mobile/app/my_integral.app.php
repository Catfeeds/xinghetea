<?php

class My_integralApp extends MemberbaseApp
{
	var $_integral_mod;
	
    function __construct()
	{
		parent::__construct();
		$this->_integral_mod = &m('integral');
	}
	
    function index()
    {
		if(!$this->_integral_mod->_get_sys_setting('integral_enabled'))
		{	
			$this->show_warning('integral_disabled');
			exit;
		}
		$type=$_GET['type'] ? $_GET['type'] :'';
		switch($type)
		{
			case 'integral_income':
			$curlocal='integral_income';
			$conditions=" AND changes > 0  AND state = 'finished' ";
			break;
			
			case 'integral_pay':
			$curlocal='integral_pay';
			$conditions=" AND changes < 0 AND state = 'finished' ";
			break;
			
			case 'integral_frozen':
			$curlocal='frozen_integral';
			$conditions=" AND  state = 'frozen' ";
			break;
			
			default:
			$curlocal='integral_log';
		}
		
		//会员当前的可用积分
		$integral_mod=&m('integral');
		$integral=$integral_mod->get($this->visitor->get('user_id'));
		$this->assign('integral_piont',$integral['amount']);
		
		
		//会员当前被冻结的积分
		$order_integral_mod = &m('order_integral');
		$frozen_integral=$order_integral_mod->get(array(
			'conditions'=>'buyer_id='.$this->visitor->get('user_id'),
			'fields'    =>'SUM(frozen_integral) AS frozen_pint',
		));
		$this->assign('frozen_integral',$frozen_integral['frozen_pint']);
		
		
        $page = $this->_get_page(10);
		$integral_log_mod=&m('integral_log');
		$order_mod=&m('order');
		$integral_log=array();
		$integral_log=$integral_log_mod->find(array(
			'conditions'=>'user_id='.$this->visitor->get('user_id').$conditions,
			'order'     =>'add_time DESC',
			'limit'     =>$page['limit'],
			'count'     =>true
		));
		
		foreach($integral_log as $key => $val)
		{
			$integral_log[$key]['state'] = $this->_integral_mod->status($val['state']);
			$integral_log[$key]['name'] = Lang::get($val['type']);
			$order=$order_mod->get(array(
				'conditions'=>'order_id='.$val['order_id'],
				'fields'    =>'order_sn',
			));
			$integral_log[$key]['order_sn']=$order['order_sn'];
		}
        $page['item_count'] = $integral_log_mod->getCount();   //获取统计的数据
        $this->_format_page($page);
        $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
        $this->assign('integral_log', $integral_log);
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_integral'));
		$this->_get_curlocal_title('my_integral');
        $this->display('my_integral.index.html');
    }
}
?>
