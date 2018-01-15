<?php

/**
 *    Desc
 *
 *    @author    Garbin
 *    @usage    none
 */
class MemberApp extends MemberbaseApp
{
	var $_member_mod;
	var $_bind_mod;
    var $_feed_enabled = false;
	
    function __construct()
    {
        $this->MemberApp();
    }
    function MemberApp()
    {
        parent::__construct();
        $ms =& ms();
        $this->_feed_enabled = $ms->feed->feed_enabled();
        $this->assign('feed_enabled', $this->_feed_enabled);
		$this->_member_mod = &m('member');
		$this->_bind_mod = &m('member_bind');
    }
    function index()
    {
        $user = $this->visitor->get();
        
        $info = $this->_member_mod->get_info($user['user_id']);
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
            $this->assign('store', $store);
        }
		
		 /* 待审核提醒 */
        if ($user['state'] != '' && $user['state'] == STORE_APPLYING)
        {
			$store_mod =& m('store');
            $store = $store_mod->get(array('conditions'=>'store_id='.$this->visitor->get('user_id'), 'fields'=>'apply_remark'));
			$this->assign('apply_remark', $store['apply_remark']);
            $this->assign('applying', 1);
        }
		
		$order_mod = &m('order');
		
		$sql1 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_PENDING . "'";
		$sql2 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_ACCEPTED . "'";
        $sql3 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_SHIPPED . "'";
        $sql4 = "SELECT COUNT(*) FROM {$order_mod->table} WHERE buyer_id = '{$user['user_id']}' AND status = '" . ORDER_FINISHED . "' AND evaluation_status = 0";
		
		$buyer_stat = array(
            'pending'  => $order_mod->getOne($sql1),
			'accepted'  => $order_mod->getOne($sql2),
            'shipped'  => $order_mod->getOne($sql3),
            'finished' => $order_mod->getOne($sql4),
			'refund'   => $this->_count_refund()
        );

        $this->assign('buyer_stat', $buyer_stat);
		
		
		$goodsqa_mod = & m('goodsqa');
        $groupbuy_mod = & m('groupbuy');

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
	
		$this->_get_curlocal_title('member_center');
		
