<?php

class My_couponApp extends MemberbaseApp 
{
    var $_user_mod;
    var $_store_mod;
    var $_coupon_mod;
    
    function index()
    {
        $page = $this->_get_page(10);
        $this->_user_mod =& m('member');
        $this->_store_mod =& m('store');
        $this->_coupon_mod =& m('coupon');
        $msg = $this->_user_mod->findAll(array(
            'conditions' => 'user_id = ' . $this->visitor->get('user_id'),
            'count' => true,
            'limit' => $page['limit'],
            'include' => array('bind_couponsn' => array())));
        $page['item_count'] = $this->_user_mod->getCount();
        $coupon = array();
        $coupon_ids = array();
        $msg = current($msg);
       if (!empty($msg['coupon_sn']))
       {
           foreach ($msg['coupon_sn'] as $key=>$val)
           {
               $coupon_tmp = $this->_coupon_mod->get(array(
                'fields' => "this.*,store.store_name,store.store_id",
                'conditions' => 'coupon_id = ' . $val['coupon_id'],
                'join' => 'belong_to_store',
                ));
                $coupon_tmp['valid'] = 0;
                $time = gmtime();
                if (($val['remain_times'] > 0) && ($coupon_tmp['end_time'] == 0 || $coupon_tmp['end_time'] > $time))
                {
                    $coupon_tmp['valid'] = 1;
                }
               $coupon[$key] = array_merge($val, $coupon_tmp);
           }
       }

       $this->assign('page_info', $page);          //将分页信息传递给视图，用于形成分页条
       $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('coupon_list'));
	   $this->_get_curlocal_title('my_coupon');
       $this->_format_page($page);
       $this->assign('coupons', $coupon);
       $this->display('my_coupon.index.html');
    }
    
    function bind()
    {
        if (!IS_POST)
        {
            header("Content-Type:text/html;charset=" . CHARSET);
			$this->_get_curlocal_title('bind');
            $this->display('my_coupon.form.html');
        }
        else 
        {
            $coupon_sn = isset($_POST['coupon_sn']) ? trim($_POST['coupon_sn']) : '';
            if (empty($coupon_sn))
            {
                $this->json_error('coupon_sn_not_empty');
				return;
            }
			
            $coupon_sn_mod =&m ('couponsn');
            $coupon = $coupon_sn_mod->get_info($coupon_sn);
            if (empty($coupon))
            {
                $this->json_error('coupon_sn_not_empty_invalid');
                return;
            }
			if(!$coupon_sn_mod->createRelation('bind_user', $coupon_sn, $this->visitor->get('user_id'))) {
				$this->json_error('coupon_bind_fail');
				return;
			}
            $this->json_result('', 'coupon_bind_ok');
        }
    }
    
    function drop()
    {
        if (!isset($_GET['id']) || empty($_GET['id']))
        {
            $this->json_error('drop_fail');
            return;
        }
        $ids = explode(',', trim($_GET['id']));
        $couponsn_mod =& m('couponsn');
        $couponsn_mod->unlinkRelation('bind_user', db_create_in($ids, 'coupon_sn'));
        if ($couponsn_mod->has_error())
        {
			$error = current($couponsn_mod->get_error());
           	$this->json_error($error['msg']);
           	return;
        }
       	$this->json_result('', 'drop_ok');
    }
    
}

?>