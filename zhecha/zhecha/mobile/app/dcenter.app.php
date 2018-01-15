<?php

class DcenterApp extends MemberbaseApp
{
	var $_distribution_mod;
	var $_member_mod;
	var $_user_id;
	var $_goods_mod;
	
    function __construct()
    {
        $this->DcenterApp();
    }
    function DcenterApp()
    {
        parent::__construct();
		$this->_goods_mod = &m('goods');
		$this->_distribution_mod = &m('distribution');
		$this->_member_mod = &m('member');
		$this->_user_id = $this->visitor->get('user_id');
		$distribution = $this->_distribution_mod->get('user_id='.$this->_user_id);
		if(!$distribution)
		{
			$this->show_warning('not_join');
			exit;
		}	
    }
    function index()
    {
		$model_order = &m('order');
		$descendant_dids = $this->_distribution_mod->get_dids_by_user($this->_user_id);
        $allorders = $model_order->find(array(
            'conditions'    => 'did'. db_create_in($descendant_dids).$conditions,
			'fields' => 'order_id',
        ));
		$this->assign('orderscount',count($allorders));
		$this->assign('statistics',$this->_get_statistics());
        $this->assign('teamscount',count($this->_distribution_mod->find('parent_id='.$this->_user_id)));
		$this->assign('storescount',count($this->_distribution_mod->find('user_id='.$this->_user_id)));
		$this->_get_curlocal_title('distribution_center');
		$this->_config_seo('title', Lang::get('distribution_center'));
        $this->display('dcenter.index.html');
	}
	
