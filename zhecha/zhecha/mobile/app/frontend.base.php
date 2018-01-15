<?php

/**
 *    前台控制器基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class FrontendApp extends ECBaseApp
{
    function __construct()
    {
        $this->FrontendApp();
    }
    function FrontendApp()
    {
		Lang::load(lang_file('common'));
        Lang::load(lang_file(APP));
		
		/* Rewrite Lang for the Mobile client */
        Lang::load(lang_file('mobile/common'));
        Lang::load(lang_file('mobile/' . APP));
        parent::__construct();

        // 判断商城是否关闭
        if (!Conf::get('site_status'))
        {
            $this->show_warning(Conf::get('closed_reason'));
            exit;
        }
        # 在运行action之前，无法访问到visitor对象
    }
    function _config_view()
    {
        parent::_config_view();
        $this->_view->template_dir  = ROOT_PATH . '/mobile/themes';
        $this->_view->compile_dir   = ROOT_PATH . '/temp/compiled/mobile/mall';
        $this->_view->res_base      = SITE_URL . '/mobile/themes';
        $this->_config_seo(array(
            'title' => Conf::get('site_title'),
            'description' => Conf::get('site_description'),
            'keywords' => Conf::get('site_keywords')
        ));
    }
    function display($tpl)
    {
        $cart =& m('cart');
        $this->assign('cart_goods_kinds', $cart->get_kinds(SESS_ID, $this->visitor->get('user_id')));
		
		/* 用于前台判断快递跟踪插件是否安装 */
		$this->assign('enable_express', Psmb_init()->_check_express_plugin());
		
		/* 用户判断是否在微信端 */
		$this->assign('isWeixin', isWeixin());
		$this->assignDid();
		
        /* 新消息 */
        $this->assign('new_message', isset($this->visitor) ? $this->_get_new_message() : '');
		
		$this->assign('header_gcategories', array('gcategories' => $this->get_header_home_gcategories()));
		
        $this->assign('site_title', Conf::get('site_title'));
        $this->assign('site_logo', Conf::get('site_logo'));
        $this->assign('statistics_code', Conf::get('statistics_code')); // 统计代码
        $current_url = explode('/', $_SERVER['REQUEST_URI']);
        $count = count($current_url);
        $this->assign('current_url',  $count > 1 ? $current_url[$count-1] : $_SERVER['REQUEST_URI']);// 用于设置导航状态(以后可能会有问题)
        parent::display($tpl);
	}
	
	function get_header_home_gcategories() 
	{
		$data = array();
		
		// 首页的分类数据调用的是挂件
		if(APP == 'default' && ACT == 'index') {	
			
			$cache_server =& cache_server();
        	$key = 'mobile_header_gcategories';
        	$data = $cache_server->get($key);
        	if($data === false)
        	{
				$gcategory_mod = &bm('gcategory', array('_store_id' => 0));
				$data = $gcategory_mod->find(array('conditions'=>'parent_id=0 AND if_show=1', 'fields'=>'cate_id as id, cate_name as value', 'order' => 'sort_order ASC, id ASC'));
				$cache_server->set($key, $data, 86400);
			}
		}
		return $data;
	}
	
    function login()
    { 
        if (!IS_POST)
        {
			if ($this->visitor->has_login)
        	{
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
            /* 防止登陆成功后跳转到登陆、退出的页面 */
            // 真实的跳转地址不能转成小写，因为URL地址区别大小写           
            if (str_replace(array('act=login', 'act=logout',), '', strtolower($ret_url)) != strtolower($ret_url))
            {
                $ret_url = SITE_URL . '/index.php';
            }

            if (Conf::get('captcha_status.login'))
            {
                $this->assign('captcha', 1);
            }
            
            $this->_curlocal(LANG::get('user_login'));
            $this->_config_seo('title', Lang::get('user_login') . ' - ' . Conf::get('site_title'));
			$this->assign('ret_url', rawurlencode($ret_url));// H5端必须加 rawurlencode，如果不加，讲获取不到&后面的参数
			$this->_get_curlocal_title('user_login');
			
            $this->display('login.html');
			
            /* 同步退出外部系统 */
            if (!empty($_GET['synlogout']))
            {
                $ms =& ms();
                echo $synlogout = $ms->user->synlogout();
            }
        }
        else
        {
			if ($this->visitor->has_login)
        	{
				$this->json_error('has_login');
            	return;
       	 	}
		
            $user_name = trim($_POST['user_name']);
            $password  = trim($_POST['password']);
			
			if(!$user_name) {
				$this->json_error('user_name_required');
				return;
			}
			if(!$password) {
				$this->json_error('password_required');
				return;
			}
			if (Conf::get('captcha_status.login') && base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
            {
                $this->json_error('captcha_failed');

                return;
            }
	

            $ms =& ms();
            $user_id = $ms->user->auth($user_name, $password);
            if (!$user_id)
            {
				$error = current($ms->user->get_error());
                
				/* 未通过验证，提示错误信息 */
				$this->json_error($error['msg']);
				return;
            }
            else
            {
				/* 记住密码，保存cookie*/
				if($_POST['AutoLogin'] && intval($_POST['AutoLogin']) == 1) {
					$this->setAutoLoginCookie($user_id, $user_name, $password);
				}
				
                /* 通过验证，执行登陆操作 */
                $this->_do_login($user_id);

                /* 同步登陆外部系统 */
                $synlogin = $ms->user->synlogin($user_id);
				
				$this->json_result('', 'login_successed');
            }
        }
    }
    function logout()
    {
        $this->visitor->logout();
		
		/* 退出COOKIE登录 */
		$this->setAutoLoginCookie('', '', '', time() - 3600);
		
        /* 跳转到登录页，执行同步退出操作 */
        header("Location: index.php?app=member&act=login&synlogout=1");
        return;
    }

    /* 执行登录动作 */
    function _do_login($user_id)
    {
        $mod_user =& m('member');

        $user_info = $mod_user->get(array(
            'conditions'    => "user_id = '{$user_id}'",
            'join'          => 'has_store',                 //关联查找看看是否有店铺
            'fields'        => 'user_id, user_name, reg_time, last_login, last_ip, store_id, locked',
        ));
		
		// 如果用户被锁定，则不能登陆（前台不能加 privs 限制，因无此参数）
		if(isset($user_info['locked']) && $user_info['locked'])
		{
			$this->json_error('your_account_has_locked');
			exit;
		}

        /* 店铺ID */
        $my_store = empty($user_info['store_id']) ? 0 : $user_info['store_id'];

        /* 保证基础数据整洁 */
        //unset($user_info['store_id']);

        /* 分派身份 */
        $this->visitor->assign($user_info);

        /* 更新用户登录信息 */
        $mod_user->edit("user_id = '{$user_id}'", "last_login = '" . gmtime()  . "', last_ip = '" . real_ip() . "', logins = logins + 1");
		
		/* 如果还没有创建预存款账户，且系统启动了自动创建，则自动创建 */
		$deposit_account_mod = &m('deposit_account');
		$deposit_account_mod->_create_deposit_account($user_id);

        /* 更新购物车中的数据 */
        $mod_cart =& m('cart');
        $mod_cart->edit("(user_id = '{$user_id}' OR session_id = '" . SESS_ID . "') AND store_id <> '{$my_store}'", array(
            'user_id'    => $user_id,
            'session_id' => SESS_ID,
        ));

        /* 去掉重复的项 */
        $cart_items = $mod_cart->find(array(
            'conditions'    => "user_id='{$user_id}' GROUP BY spec_id",
            'fields'        => 'COUNT(spec_id) as spec_count, spec_id, rec_id',
        ));
        if (!empty($cart_items))
        {
            foreach ($cart_items as $rec_id => $cart_item)
            {
                if ($cart_item['spec_count'] > 1)
                {
                    $mod_cart->drop("user_id='{$user_id}' AND spec_id='{$cart_item['spec_id']}' AND rec_id <> {$cart_item['rec_id']}");
                }
            }
        }
    }
	
	function setCookieDid($did,$store_id,$expire=0)
	{
		if(!$expire)  $expire = time() + 3600;
		setcookie('CookieDid', json_encode(array('did' => $did, 'store_id' => $store_id)), $expire,  "/");
	}
	
	function getCookieDid()
	{
		if(isset($_COOKIE['CookieDid']) && trim($_COOKIE['CookieDid']) <> '')
		{
			return json_decode(stripslashes($_COOKIE['CookieDid']), true);			
		}
	}
	
	function assignDid()
	{
		/* 
		if($cookie = $this->getCookieDid())
		{
			$this->assign('did',$cookie['did']);
			$this->assign('store_id',$cookie['store_id']);
		}
		*/
		$distribution_mod =&m('distribution');
		$user_id = $this->visitor->get('user_id');
		if($user_id && $distribution = $distribution_mod->get('user_id='.$user_id))
		{
			$this->assign('malljoin',-1);//已经加入分销，前台页面显示分销中心
		}
	}
	
	function code()
	{
		$did = isset($_GET['did'])?intval($_GET['did']):0;
		$distribution_mod = &m('distribution');
		$distribution = $distribution_mod->get('did='.$did);
		if(!$did || !$distribution)
		{
			$this->show_warning('error');
            return;
		}
		$store_mod = &m('store');
		$store = $store_mod->get(array('conditions'=>$distribution['store_id'],'fields'=>'store_name'));
		$distribution['store_name'] = $store['store_name'];
		if(empty($distribution['logo']))
		{
			$member_mod = &m('member');	
			$member = $member_mod->get($distribution['user_id']);
			$distribution['user_name'] = $member['user_name'];
			$distribution['logo'] = portrait($distribution['user_id'], $member['portrait'], 'middle');
		}
		$distribution['code_img'] = $this->gendtbQRCode('dtb_qrcode',array('store_id'=>$distribution['store_id'],'did'=>$did));
		$this->assign('distribution',$distribution);
		$this->_get_curlocal_title('my_code');
		$this->_config_seo('title', Lang::get('distribution_center') . ' - ' . Lang::get('my_code'));
		$this->display('dcenter.code.html');
	}
	

    /* 取得导航 */
    function _get_navs()
    {
        $cache_server =& cache_server();
        $key = 'common.navigation';
        $data = $cache_server->get($key);
        if($data === false)
        {
            $data = array(
                'header' => array(),
                'middle' => array(),
                'footer' => array(),
            );
            $nav_mod =& m('navigation');
            $rows = $nav_mod->find(array(
                'order' => 'type, sort_order',
            ));
            foreach ($rows as $row)
            {
                $data[$row['type']][] = $row;
            }
            $cache_server->set($key, $data, 86400);
        }

        return $data;
    }

    /**
     *    获取JS语言项
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function jslang($lang = '')
    {
        $lang = array_merge(Lang::fetch(lang_file('jslang')), Lang::fetch(lang_file('mobile/jslang')));
        parent::jslang($lang);
    }

    /**
     *    视图回调函数[显示小挂件]
     *
     *    @author    Garbin
     *    @param     array $options
     *    @return    void
     */
    function display_widgets($options)
    {
        $area = isset($options['area']) ? $options['area'] : '';
        $page = isset($options['page']) ? $options['page'] : '';
        if (!$area || !$page)
        {
            return;
        }
        include_once(ROOT_PATH . '/includes/widget.base.php');

        /* 获取该页面的挂件配置信息 */
        $widgets = get_widget_config($this->_get_template_name(), $page);

        /* 如果没有该区域 */
        if (!isset($widgets['config'][$area]))
        {
            return;
        }

        /* 将该区域内的挂件依次显示出来 */
        foreach ($widgets['config'][$area] as $widget_id)
        {
            $widget_info = $widgets['widgets'][$widget_id];
            $wn     =   $widget_info['name'];
            $options=   $widget_info['options'];

            $widget =& widget($widget_id, $wn, $options);
            $widget->display();
        }
    }

    /**
     *    获取当前使用的模板名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_template_name()
    {
        return 'default';
    }

    /**
     *    获取当前使用的风格名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_style_name()
    {
        return 'default';
    }

    /**
     *    当前位置
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function _curlocal($arr)
    {
        $curlocal = array(array(
            'text'  => Lang::get('index'),
            'url'   => SITE_URL . '/index.php',
        ));
        if (is_array($arr))
        {
            $curlocal = array_merge($curlocal, $arr);
        }
        else
        {
            $args = func_get_args();
            if (!empty($args))
            {
                $len = count($args);
                for ($i = 0; $i < $len; $i += 2)
                {
                    $curlocal[] = array(
                        'text'  =>  $args[$i],
                        'url'   =>  $args[$i+1],
                    );
                }
            }
        }

        $this->assign('_curlocal', $curlocal);
    }
    function _init_visitor()
    {
        $this->visitor =& env('visitor', new UserVisitor());
    }
	function _get_curlocal_title($title)
	{
		$this->assign('curlocal_title',Lang::get($title));
	}
	/**
	 *    获取会员等级价格配置
	 *
	 *    @author    PwordC
	 *    @param     $name 参数名称
	 *    @return    $name 为空是为数组
	 */
	function _get_vip_price_setting($name='')
	{
	    $store_setting =& m('store_setting');
	    $vip_price_setting = $store_setting->get("appid='vip_price'");
	    if ($name ==''){
	        return $vip_price_setting;
	    }else {
	        return $vip_price_setting[$name];
	    }
	}
	/**
	 *    获取用户权限
	 *
	 *    @author    PwordC
	 *    @param
	 *    @return   0为普通商城用户；其他为等级用户
	 */
	function _get_member_type(){
	    $sys_user_id = $this->visitor->get('sys_user_id');
	    if ($sys_user_id == 0){
	        return 0;
	    }else {
	        $member_type_mod =& m('member_type');
	        $member_type_info = $member_type_mod->get("user_id={$sys_user_id}");
	        $member_type = $member_type_info['user_type'];
	        return $member_type;
	    }
	
	}
	/**
	 *    获取pv参数配置
	 *
	 *    @author    PwordC
	 *    @param
	 *    @return
	 */
	function _get_pv_setting($name=''){
	    $store_setting_mod =& m('store_setting');
	    $pv_setting_info = $store_setting_mod->get("appid='pv'");
	    if ($name ==''){
	        return $pv_setting_info;
	    }else {
	        return $pv_setting_info[$name];
	    }
	}
	/**
	 *    获取是否允许添加产品参数配置
	 *
	 *    @author    PwordC
	 *    @param
	 *    @return
	 */
	function _get_add_goods_setting($name=''){
	    $store_setting_mod =& m('store_setting');
	    $pv_setting_info = $store_setting_mod->get("appid='add_goods'");
	    if ($name ==''){
	        return $pv_setting_info;
	    }else {
	        return $pv_setting_info[$name];
	    }
	}
}
/**
 *    前台访问者
 *
 *    @author    Garbin
 *    @usage    none
 */
class UserVisitor extends BaseVisitor
{
    var $_info_key = 'user_info';

    /**
     *    退出登录
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function logout()
    {
        /* 将购物车中的相关项的session_id置为空 */
        $mod_cart =& m('cart');
        $mod_cart->edit("user_id = '" . $this->get('user_id') . "'", array(
            'session_id' => '',
        ));
		
        /* 退出登录 */
        parent::logout();
    }
}
/**
 *    商城控制器基类
 *
 *    @author    Garbin
 *    @usage    none
 */
