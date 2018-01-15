<?php

class My_distributionApp extends StoreadminbaseApp
{
	var $_appid;
	var $_appmarket_mod;
    var $_store_id;
    var $_store_mod;
	var $_goods_mod;
	var $_distribution_mod;

    function __construct()
    {
        $this->My_distributionApp();
    }
    function My_distributionApp()
    {
        parent::__construct();
		$this->_appid     = 'distribution';
        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_store_mod =& m('store');
		$this->_goods_mod =&m('goods');
		$this->_distribution_mod =&m('distribution');
		$this->_appmarket_mod = &m('appmarket');
    }
	
	function index()
    {
		$_GET['real_name'] && $conditions = " AND real_name like '%".$_GET['real_name']."%'";
		$page = $this->_get_page(10);
		$deposit_trade_mod = &m('deposit_trade');
		$teams = $this->_distribution_mod->find(array(
			'conditions' =>"store_id=".$this->_store_id.$conditions,
			'limit' => $page['limit'],
			'count' => true,
		));
		foreach($teams as $key=>$team)
		{
			$member_mod = &m('member');
			$member = $member_mod->get($team['user_id']);
			$teams[$key]['user_name'] = $member['user_name'];
			$amount = $deposit_trade_mod->get(array(
					'conditions' => "flow='income' AND bizIdentity='".TRADE_FX."' AND buyer_id=".$team['user_id'], 
					'fields' => 'sum(amount) as amount',
			));
			$teams[$key]['amount'] = $amount['amount'];
		}
		$page['item_count'] = $this->_distribution_mod->getCount();
		$this->_format_page($page);
		$this->assign('page_info',$page);
		$this->assign('teams',$teams);
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('distribution_manage'),'index.php?app=my_distribution', LANG::get('my_distributions'));
        $this->_curitem('distribution_manage');
        $this->_curmenu('my_distributions');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_distributions'));
		
        $this->display('my_distribution.index.html');
    }
	
	function order()
    {
		$conditions = $this->_get_query_conditions(array(
            array(      //按买家名称搜索
                'field' => 'buyer_name',
                'equal' => 'LIKE',
            ),
            array(      //按下单时间搜索,起始时间
                'field' => 'add_time',
                'name'  => 'add_time_from',
                'equal' => '>=',
                'handler'=> 'gmstr2time',
            ),
            array(      //按下单时间搜索,结束时间
                'field' => 'add_time',
                'name'  => 'add_time_to',
                'equal' => '<=',
                'handler'=> 'gmstr2time_end',
            ),
            array(      //按订单号
                'field' => 'order_sn',
				'equal' => 'LIKE',
            ),
        ));
		$page = $this->_get_page(10);
        $model_order =& m('order');
		$member_mod =& m('member');
		$deposit_trade_mod = &m('deposit_trade');
		$order_goods_mod = &m('ordergoods');
		$conditions .= $_GET['did'] ? " AND did=".intval($_GET['did']) :  "	AND did>0";
        $orders = $model_order->findAll(array(
            'conditions'    => 'seller_id='.$this->_store_id.$conditions,
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
			$orders[$key]['distributioner'] = $distribution['real_name'];
			$orders[$key]['goods_quantities'] = count($order['order_goods']);
			$orders[$key]['buyer_info'] = $member_mod->get(array('conditions'=>'user_id='.$order['buyer_id'],'fields'=>'real_name,im_qq,im_aliww,im_msn'));
			$record = $deposit_trade_mod->get(array(
				'conditions' => "bizIdentity='".TRADE_FX."' AND seller_id=".$this->_store_id." AND bizOrderId=".$order['order_sn'],
				'fields' => 'sum(amount) as total_amount',
			));
			$orders[$key]['record_amount'] = $record['total_amount'];
        }
        $page['item_count'] = $model_order->getCount();
		$this->_format_page($page);
		$this->assign('page_info',$page);
		$this->assign('orders',$orders);
        $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('distribution_manage'),'index.php?app=my_distribution', LANG::get('distribution_order'));
        $this->_curitem('distribution_manage');
        $this->_curmenu('distribution_order');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('distribution_order'));
		$this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->display('my_distribution.order.html'); 
    }
	
	function setting()
    {
        if (!IS_POST)
        {
			$store = $this->_store_mod->get(array(
				'conditions' => $this->_store_id,
				'fields' => 'enable_distribution,distribution_1,distribution_2,distribution_3',
			));
            $this->_curlocal(LANG::get('member_center'), 'index.php?app=member', LANG::get('distribution_manage'),'index.php?app=my_distribution',LANG::get('distribution_setting'));
            $this->_curitem('distribution_manage');
            $this->_curmenu('distribution_setting');
            $this->assign('store', $store);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('distribution_manage'));
			$this->assign('appAvailable', $this->_appmarket_mod->getCheckAvailableInfo($this->_appid, $this->_store_id));
			$this->import_resource(array(
				'script' => array(
					array(
						'path' => 'jquery.plugins/jquery.validate.js',
						'attr' => 'charset="utf-8"',
					),
				),
			));
            $this->display('my_distribution.setting.html');
        }
        else
        {
			if(($appAvailable = $this->_appmarket_mod->getCheckAvailableInfo($this->_appid, $this->_store_id)) !== TRUE) {
				$this->show_warning($appAvailable['msg']);
				return;
			}
			
            $data = array(
                'enable_distribution' => $_POST['enable_distribution'],
                'distribution_1'  => $_POST['distribution_1'],
				'distribution_2'  => $_POST['distribution_2'],
				'distribution_3'  => $_POST['distribution_3'],
            );
            $this->_store_mod->edit($this->_store_id, $data);
            $this->show_message('setting_ok');
        }
    }
	
    function _get_member_submenu()
    {
        $menus = array(
			array(
                'name' => 'my_distributions',
                'url'  => 'index.php?app=my_distribution',
            ),
			array(
                'name' => 'distribution_order',
                'url'  => 'index.php?app=my_distribution&act=order',
            ),
            array(
                'name' => 'distribution_setting',
                'url'  => 'index.php?app=my_distribution&act=setting',
            )
        );
		return $menus;
    }
	
	/* 取得本店所有商品分类 */
    function _get_sgcategory_options()
    {
        $mod =& bm('gcategory', array('_store_id' => $this->_store_id));
        $gcategories = $mod->get_list();
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree->getOptions();
    }
}

?>
