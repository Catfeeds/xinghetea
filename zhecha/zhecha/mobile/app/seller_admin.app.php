<?php

require(ROOT_PATH . '/app/member.app.php');

class Seller_adminApp extends MemberApp
{
    function __construct()
    {
        $this->Seller_adminApp();
    }
    function Seller_adminApp()
    {
        parent::__construct();
		$this->_get_member_role();
    }
    function index()
    {
        /* 清除新短消息缓存 */
        $cache_server =& cache_server();
        $cache_server->delete('new_pm_of_user_' . $this->visitor->get('user_id'));

        $user = $this->visitor->get();
        $user_mod =& m('member');
        $info = $user_mod->get_info($user['user_id']);
        $user['portrait'] = portrait($user['user_id'], $info['portrait'], 'middle');
		
		/* 在用户中心显示积分 */
		$integral_mod = &m('integral');
		if($integral_mod->_get_sys_setting('integral_enabled'))
		{
			$this->assign('integral_enabled',1);
			$integral = $integral_mod->get($this->visitor->get('user_id'));
			$user['integral'] = $integral['amount'];
		}
		
		$user['count_collect_goods'] = $this->_count_collect_goods();
		$user['count_collect_store'] = $this->_count_collect_store();
		
        $this->assign('user', $user);

        /* 店铺信用和好评率 */
        if ($user['has_store'])
        {
            $store_mod =& m('store');
            $store = $store_mod->get_info($user['has_store']);
            $step = intval(Conf::get('upgrade_required'));
            $step < 1 && $step = 5;
            $store['credit_image'] = $this->_view->res_base . '/images/' . $store_mod->compute_credit($store['credit_value'], $step);
			$store['avg_evaluation']= round(($store['avg_goods_evaluation']+$store['avg_service_evaluation']+$store['avg_shipped_evaluation'])/3,2);
			$store['evaluation_rate']= (round($store['avg_evaluation'] / 5,2)*100).'%';
			$store['industy_compare']=Psmb_init()->get_industry_avg_evaluation($user['has_store']);
            $this->assign('store', $store);
            $this->assign('store_closed', STORE_CLOSED);
        }

        $order_mod =& m('order');

        /* 卖家提醒：待处理订单和待发货订单 */
        if ($user['has_store'])
        {

            $sql7 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_SUBMITTED . "'";
            $sql8 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_ACCEPTED . "'";
			$sql9 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_SHIPPED . "'";
        	$sql10 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE seller_id = '{$user['user_id']}' AND status = '" . ORDER_FINISHED . "' AND evaluation_status = 0";
            $seller_stat = array(
                'submitted' => $order_mod->getOne($sql7),
                'accepted'  => $order_mod->getOne($sql8),
                'shipped'  => $order_mod->getOne($sql9),
            	'finished' => $order_mod->getOne($sql10),
				'refund'   => $this->_count_receive_refund()
            );

            $this->assign('seller_stat', $seller_stat);
        }


        /* 待审核提醒 */
        if ($user['state'] != '' && $user['state'] == STORE_APPLYING)
        {
            $this->assign('applying', 1);
        }
		
		
        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    url('app=member'),
                         LANG::get('overview'));

        /* 当前用户中心菜单 */
        $this->_curitem('overview');
        $this->_config_seo('title', Lang::get('member_center'));
        $this->display('member.index.html');
    }
	
	function _count_receive_refund()
	{
		$refund_mod = &m('refund');
		$refunds = $refund_mod->find(array(
			'conditions'	=> "seller_id=".$this->visitor->get('user_id')." AND status NOT IN ('SUCCESS','CLOSED')",
			'fields'        => 'refund_id'
		));
		
		return count($refunds);
	}
	
	function _count_collect_goods()
    {
        $model_goods =& m('goods');
		$collect_goods = $model_goods->find(array(
            'join'  => 'be_collect,belongs_to_store,has_default_spec',
            'fields'=> 'g.goods_id',
            'conditions' => 'collect.user_id = ' . $this->visitor->get('user_id'),
        ));
        
		return count($collect_goods);
    }
	

    function _count_collect_store()
    {
        $model_store =& m('store');
        $collect_store = $model_store->find(array(
            'join'  => 'be_collect,belongs_to_user',
            'fields'=> 's.store_id',
            'conditions' => 'collect.user_id = ' . $this->visitor->get('user_id'),
        ));
       
	    return  count($collect_store);
    }
	
	function _get_member_role()
	{
        if (!$this->visitor->has_login)
        {
			header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode(get_domain() . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));
			return;
		}
		if(!$this->visitor->get('has_store')){
			$this->show_warning('has_no_store');
			exit;
		}
		$_SESSION['member_role'] = 'seller_admin';
	}
}

?>