class MallbaseApp extends FrontendApp
{
    function _run_action()
    {
        /* 只有登录的用户才可访问 */
        if (!$this->visitor->has_login && in_array(APP, array('apply')))
        {
            header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode(get_domain() . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));

            return;
        }

        parent::_run_action();
    }

    function _config_view()
    {
        parent::_config_view();

        $template_name = $this->_get_template_name();
        $style_name    = $this->_get_style_name();
		
        $this->_view->template_dir = APP_ROOT . "/themes/mall/{$template_name}";
        $this->_view->compile_dir  = ROOT_PATH . "/temp/compiled/mobile/mall/{$template_name}";
        $this->_view->res_base     = site_url() . "/themes/mall/{$template_name}/styles/{$style_name}";
		$this->_view->lib_base     = dirname(site_url()) . '/includes/libraries/javascript';
    }

    /* 取得支付方式实例 */
    function _get_payment($code, $payment_info)
    {
        include_once(ROOT_PATH . '/includes/payment.base.php');
        include(ROOT_PATH . '/includes/payments/' . $code . '/' . $code . '.payment.php');
        $class_name = ucfirst($code) . 'Payment';

        return new $class_name($payment_info);
    }

    /**
     *   获取当前所使用的模板名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_template_name()
    {
        $template_name = Conf::get('wap_template_name');
        if (!$template_name)
        {
            $template_name = 'default';
        }

        return $template_name;
    }

    /**
     *    获取当前模板中所使用的风格名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_style_name()
    {
        $style_name = Conf::get('wap_style_name');
        if (!$style_name)
        {
            $style_name = 'default';
        }

        return $style_name;
    }
}

/**
 *    购物流程子系统基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class ShoppingbaseApp extends MallbaseApp
{
    function _run_action()
    {
        /* 只有登录的用户才可访问 */
        if (!$this->visitor->has_login && !in_array(ACT, array('login', 'register', 'check_user')))
        {
            if (!IS_AJAX)
            {
				// 加 get_domain() 是针对 www.abc.com/mall这样的站点， 如果没有/mall 则不用加也可以
                header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode(get_domain() . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));

                return;
            }
            else
            {
                $this->json_error('login_please');
                return;
            }
        }

        parent::_run_action();
    }
}