	//修改小店名称上传小店logo
	function edit()
	{
		$did = intval($_GET['did']);
		$distribution = $this->_distribution_mod->get('user_id='.$this->_user_id.' AND did='.$did);
		if(!$did || !$distribution)
		{
			$this->show_warning('limit_error');
			return;
		}
		if(!IS_POST)
		{
			$this->assign('distribution',$distribution);
			$this->_get_curlocal_title('edit_info');
			$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('edit_info'));
			$this->display('dcenter.edit.html');
		}
		else
		{
			$real_name = trim($_POST['real_name']);
			$phone_mob = trim($_POST['phone_mob']);
			if(empty($real_name) || empty($phone_mob))
			{
				$this->show_warning('name_or_phone_empty');
				return;
			}
			if(!is_mobile($phone_mob)){
				$this->show_warning('input_phone_mob');
				return;
			}
			$data = array(
				'real_name' => $real_name,
				'phone_mob' => $phone_mob,
			);
			$logo = $this->_upload_logo($did);
			if($logo)
			{
				$data['logo'] = $logo;
			}
			$this->_distribution_mod->edit('did='.$did,$data);
			$this->show_message('edit_info_ok','back_list',SITE_URL.'/mobile/index.php?app=store&id='.$distribution['store_id'].'&did='.$did);			
		}
	}
	
	function profit()
	{
		$page = $this->_get_page(10);
		$model_order = &m('order');
		$descendant_dids = $this->_distribution_mod->get_dids_by_user($this->_user_id);
        $orders = $model_order->findAll(array(
            'conditions'    => 'status=40 AND did'. db_create_in($descendant_dids),
			'limit' => $page['limit'],
			'count' => true,
			'order' => 'order_id desc',
        ));
		$page['item_count'] = $model_order->getCount();
		foreach ($orders as $key => $order)
        {
			$refund = $this->_get_refund($order);
			$profit = $this->_distribution_mod->get_profit($order['order_id'],$refund);
			foreach($profit as $k=>$v)
			{
				if($v['user_id'] == $this->_user_id)
				{
					$orders[$key]['layer'] = $k+1;
					$orders[$key]['item_profit'] = $v['amount'];
				}
			}
        }
		if($_GET['layer'])  //刷选
		{
			foreach($orders as $k=>$v)
			{
				if($v['layer'] != $_GET['layer'])
				{
					unset($orders[$k]);
				}
			}
		}
		$this->_format_page($page);
		$this->assign('page_info',$page);
		$this->assign('orders',$orders);
		$this->assign('statistics',$this->_get_statistics());
		$this->_get_curlocal_title('total_profit');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('total_profit'));
		$this->display('dcenter.profit.html');
	}
	
	function team()
	{
		$page = $this->_get_page(10);
		$teams = $this->_distribution_mod->find(array(
			'conditions' =>'parent_id='.$this->_user_id,
			'limit' => $page['limit'],
			'count' => true,
		));
		foreach($teams as $key=>$team)
		{
			$member = $this->_member_mod->get($team['user_id']);
			$teams[$key]['portrait'] = portrait($team['user_id'], $member['portrait'], 'middle');
			$childs = $this->_distribution_mod->get(array('conditions'=>'parent_id='.$team['user_id'],'fields'=>'count(*) as childcount'));
			$teams[$key]['childcount'] = $childs['childcount'];
		}
		$page['item_count'] = $this->_distribution_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info',$page);
		$this->assign('teams',$teams);
		$this->assign('teamscount',count($teams));
		$this->assign('statistics',$this->_get_statistics());
		$this->_get_curlocal_title('my_team');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('my_team'));
		$this->display('dcenter.team.html');
	}
	
	function stores()
	{
		$page = $this->_get_page(10);
		$stores = $this->_distribution_mod->find(array(
			'conditions' =>'user_id='.$this->_user_id,
			'limit' => $page['limit'],
			'count' => true,
		));
		$store_mod = &m('store');
		$deposit_trade_mod = &m('deposit_trade');
		foreach($stores  as $key=>$store)
		{
			$store_info = $store_mod->get(array(
				'conditions'=>$store['store_id'],
				'fields'=>'store_logo,store_name,owner_name,tel,enable_distribution,distribution_1,distribution_2,distribution_3'
			));
			$stores[$key]['store_logo'] = $store_info['store_logo'] ? $store_info['store_logo'] : Conf::get('default_store_logo');
			$stores[$key]['store_name'] = $store_info['store_name'];
			$stores[$key]['owner_name'] = $store_info['owner_name'];
			$stores[$key]['tel'] = $store_info['tel'];
			$stores[$key]['enable_distribution'] = $store_info['enable_distribution'];
			$stores[$key]['distribution_1'] = $store_info['distribution_1'];
			$stores[$key]['distribution_2'] = $store_info['distribution_2'];
			$stores[$key]['distribution_3'] = $store_info['distribution_3'];
			$sum_amount = $deposit_trade_mod->get(array(
				'conditions' => "bizIdentity='".TRADE_FX."' AND buyer_id=".$this->_user_id.' AND seller_id='.$store['store_id'],
				'fields' => 'sum(amount) as sum_amount',
			));
			$stores[$key]['amount'] = $sum_amount['sum_amount'];
		}
		$page['item_count'] = $this->_distribution_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info',$page);
		$this->assign('stores',$stores);
		$this->assign('storescount',count($stores));
		$this->assign('statistics',$this->_get_statistics());
		$this->_get_curlocal_title('my_stores');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('my_stores'));
		$this->display('dcenter.stores.html');
	}
	
	function order()
	{
		$this->assign($this->_get_orders());
        $this->assign('type', $_GET['type']);
		$this->_get_curlocal_title('my_order');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('my_order'));
		$this->display('dcenter.order.html');
	}
	
	function _get_orders()
	{
		$page = $this->_get_page(10);
        $model_order =& m('order');	
        !$_GET['type'] && $_GET['type'] = 'all_orders';
		$conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'status',
                'name'  => 'type',
                'handler' => 'order_status_translator',
            ),
        ));
		$descendant_dids = $this->_distribution_mod->get_dids_by_user($this->_user_id);
        $orders = $model_order->findAll(array(
            'conditions'    => 'did'. db_create_in($descendant_dids).$conditions,
            'count'         => true,
            'join'          => 'has_orderextm',
            'limit'         => $page['limit'],
            'order'         => 'add_time DESC',
            'include'       =>  array(
                'has_ordergoods',       //取出商品
            ),
        ));
		
		foreach ($orders as $key => $order)
        {
			$distribution = $this->_distribution_mod->get('did='.$order['did']);
			if($distribution)
			{
				$orders[$key]['dtb_name'] = $distribution['real_name'];
				$orders[$key]['dtb_phone_mob'] = $distribution['phone_mob'];
			}
			$refund = $this->_get_refund($order);
			$profit = $this->_distribution_mod->get_profit($order['order_id'],$refund);
			foreach($profit as $k=>$v)
			{
				if($v['user_id'] == $this->_user_id)
				{
					$orders[$key]['layer'] = $k+1;
					$orders[$key]['item_profit'] = $v['amount'];
				}
			}
			if($refund)
			{
				$orders[$key]['status'] = 'refund_success';
			}
        }
        $page['item_count'] = $model_order->getCount();
		$this->_format_page($page);
		return array('orders'=>$orders,'page_info'=>$page,'statistics'=>$this->_get_order_statistics($conditions));
	}
	
	function _get_order_statistics($conditions='')
	{
		$model_order = &m('order');
		$descendant_dids = $this->_distribution_mod->get_dids_by_user($this->_user_id);
        $allorders = $model_order->find(array(
            'conditions'    => 'did'. db_create_in($descendant_dids).$conditions,
        ));

		$order_statistics = array();
		foreach($allorders as $key => $order)
		{
			$refund = $this->_get_refund($order);
			$profit = $this->_distribution_mod->get_profit($order['order_id'],$refund);
			foreach($profit as $k=>$v)
			{
				if($v['user_id'] == $this->_user_id)
				{
					$order_statistics['layer'.($k+1)]['count'] ++;
					$order_statistics['total']['count'] ++;
					$order_statistics['total']['amount'] += $v['amount'];
				}
			}
		}
		return $order_statistics;
	}
	
	//佣金统计
	function _get_statistics()
	{
		$model_distribution_statistics = &m('distribution_statistics');
		$statistics = $model_distribution_statistics->get($this->_user_id);
		return $statistics;
	}
	
	function ranks()
	{
		$deposit_trade_mod = &m('deposit_trade');
		$all_records = $deposit_trade_mod->find(array(
			'conditions' => "flow='income' AND bizIdentity='".TRADE_FX."' GROUP BY buyer_id",
			'fields' => 'buyer_id,sum(amount) as amount',
			'order'  => 'amount desc',
		));
		$member_mod = &m('member');	
		foreach($all_records as $record)
		{
			$distribution = $this->_distribution_mod->get('user_id='.$record['buyer_id']);
			$ranks[$record['buyer_id']]['user_id'] = $record['buyer_id']; 
			$ranks[$record['buyer_id']]['real_name'] = $distribution['real_name'];
			$member = $member_mod->get($record['buyer_id']);
			$ranks[$record['buyer_id']]['logo'] = $distribution['logo'] ? $distribution['logo'] : portrait($distibution['user_id'], $member['portrait'], 'middle');
			$ranks[$record['buyer_id']]['teams'] = count($this->_distribution_mod->find('parent_id='.$record['buyer_id']));
			$ranks[$record['buyer_id']]['amount'] = $record['amount']; 
		}
		if(is_array($ranks) && $rank = array_keys(array_keys($ranks),$this->_user_id,false))
		{
			$my_rank['rank'] = $rank[0]+1;
		}
		$my_rank['amount'] = $ranks[$this->_user_id]['amount'];
		$this->assign('my_rank',$my_rank);
		$this->assign('ranks',$ranks);
		$this->_get_curlocal_title('ranks');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('ranks'));
		$this->display('dcenter.ranks.html');
	}
	
	function _upload_logo($did)
    {
        import('uploader.lib');
        $file = $_FILES['logo'];
        if ($file['error'] == UPLOAD_ERR_OK && $file !='')
        {
            $uploader = new Uploader();
            $uploader->allowed_type(IMAGE_FILE_TYPE);
            //$uploader->allowed_size(SIZE_STORE_LOGO);
            $uploader->addFile($file);
            $uploader->root_dir(ROOT_PATH);
            return $uploader->save('data/files/distribution', 'store_logo_'.$did);
        }
	}
	
	function _get_refund($order)
	{
		$deposit_trade_mod = &m('deposit_trade');
		$refund_mod = &m('refund');
		$sql = "select refund_goods_fee from {$deposit_trade_mod->table} as t left join {$refund_mod->table} as r on t.tradeNo=r.tradeNo where r.status='SUCCESS' and bizOrderId='{$order['order_sn']}' and r.buyer_id={$order['buyer_id']}";
		$data = current($deposit_trade_mod->getAll($sql));
		return $data['refund_goods_fee'];
	}
}

?>
