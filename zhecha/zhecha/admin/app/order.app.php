<?php

class OrderApp extends BackendApp
{	

    function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js',
		));
        $this->display('order.index.html');
    }
	
	function get_xml()
	{
		$conditions = '1=1';
		if ($_POST['query'] != '') 
		{
			$conditions .= " AND ".$_POST['qtype']." like '%" . $_POST['query'] . "%'";
		}
		$order = 'add_time DESC';
        $param = array('order_sn','seller_name','add_time','buyer_name','order_amount','payment_name','status');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
		if($_GET['type'] == 'distribution')
		{
			$conditions .= ' AND did>0';
		}
		$pre_page = $_POST['rp']?intval($_POST['rp']):10;
		$page   =   $this->_get_page($pre_page);
		$model_order =& m('order');
		$orders = $model_order->findAll(array(
            'conditions'    => $conditions.$where,
            'limit'         => $page['limit'],  //获取当前页的数据
            'order'         => $order,
            'count'         => true,             //允许统计
			'include'       =>  array(
                'has_ordergoods',       //取出商品
            ),
        ));
        $page['item_count'] = $model_order->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($orders as $k => $v){
			$list = array();
			$operation = "<a class='btn green' href='index.php?app=order&act=view&id={$k}'><i class='fa fa-search-plus'></i>查看</a>";
			$list['operation'] = $operation;
			$list['order_sn'] = $v['order_sn'];
			$list['seller_name'] = $v['seller_name'];
			$list['add_time'] = local_date('Y-m-d H:i:s',$v['add_time']);
			$list['buyer_name'] = $v['buyer_name'];
			$list['order_amount'] = $v['order_amount'];
			$list['payment_name'] = $v['payment_name'];
			$list['status'] = order_status($v['status']);
			$list['distribution'] = $v['did']>0?'是':'否';
			$data['list'][$k] = $list;
		}
		$this->flexigridXML($data);
	}

    /**
     *    查看
     *

     *    @param    none
     *    @return    void
     */
    function view()
    {
        $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$order_id)
        {
            $this->show_warning('no_such_order');

            return;
        }

        /* 获取订单信息 */
        $model_order =& m('order');
        $order_info = $model_order->get(array(
            'conditions'    => $order_id,
            'join'          => 'has_orderextm',
            'include'       => array(
                'has_ordergoods',   //取出订单商品
            ),
        ));

        if (!$order_info)
        {
            $this->show_warning('no_such_order');
            return;
        }
		
		$distribution_mod = &m('distribution');
		$order_info['distribution'] = $distribution_mod->get_profit($order_id);
		$member_mod = &m('member');
		foreach($order_info['distribution'] as $k=>$val)
		{
			$member = $member_mod->get(array('conditions' => $val['user_id'],'fields'=>'user_name'));
			$order_info['distribution'][$k]['user_name'] = $member['user_name'];
			$order_info['distribution'][$k]['layer'] = $k+1;
		}
		
        $order_type =& ot($order_info['extension']);
        $order_detail = $order_type->get_order_detail($order_id, $order_info);
        $order_info['group_id'] = 0;
        if ($order_info['extension'] == 'groupbuy')
        {
            $groupbuy_mod =& m('groupbuy');
            $groupbuy = $groupbuy_mod->get(array(
                'fields' => 'groupbuy.group_id',
                'join' => 'be_join',
                'conditions' => "order_id = {$order_info['order_id']} ",
                )
            );
            $order_info['group_id'] = $groupbuy['group_id'];
        }
        foreach ($order_detail['data']['goods_list'] as $key => $goods)
        {
            if (substr($goods['goods_image'], 0, 7) != 'http://')
            {
                $order_detail['data']['goods_list'][$key]['goods_image'] = SITE_URL . '/' . $goods['goods_image'];
            }
        }
        $this->assign('order', $order_info);
        $this->assign($order_detail['data']);
        $this->display('order.view.html');
    }
	
	function export_csv()
	{
		$conditions = '1=1';
		if ($_GET['id'] != '') {
            $ids = explode(',', $_GET['id']);
			$conditions .= ' AND order_alias.order_id' . db_create_in($ids);
        }
		if ($_GET['query'] != '') 
		{
			$conditions .= " AND ".$_GET['qtype']." like '%" . $_GET['query'] . "%'";
		}
        $model_order =& m('order');
        $orders = $model_order->findAll(array(
            'conditions'    => $conditions,
			'join'          => 'has_orderextm',
            'order'         => "add_time desc",
			'include'       => array(
                'has_ordergoods',   //取出订单商品
            ),
        )); 
		if(!$orders) {
			$this->show_warning('no_such_order');
            return;
		}
		
		/* xls文件数组 */
		$record_xls = array();		
		$record_title = array(
			'seller_name' 		=> 	'店铺名称',
    		'order_sn' 		=> 	'订单编号',
    		'add_time' 		=> 	'下单时间',
    		'buyer_name' 		=> 	'买家名称',
    		'order_amount' => 	'订单总额',
    		'payment_name' 	=> 	'付款方式',
			'name' => '收货人姓名',
    		'buyer_addr' 	=> 	'地址',
			'buyer_phone' => '电话',
			'pay_message'		=>	'买家留言',
			'status'		=>	'订单状态',
			'invoice_no'		=>	'快递单号',
			'postscript'		=>	'备注',
			'goods'		=>	'商品信息',
		);
		$folder = 'order_'.local_date('Ymdhis', gmtime());
		$record_xls[] = $record_title;
		$amount = 0;
		foreach($orders as $key=>$order)
    	{
			$record_value['seller_name']	=	$order['seller_name'];
			$record_value['order_sn']	=	$order['order_sn'];
			$record_value['add_time']	=	local_date('Y/m/d H:i:s',$order['add_time']);
			$record_value['buyer_name']	=	$order['buyer_name'];
			$record_value['order_amount']	=	$order['order_amount'];
			$record_value['payment_name']	=	$order['payment_name'];
			$record_value['name']	=	$order['consignee'];
			$record_value['buyer_addr']	=	$order['region_name'].$order['address'];
			$record_value['buyer_phone']	=	$order['phone_mob'];
			$record_value['pay_message']   =   $order['pay_message'];
			$record_value['status']	=	order_status($order['status']);
			$record_value['invoice_no']	=	$order['invoice_no'];
			$record_value['postscript']	=	$order['postscript'];
			$record_value['goods'] = '';
			foreach($order['order_goods'] as $ordergoods)
			{
				$record_value['goods'] .= '商品名称：'.$ordergoods['goods_name'].',价格：'.$ordergoods['price'].',数量：'.$ordergoods['quantity'].'；';
			}
        	$record_xls[] = $record_value;
			$amount += $order['order_amount'];
    	}
		$record_xls[] = array('订单总数:',count($orders).'笔','订单总额:',$amount.'元');
		import('excelwriter.lib');
		$ExcelWriter = new ExcelWriter(CHARSET, $folder);
		$ExcelWriter->add_array($record_xls);
		$ExcelWriter->output();
	}
}
?>