/**
 *    用户中心子系统基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class MemberbaseApp extends MallbaseApp
{
    function _run_action()
    {
        /* 只有登录的用户才可访问 */
        if (!$this->visitor->has_login && !in_array(ACT, array('login', 'register', 'check_user', 'check_email_info','check_phone_mob', 'bind')))
        {
            if (!IS_AJAX)
            {
                header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode(get_domain() . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));

                return;
            }
            else
            {
                $this->json_error('login_please');
                return;
            }
        }
        parent::_run_action();
    }
    /**
     *    当前选中的菜单项
     *
     *    @author    Garbin
     *    @param     string $item
     *    @return    void
     */
    function _curitem($item)
    {
        $this->assign('has_store', $this->visitor->get('has_store'));
		
        $member_menu = $this->_get_member_menu();
		if(!$this->visitor->get('has_store')){
			unset($member_menu['im_seller']);
			$this->assign('member_role', 'buyer_admin');
		} else {
			if($_SESSION['member_role'] == 'buyer_admin') {
				unset($member_menu['im_seller']);
				$this->assign('member_role', 'buyer_admin');
			} else {
				unset($member_menu['im_buyer']);
				$this->assign('member_role', 'seller_admin');
			}
		}
        $this->assign('_member_menu', $member_menu);
        $this->assign('_curitem', $item);
    }
    /**
     *    当前选中的子菜单
     *
     *    @author    Garbin
     *    @param     string $item
     *    @return    void
     */
    function _curmenu($item)
    {
        $_member_submenu = $this->_get_member_submenu();
        foreach ($_member_submenu as $key => $value)
        {
            $_member_submenu[$key]['text'] = $value['text'] ? $value['text'] : Lang::get($value['name']);
        }
        $this->assign('_member_submenu', $_member_submenu);
        $this->assign('_curmenu', $item);
    }
    /**
     *    获取子菜单列表
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function _get_member_submenu()
    {
        return array();
    }
    /**
     *    获取用户中心全局菜单列表
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function _get_member_menu()
    {
        $menu = array();
        /* 我是买家 */
        $menu['im_buyer'] = array(
            'name'  => 'im_buyer',
            'text'  => Lang::get('im_buyer'),
            'submenu'   => array(
                'my_order'  => array(
                    'text'  => Lang::get('my_order'),
					'sub_text'  => Lang::get('view_my_order'),
                    'url'   => 'index.php?app=buyer_order',
                    'name'  => 'my_order',
					'margin'  => 'mb10',
                    'icon'  => 'order.jpg',
                ),
// 				'my_groupbuy'  => array(
//                     'text'  => Lang::get('my_groupbuy'),
//                     'url'   => 'index.php?app=buyer_groupbuy',
//                     'name'  => 'my_groupbuy',
//                     'icon'  => 'groupbuy.jpg',
//                 ),
                'my_question' =>array(
                    'text'  => Lang::get('my_question'),
                    'url'   => 'index.php?app=my_question',
                    'name'  => 'my_question',
                    'icon'  => 'qa.png',

                ),
                'my_favorite'  => array(
                    'text'  => Lang::get('my_favorite'),
                    'url'   => 'index.php?app=my_favorite',
                    'name'  => 'my_favorite',
                    'icon'  => 'collect_goods.png',
                ),
				'my_favorite_store' => array(
					'text'  => Lang::get('my_favorite_store'),
					'url'   => 'index.php?app=my_favorite&type=store',
					'name'  => 'my_favorite_store',
					'icon'  => 'collect_store.jpg',
				),
                'my_address'  => array(
                    'text'  => Lang::get('my_address'),
                    'url'   => 'index.php?app=my_address',
					'margin'  => 'mb10',
                    'name'  => 'my_address',
                    'icon'  => 'address.png',
                ),
				
//                 'my_coupon'  => array(
//                     'text'  => Lang::get('my_coupon'),
//                     'url'   => 'index.php?app=my_coupon',
//                     'name'  => 'my_coupon',
//                     'icon'  => 'coupon.png',
//                 ),
				'my_deposit' => array(
					'text'  => Lang::get('my_deposit'),
					'url'   => 'index.php?app=deposit',
					'name'  => 'my_deposit',
					'icon'  => 'deposit.jpg',
				),
				'refund' => array(
					'text' => Lang::get('refund_apply'),
					'url'  => 'index.php?app=refund',
					'name' => 'refund_apply',
					'margin'  => 'mb10',
					'icon' => 'refund.png',
				),
// 				'dcenter' => array(
// 					'text' => Lang::get('dcenter'),
// 					'url'  => 'index.php?app=dcenter',
// 					'name' => 'dcenter',

// 				),
            ),
        );
		
        if (!$this->visitor->get('has_store') && Conf::get('store_allow'))
        {
            /* 没有拥有店铺，且开放申请，则显示申请开店链接 */
			$menu['overview'] = array(
                'text' => Lang::get('apply_store'),
                'url'  => 'index.php?app=apply',
				'margin'  => 'mb10',
				'name'  => 'apply_store'
            );
        }
        if ($this->visitor->get('manage_store'))
        {
			$menu['im_buyer']['submenu']['im_seller'] = array(
                'text'  => Lang::get('im_seller'),
                'url'   => 'index.php?app=seller_admin',
			    'margin'  => 'mb10',
                'name'  => 'im_seller'
             );
			
            /* 指定了要管理的店铺 */
            $menu['im_seller'] = array(
                'name'  => 'im_seller',
                'text'  => Lang::get('im_seller'),
                'submenu'   => array(),
            );
			$menu['im_seller']['submenu']['order_manage'] = array(
                    'text'  => Lang::get('order_manage'),
                    'url'   => 'index.php?app=seller_order',
                    'name'  => 'order_manage',
					'margin'  => 'mb10',
                    'icon'  => 'order.jpg',
            );
            $menu['im_seller']['submenu']['my_qa'] = array(
                    'text'  => Lang::get('my_qa'),
                    'url'   => 'index.php?app=my_qa',
                    'name'  => 'my_qa',
                    'icon'  => 'qa.png',
            );  
			$menu['im_seller']['submenu']['my_deposit'] = array(
					'text'  => Lang::get('my_deposit'),
					'url'   => 'index.php?app=deposit',
					'name'  => 'my_deposit',
					'icon'  => 'deposit.jpg',
			);       
			$menu['im_seller']['submenu']['refund_manage'] = array(
                    'text'  => Lang::get('refund_manage'),
                    'url'   => 'index.php?app=refund&act=receive',
                    'name'  => 'refund_manage',
                    'icon'  => 'refund.png',
            );
// 			$menu['im_seller']['submenu']['dcenter'] = array(
//                     'text'  => Lang::get('dcenter'),
//                     'url'   => 'index.php?app=dcenter',
//                     'name'  => 'dcenter',
//             );
// 			$menu['im_seller']['submenu']['map'] = array(
//                     'text'  => Lang::get('store_map'),
//                     'url'   => 'index.php?app=my_store&act=map',
//                     'name'  => 'map',
// 					'margin'  => 'mb10',
//                     'icon'  => 'address.png',
//             );
			$menu['im_seller']['submenu']['im_buyer'] = array(
                    'text'  => Lang::get('im_buyer'),
                    'url'   => 'index.php?app=buyer_admin',
                    'name'  => 'im_buyer',
					'margin'  => 'mb10',
                    'icon'  => 'address.png',
            );
        }

        return $menu;
    }
}