		/* 当前用户中心菜单 */
        $this->_curitem('overview');
        $this->_config_seo('title', Lang::get('member_center'));
        $this->display('member.index.html');
	}
	
	//签到送积分
	function sign_in_integral()
	{
		$user_id = $this->visitor->get('user_id');
		
		$integral_log_mod = &m('integral_log');
		$log = $integral_log_mod->get(array('conditions'=>"type ='sign_in_integral' AND user_id = ".$user_id,'order'=>'add_time desc'));
		
		if(date('Ymd', gmtime()) == date('Ymd', $log['add_time']))
		{
			$this->json_error('you_have_got_integral_for_sign_in');
			return;
		}
		
		$integral_mod=&m('integral');
		$data = array(
			'user_id' => $user_id,
			'type'    => 'sign_in_integral',
			'amount'  => $integral_mod->_get_sys_setting('sign_in_integral')
		);
	    $integral_mod->update_integral($data);
		
		$new_amount = $integral_mod->get($user_id);
		
		$this->json_result(array('amount'=>$new_amount['amount']),sprintf(Lang::get('success_get_integral_for_sign_in'),$integral_mod->_get_sys_setting('sign_in_integral')));
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
	
	function _count_refund()
	{
		$refund_mod = &m('refund');
		$refunds = $refund_mod->find(array(
			'conditions'	=> "buyer_id=".$this->visitor->get('user_id')." AND status NOT IN ('SUCCESS','CLOSED')",
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

    /**
     *    注册一个新用户
     *
     *    @author    Garbin
     *    @return    void
     */
    function register()
    {
        $this->show_warning('暂不开放注册');return ;
		$msg_setting_mod = &m('msg_setting');
		if($setting = $msg_setting_mod->get('')){
			$setting['msg_status'] = unserialize($setting['msg_status']);
		}
		
        if (!IS_POST)
        {
			if ($this->visitor->has_login)
        	{
            	// 注册页面注册后跳转到这个页面，避免出现您已经登录了的提示， 所以直接跳转到用户中心
            	$this->show_message('has_login', '', url('app=member'));

            	return;
        	}
            if (!empty($_GET['ret_url']))
            {
                $ret_url = trim($_GET['ret_url']);
            }
            else
            {
                if (isset($_SERVER['HTTP_REFERER']))
                {
                    $ret_url = $_SERVER['HTTP_REFERER'];
                }
                else
                {
                    $ret_url = SITE_URL . '/index.php';
                }
            }
            $this->assign('ret_url', $ret_url);
            $this->_curlocal(LANG::get('user_register'));
            $this->_config_seo('title', Lang::get('user_register') . ' - ' . Conf::get('site_title'));

            if (Conf::get('captcha_status.register'))
            {
                $this->assign('captcha', 1);
            }
			
			if ($setting['msg_status']['register'])
        	{
           		$this->assign('phone_captcha', 1);
        	}
			
			$this->_get_curlocal_title('user_register');
			
			// 通过此来限制机器恶意批量发送短信
			$_SESSION['_sendcode_ing'] = TRUE;
			
            $this->display('member.register.html');
        }
        else
        {
			if ($this->visitor->has_login)
        	{
            	$this->json_error('has_login');

            	return;
       		}
            if (!$_POST['agree'])
            {
                $this->json_error('agree_first');

                return;
            }
			
            /* 注册并登陆 */
            $user_name = trim($_POST['user_name']);
            $password  = $_POST['password'];
            $email     = trim($_POST['email']);
			$phone_mob = trim($_POST['phone_mob']);
			$check_code = trim($_POST['check_code']);
            $passlen = strlen($password);
            $user_name_len = strlen($user_name);
			
			if(empty($user_name)) {
				$this->json_error('user_name_required');
				return;
			}
			
            if ($user_name_len < 3 || $user_name_len > 25)
            {
                $this->json_error('user_name_length_error');

                return;
            }
			
			$ms =& ms(); //连接用户中心
			if(!$ms->user->check_username($user_name))
			{
				$this->json_error('user_name_existed');

                return;
			}
			
            if ($passlen < 6 || $passlen > 20)
            {
                $this->json_error('password_length_error');

                return;
            }
			
			if ($_POST['password'] != $_POST['password_confirm'])
            {
                /* 两次输入的密码不一致 */
                $this->json_error('inconsistent_password');
                return;
            }
			
            if (!is_email($email))
            {
                $this->json_error('email_error');

                return;
            }
			
			if(!$ms->user->check_email($email)) {
				$this->json_error('email_exists');
				return;
			}
			
			if(!is_mobile($phone_mob)){
				$this->json_error('phone_mob_invalid');
				return;
			}
			if(!$ms->user->check_phone($phone_mob)) {
				$this->json_error('phone_mob_existed');
				return;
			}
			
			if($setting['msg_status']['register'] && (empty($check_code) || md5($phone_mob.$check_code) != $_SESSION['phone_code']))
		    {
				$this->json_error('phone_code_check_failed');
				return;
			}
			if($setting['msg_status']['register'] && (!isset($_SESSION['last_send_time_phone_code']) || (gmtime()-$_SESSION['last_send_time_phone_code'])>120))
			{
				$this->json_error('err_check_code_timeout');
				return;	
			}
			
			 if (Conf::get('captcha_status.register') && base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
            {
                $this->json_error('captcha_failed');
                return;
            }
			
            $user_id = $ms->user->register($user_name, $password, $email, array('phone_mob' => $phone_mob));

            if (!$user_id)
            {
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
            }
            $this->_hook('after_register', array('user_id' => $user_id));
			
            //登录
            $this->_do_login($user_id);
            
            /* 同步登陆外部系统 */
            $synlogin = $ms->user->synlogin($user_id);

            #TODO 可能还会发送欢迎邮件
			$this->json_result('', Lang::get('register_successed') . $synlogin);
        }
    }
	
	/* 第三方账户登录的用户绑定 */
	function bind()
	{
		if ($this->visitor->has_login)
        {
            $this->show_warning('has_login');

            return;
        }
	
		if(!isset($_SESSION['bind']['unionid']) || $_SESSION['bind']['unionid'] == '')
		{
			$this->show_warning('session_expire');
			return;
		}
		
		/* 进入绑定界面，10分钟有效期 */
		if(!isset($_SESSION['bind']['bind_expire_time']) || ($_SESSION['bind']['bind_expire_time'] < gmtime())){
			$this->show_warning('session_expire');
			return;
		}
		
		if(!IS_POST)
		{
			/* 配置seo信息 */
        	$this->_config_seo(array('title' => Lang::get('member_bind') . ' - ' . Conf::get('site_title')));
			$this->_get_curlocal_title('member_bind');
			
			// 通过此来限制机器恶意批量发送短信
			$_SESSION['_sendcode_ing'] = TRUE;
			
			$this->display('member.bind.html');
		}
		else
		{	
			$data = array();
			
			$unionid 	= $_SESSION['bind']['unionid'];
			
			// 只有微信才有openid
			$openid 	= $_SESSION['bind']['openid'];
			$app		= $_SESSION['bind']['app'];
			$phone_mob 	= trim($_POST['phone_mob']);
			$email    	= trim($_POST['email']);
			$password  	= trim($_POST['password']);
			
			$codeType 	= (!empty($_GET['codeType']) && in_array($_GET['codeType'], array('phone', 'email'))) ? trim($_GET['codeType']) : 'phone';
			$code      	= trim($_POST['code']);
		
			$ms =& ms();
			if($codeType == 'email')
			{
				if(!is_email($email)) {
					$this->show_warning('email_invalid');
					return;
				}
				
				/*  检查Email是否被注册过 */
				if($member = $this->_member_mod->get(array('conditions' => 'email="'.$email.'"', 'fields' => 'user_id, user_name, password'))){
					if(!$ms->user->auth($member['user_name'], $password)) {
						$this->show_warning('err_orig_password');
						return;
					}
					$user_id = $member['user_id'];
				}
				else
				{
					if(($_SESSION['email_code'] != md5($email.$code)) || ($_SESSION['last_send_time_email_code'] + 120 < gmtime())) {
						$this->show_warning('email_code_check_failed');
						return;
					}	
				}
				$data['email'] = $email;
			}
			elseif($codeType == 'phone')
			{
				if(!is_mobile($phone_mob)) {
					$this->show_warning('phone_mob_invalid');
					return;
				}
				
				/* 检查手机号是否被注册过 */
				if($member = $this->_member_mod->get(array('conditions' => 'phone_mob="'.$phone_mob.'"', 'fields' => 'user_id, user_name, password'))) {
					if(!$ms->user->auth($member['user_name'], $password)) {
						$this->show_warning('err_orig_password');
						return;
					}
					$user_id = $member['user_id'];
				}
				else
				{
					if(($_SESSION['phone_code'] != md5($phone_mob.$code)) || ($_SESSION['last_send_time_phone_code'] + 120 < gmtime())) {
						$this->show_warning('phone_code_check_failed');
						return;
					}
				  	$data['phone_mob'] 	= $phone_mob;
				}
			} else {
				exit(0);
			}
			
			/* 如果是绑定新用户，则执行注册 */
			if(!$user_id) 
			{
				do {
					 
					if(isset($_SESSION['bind']['nickname']) && !empty($_SESSION['bind']['nickname'])) {
						$user_name  = $ms->user->check_username($_SESSION['bind']['nickname']) ? $_SESSION['bind']['nickname'] : $_SESSION['bind']['nickname'].mt_rand(10,99);
					} else $user_name  = gmtime() . mt_rand(10,99);
					$password  = mt_rand(1000, 9999);
					if(!$data['email']) $data['email'] = $user_name.'@'.$app.'.com';
					if($_SESSION['bind']['real_name']) $data['real_name'] = $_SESSION['bind']['real_name'];
					if($_SESSION['bind']['portrait']) $data['portrait']   = $_SESSION['bind']['portrait'];
					$user_id = $ms->user->register($user_name, $password, $data['email'], $data);
				} while (!$user_id);
			}
			
			
			// 将绑定信息插入数据库
			$bindData = array('unionid' => $unionid, 'openid' => $openid, 'token' => $token, 'user_id' => $user_id, 'app' => $app);
			
			// 如果存在有绑定，则修改
			if($bind = $this->_bind_mod->get("unionid='{$unionid}' AND app='{$app}'")) {
				$this->_bind_mod->edit($bind['id'], $bindData);
			} 
			// APP中微信登录兼容处理
			elseif($bind = $this->_bind_mod->get("openid='{$openid}' AND app='{$app}'")) {
				$this->_bind_mod->edit($bind['id'], $bindData);
			}
			else{
				$this->_bind_mod->add($bindData);
			}
			
			/* 退出绑定模式 */
			unset($_SESSION['bind']);
			unset($_SESSION['phone_code'], $_SESSION['last_send_time_phone_code'], $_SESSION['email_code'],$_SESSION['last_send_time_email_code']);
			
			// 登录
			$this->_do_login($user_id);
				
			/* 同步登陆外部系统 */
			$synlogin = $ms->user->synlogin($user_id);
				
			$this->show_message(Lang::get('login_successed') . $synlogin,
				'back_index', site_url()
			);	

		}
	}
	
    /**
     *    检查用户是否存在
     *
     *    @author    Garbin
     *    @return    void
     */
    function check_user()
    {
        $user_name = empty($_GET['user_name']) ? null : trim($_GET['user_name']);
        if (!$user_name)
        {
            echo ecm_json_encode(false);

            return;
        }
        $ms =& ms();

        echo ecm_json_encode($ms->user->check_username($user_name));
    }
	
	/* 检查邮箱唯一性 */
	function check_email_info()
    {
        $email = empty($_GET['email']) ? '' : trim($_GET['email']);
        if (!$email)
        {
            echo ecm_json_encode(false);

            return;
        }
        $ms =& ms();

        echo ecm_json_encode($ms->user->check_email($email, $this->visitor->get('user_id')));
    }
	
	/* 检查手机号唯一性 */
	function check_phone_mob()
    {
        $phone_mob = empty($_GET['phone_mob']) ? '' : trim($_GET['phone_mob']);
        if (!$phone_mob)
        {
            echo ecm_json_encode(false);

            return;
        }
		$ms =& ms();
		
        echo ecm_json_encode($ms->user->check_phone($phone_mob, $this->visitor->get('user_id')));
    }
	
	
	function setting()
	{
		$this->_get_curlocal_title('member_setting');
        $this->display('member.setting.html');
	}
	

    /**
     *    修改基本信息
     *
     *    @author    Hyber
     *    @usage    none
     */
    function profile(){

        $user_id = intval($this->visitor->get('user_id'));
		
        if (!IS_POST)
        {
           
            $profile    = $this->_member_mod->get_info($user_id);
            $this->assign('profile',$profile);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_profile'));
			$this->_get_curlocal_title('my_profile');
            $this->display('member.profile.html');
        }
        else
        {
            $data = array(
                'real_name' => $_POST['real_name'],
                'gender'    => $_POST['gender'],
                'birthday'  => $_POST['birthday'],
                'im_qq'     => $_POST['im_qq'],
            );

           
            $this->_member_mod->edit($user_id , $data);
            if ($this->_member_mod->has_error())
            {
				$error = current($this->_member_mod->get_error());
                $this->json_error($error['msg']);
            	return;
            }

            $this->json_result('', 'edit_profile_successed');
        }
    }
    /**
     *    修改密码
     *
     *    @author   psmb
     *    @usage    none
     */
    function password()
	{
        if (!IS_POST)
        {
            $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('edit_password'));
			$this->_get_curlocal_title('edit_password');
            $this->display('member.password.html');
        }
        else
        {
            /* 两次密码输入必须相同 */
            $orig_password      = trim($_POST['orig_password']);
            $new_password       = trim($_POST['new_password']);
            $confirm_password   = trim($_POST['confirm_password']);
		
			if(!$orig_password) {
				$this->json_error('orig_password_empty');
				return;
			}
			
			$ms = & ms ();
			
			// 验证原始密码是否正确
			if (!$ms->user->auth ($this->visitor->get('user_name'), $orig_password))
			{
				$this->json_error('orig_password_not_correct');
				return;
			}
			
            if (!$new_password)
            { 
				$this->json_error('no_new_pass');
			    return;
            }
			
			$passlen = strlen($new_password);
            if ($passlen < 6 || $passlen > 20)
            {
                 $this->json_error('password_length_error');
			   	 return;
            }
			
			/* 两次密码输入必须相同 */
            if ($new_password != $confirm_password)
            {
               $this->json_error('twice_pass_not_match');
			   return;
            }
			
            /* 修改密码 */
            $result = $ms->user->edit($this->visitor->get('user_id'), $orig_password, array('password'  => $new_password));
            if (!$result)
            {
                 /* 修改不成功，显示原因 */
                  $this->json_error('edit_fail');
            	  return;
            }

            $this->json_result('', 'edit_ok');
        }
    }
	
	/**
     *    修改电子邮箱
     *
     *    @author    Hyber
     *    @usage    none
     */
    function email(){
        $user_id = $this->visitor->get('user_id');
        if (!IS_POST)
        {
			$this->_get_curlocal_title('edit_email');
            $this->_config_seo('title', Lang::get('user_center') . ' - ' . Lang::get('edit_email'));
            $this->display('member.email.html');
        }
        else
        {
            $orig_password  = $_POST['orig_password'];
            $email          = isset($_POST['email']) ? trim($_POST['email']) : '';
            if (!$email)
            {
                $this->json_error('email_required');

                return;
            }
            if (!is_email($email))
            {
                $this->json_error('email_error');

                return;
            }

            $ms =& ms();    //连接用户系统
			
			/*  检查Email是否被注册过 */
			if(!$ms->user->check_email($email, $this->visitor->get('user_id'))){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);
                return;
			}
			
			/* 验证密码正确后执行编辑 */
            $result = $ms->user->edit($this->visitor->get('user_id'), $orig_password, array(
                'email' => $email
            ));
            if (!$result)
            {
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);
                return;
            }

            $this->json_result('','edit_email_successed');
        }
    }
	
	
	 /**
     *    修改手机
     *
     *    @author    Hyber
     *    @usage    none
     */
    function phone(){
        $user_id = $this->visitor->get('user_id');
        if (!IS_POST)
        {
			$this->_get_curlocal_title('edit_phone');
			
			$member_info = $this->_member_mod->get($user_id);
			$this->assign('phone_mob',$member_info['phone_mob']);
            $this->display('member.phone.html');
        }
        else
        {
            $orig_password  = $_POST['orig_password'];
            $phone          = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            if (!$phone)
            {
                $this->show_warning('phone_required');

                return;
            }
            if (!is_mobile($phone))
            {
                $this->json_error('err_phone_mob_desc');

                return;
            }
			$ms =& ms();
			
            /*  检查手机是否被注册过 */
			if(!$ms->user->check_phone($phone, $this->visitor->get('user_id'))){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
			}
			
			/* 验证密码正确后执行编辑 */
			$result = $ms->user->edit($this->visitor->get('user_id'), $orig_password, array(
                'phone_mob' => $phone
            ));
	    	if (!$result)
            {
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
            }
			$this->json_result('','edit_phone_successed');
        }
    }
}

?>