/**
 *    店铺管理子系统基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class StoreadminbaseApp extends MemberbaseApp
{
    function _run_action()
    {
        /* 只有登录的用户才可访问 */
        if (!$this->visitor->has_login && !in_array(ACT, array('login', 'register', 'check_user')))
        {
            if (!IS_AJAX)
            {
                header('Location:index.php?app=member&act=login&ret_url=' . rawurlencode(get_domain() . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']));

                return;
            }
            else
            {
                $this->json_error('login_please');
                return;
            }
        }
        $referer = $_SERVER['HTTP_REFERER'];
        if (strpos($referer, 'act=login') === false)
        {
            $ret_url = $_SERVER['HTTP_REFERER'];
            $ret_text = 'go_back';
        }
        else
        {
            $ret_url = SITE_URL . '/index.php';
            $ret_text = 'back_index';
        }

        /* 检查是否是店铺管理员 */
        if (!$this->visitor->get('manage_store'))
        {
            /* 您不是店铺管理员 */
            $this->show_warning(
                'not_storeadmin',
                'apply_now', 'index.php?app=apply',
                $ret_text, $ret_url
            );

            return;
        }

        /* 检查是否被授权 */
        $privileges = $this->_get_privileges();
        if (!$this->visitor->i_can('do_action', $privileges))
        {
            $this->show_warning('no_permission', $ret_text, $ret_url);

            return;
        }

        /* 检查店铺开启状态 */
        $state = $this->visitor->get('state');
        if ($state == 0)
        {
            $this->show_warning('apply_not_agree', $ret_text, $ret_url);

            return;
        }
        elseif ($state == 2)
        {
            $this->show_warning('store_is_closed', $ret_text, $ret_url);

            return;
        }

        /* 检查附加功能 */
        if (!$this->_check_add_functions())
        {
            $this->show_warning('not_support_function', $ret_text, $ret_url);
            return;
        }

        parent::_run_action();
    }
    function _get_privileges()
    {
        $store_id = $this->visitor->get('manage_store');
        $privs = $this->visitor->get('s');

        if (empty($privs))
        {
            return '';
        }

        foreach ($privs as $key => $admin_store)
        {
            if ($admin_store['store_id'] == $store_id)
            {
                return $admin_store['privs'];
            }
        }
    }
    
    /* 获取当前店铺所使用的主题 */
    function _get_theme()
    {
        $model_store =& m('store');
        $store_info  = $model_store->get($this->visitor->get('manage_store'));
        $theme = !empty($store_info['theme']) ? $store_info['theme'] : 'default|default';
        list($curr_template_name, $curr_style_name) = explode('|', $theme);
        return array(
            'template_name' => $curr_template_name,
            'style_name'    => $curr_style_name,
        );
    }

    function _check_add_functions()
    {
        $apps_functions = array( // app与function对应关系
            'seller_groupbuy' => 'groupbuy',
            'coupon' => 'coupon',
        );
        if (isset($apps_functions[APP]))
        {
            $store_mod =& m('store');
            $settings = $store_mod->get_settings($this->_store_id);
            $add_functions = isset($settings['functions']) ? $settings['functions'] : ''; // 附加功能
            if (!in_array($apps_functions[APP], explode(',', $add_functions)))
            {
                return false;
            }
        }
        return true;
    }
}

/**
 *    虚拟币管理子系统基础类
 *
 *    @author    psmb
 *    @usage    none
 */
class DepositbaseApp extends MemberbaseApp
{
	function _run_action()
	{
		$this->assign('has_account', $this->_has_account());
		
		parent::_run_action();
	}
	
	/* 检查用户是否配置过预存款账户 */
	function _has_account()
	{
		$deposit_account_mod = &m('deposit_account');
		$deposit_account = $deposit_account_mod->get(array('conditions'=>'user_id='.$this->visitor->get('user_id')));
		if($deposit_account) {
			return 1;
		}
		return 0;
	}   
}


/**
 *    店铺控制器基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class StorebaseApp extends FrontendApp
{
    var $_store_id;

    /**
     * 设置店铺id
     *
     * @param int $store_id
     */
    function set_store($store_id)
    {
        $this->_store_id = intval($store_id);

        /* 有了store id后对视图进行二次配置 */
        $this->_init_view();
        $this->_config_view();
    }

    function _config_view()
    {
        parent::_config_view();
        $template_name = $this->_get_template_name();
        $style_name    = $this->_get_style_name();

		$this->_view->template_dir = APP_ROOT . "/themes/store/{$template_name}";
        $this->_view->compile_dir  = ROOT_PATH . "/temp/compiled/mobile/store/{$template_name}";
        $this->_view->res_base     = site_url() . "/themes/store/{$template_name}/styles/{$style_name}";
		$this->_view->lib_base     = dirname(site_url()) . '/includes/libraries/javascript';
		
		$wap_template_name = Conf::get('wap_template_name') ? Conf::get('wap_template_name'):'default';
		$wap_style_name = Conf::get('wap_style_name') ? Conf::get('wap_style_name'):'default';
		// 该赋值便于在店铺模板中调用商城模板的CSS,JS路径
		$this->assign('mall_theme_root',  site_url() . '/themes/mall/' . $wap_template_name . '/styles/'. $wap_style_name);
    }

    /**
     * 取得店铺信息
     */
    function get_store_data()
    {
        $cache_server =& cache_server();
        $key = 'function_get_store_data_' . $this->_store_id;
        $store = $cache_server->get($key);
        if ($store === false)
        {
            $store = $this->_get_store_info();
            if (empty($store))
            {
                $this->show_warning('the_store_not_exist');
                exit;
            }
            if ($store['state'] == 2)
            {
                $this->show_warning('the_store_is_closed');
                exit;
            }
            $step = intval(Conf::get('upgrade_required'));
            $step < 1 && $step = 5;
            $store_mod =& m('store');
            $store['credit_image'] = $this->_view->res_base . '/images/' . $store_mod->compute_credit($store['credit_value'], $step);

            empty($store['store_logo']) && $store['store_logo'] = Conf::get('default_store_logo');
            $store['store_owner'] = $this->_get_store_owner();
            $store['store_navs']  = $this->_get_store_nav();
            $goods_mod =& m('goods');
            $store['goods_count'] = $goods_mod->get_count_of_store($this->_store_id);
			
            $store['store_gcates']= $this->_get_store_gcategory();
			if($store['store_gcates'])
			{
				foreach($store['store_gcates'] as $k=>$cate)
				{
					$store['store_gcates'][$k]['count'] = count($cate['children']);
				}
			}
			
            $store['sgrade'] = $this->_get_store_grade('grade_name');
            $functions = $this->_get_store_grade('functions');
            $store['functions'] = array();
            if ($functions)
            {
                $functions = explode(',', $functions);
                foreach ($functions as $k => $v)
                {
                    $store['functions'][$v] = $v;
                }
            }
            $cache_server->set($key, $store, 1800);
        }

        return $store;
    }
    /* 取得店铺信息 */
    function _get_store_info()
    {
        if (!$this->_store_id)
        {
            /* 未设置前返回空 */
            return array();
        }
        static $store_info = null;
        if ($store_info === null)
        {
            $store_mod  =& m('store');
            $store_info = $store_mod->get_info($this->_store_id);
        }

        return $store_info;
    }

    /* 取得店主信息 */
    function _get_store_owner()
    {
        $user_mod =& m('member');
        $user = $user_mod->get($this->_store_id);

        return $user;
    }

    /* 取得店铺导航 */
    function _get_store_nav()
    {
        $article_mod =& m('article');
        return $article_mod->find(array(
            'conditions' => "store_id = '{$this->_store_id}' AND cate_id = '" . STORE_NAV . "' AND if_show = 1",
            'order' => 'sort_order',
            'fields' => 'title',
        ));
    }
    /*  取的店铺等级   */

    function _get_store_grade($field)
    {
        $store_info = $store_info = $this->_get_store_info();
        $sgrade_mod =& m('sgrade');
        $result = $sgrade_mod->get_info($store_info['sgrade']);
        return $result[$field];
    }
    /* 取得店铺分类 */
    function _get_store_gcategory()
    {
        $gcategory_mod =& bm('gcategory', array('_store_id' => $this->_store_id));
        $gcategories = $gcategory_mod->get_list(-1, true);
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree->getArrayList(0);
    }

    /**
     *    获取当前店铺所设定的模板名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_template_name()
    {
        $store_info = $this->_get_store_info();
        $theme = !empty($store_info['wap_theme']) ? $store_info['wap_theme'] : 'default|default';
        list($template_name, $style_name) = explode('|', $theme);
        return $template_name;
    }

    /**
     *    获取当前店铺所设定的风格名称
     *
     *    @author    Garbin
     *    @return    string
     */
    function _get_style_name()
    {
        $store_info = $this->_get_store_info();
        $theme = !empty($store_info['wap_theme']) ? $store_info['wap_theme'] : 'default|default';
        list($template_name, $style_name) = explode('|', $theme);

        return $style_name;
    }
}

/* 实现消息基础类接口 */
class MessageBase extends MallbaseApp {};

/* 实现模块基础类接口 */
class BaseModule  extends FrontendApp {};

/* 消息处理器 */
require(ROOT_PATH . '/eccore/controller/message.base.php');

?>
