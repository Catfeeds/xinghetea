<?php

/* 商品 */
class GoodsApp extends StorebaseApp
{
    var $_goods_mod;
    //用户等级
    var $_member_type;
    function __construct()
    {
        $this->GoodsApp();
    }
    function GoodsApp()
    {
        parent::__construct();
        $this->_goods_mod =& m('goods');
        $this->_member_type = $this->_get_member_type();
    }

    function index()
    {
        /* 参数 id */
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        //增加store_id参数  by PwordC
        $store_id = empty($_GET['store_id']) ? 1 : intval($_GET['store_id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        /* 可缓存数据 */
        $data = $this->_get_common_info($id,$store_id);//增加store_id字段。by PwordC
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }

        /* 更新浏览次数 */
        $this->_update_views($id);

        //是否开启验证码
        if (Conf::get('captcha_status.goodsqa'))
        {
            $this->assign('captcha', 1);
        }
		/*是否开启积分功能*/
		$integral_mod = &m('integral');
		if($integral_mod->_get_sys_setting('integral_enabled'))
		{
			$this->assign('integral_enabled', 1);
		}
		//获取会员等级价格设置状态 by PwordC
		if ($this->_get_vip_price_setting('status') != 0){
		    //获取会员等级价格
		    $this->get_member_type_price();
		}else{
		    $this->assign('vip_price_status_off',1);
		}
		//获取pv设置
		if ($this->_get_pv_setting('status') != 0){
		    $pv_view = $this->_get_pv_setting('config');
		    $pv_view = unserialize($pv_view);
		    if ($pv_view == ''){
		        $pv_view = 'pv';
		    }
		    $this->assign('pv_view',$pv_view);
		}
				
		
        
        //传递store_id到视图
        $this->assign('store_id',$store_id);
	
		$this->assign('props', $this->_get_goods_props($id));
		
		
        $this->assign('guest_comment_enable', Conf::get('guest_comment'));
		$this->_get_curlocal_title('goods_detail');
        $this->display('goods.index.html');
    }
	
	function _get_goods_props($id=0)
	{
		$goods_pvs_mod = &m('goods_pvs');
		$props_mod = &m('props');
		$prop_value_mod = &m('prop_value');
		$goods_pvs = $goods_pvs_mod->get($id);// 取出该商品的属性字符串
		$goods_pvs_str = $goods_pvs['pvs'];
		$props = array();
		if(!empty($goods_pvs_str))
		{
			$goods_pvs_arr = explode(';',$goods_pvs_str);//  分割成数组
			foreach($goods_pvs_arr as $pv)
			{
				if($pv)
				{
					$pv_arr = explode(':',$pv);
					$prop = $props_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND status=1'));
					if($prop)
					{
						$prop_value = $prop_value_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND vid='.$pv_arr[1].' AND status=1'));
						if($prop_value){
							if(isset($props[$pv_arr[0]])){
								$props[$pv_arr[0]]['value'] .= '，'.$prop_value['prop_value'];
							}
							else {
								$props[$pv_arr[0]] = array('name'=>$prop['name'],'value'=>$prop_value['prop_value']);
							}
						}
					}
				}
			}
		}
		return $props;		
	}
	
    /* 商品评论 */
    function comments()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        //增加店铺id参数，by PwordC
        $store_id = empty($_GET['store_id']) ? 2 : intval($_GET['store_id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $data = $this->_get_common_info($id,$store_id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }
        
        //获取会员等级价格设置状态
        if ($this->_get_vip_price_setting('status') != 0){
            //获取会员等级价格
            $this->get_member_type_price();
        }else{
            $this->assign('vip_price_status_off',1);
        }
        //获取pv设置
        if ($this->_get_pv_setting('status') != 0){
            $pv_view = $this->_get_pv_setting('config');
            $pv_view = unserialize($pv_view);
            $this->assign('pv_view',$pv_view);
        }
        //传到store_id到视图，by PwordC
        $this->assign('store_id',$store_id);

        /* 赋值商品评论 */
        $data = $this->_get_goods_comment($id, 10);
        $this->_assign_goods_comment($data);
		
		$this->_get_curlocal_title('goods_comment');

        $this->display('goods.comments.html');
    }

    /* 销售记录 */
    function saleslog()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        //增加店铺id参数，by PwordC
        $store_id = empty($_GET['store_id']) ? 2 : intval($_GET['store_id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $data = $this->_get_common_info($id,$store_id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }
        
        //传到store_id到视图，by PwordC
        $this->assign('store_id',$store_id);

        /* 赋值销售记录 */
        $data = $this->_get_sales_log($id, 10);
        $this->_assign_sales_log($data);
		
		$this->_get_curlocal_title('sales_log');

        $this->display('goods.saleslog.html');
    }
    function qa()
    {
    	$id = intval($_GET['id']);
    	//增加店铺id参数，by PwordC
    	$store_id = empty($_GET['store_id']) ? 2 : intval($_GET['store_id']);
        $goods_qa =& m('goodsqa');
         
        if(!IS_POST)
        {
			if (!$id)
         	{
            	$this->show_warning('Hacking Attempt');
            	return;
        	 }
		 
            $data = $this->_get_common_info($id,$store_id);
            if ($data === false)
            {
                return;
            }
            else
            {
                $this->_assign_common_info($data);
            }
            $data = $this->_get_goods_qa($id,5);
            $this->_assign_goods_qa($data);
            
            //传到store_id到视图，by PwordC
            $this->assign('store_id',$store_id);

            //是否开启验证码
            if (Conf::get('captcha_status.goodsqa'))
            {
                $this->assign('captcha', 1);
            }
			$this->_get_curlocal_title('qa');
            $this->assign('guest_comment_enable', Conf::get('guest_comment'));
            /*赋值产品咨询*/
            $this->display('goods.qa.html');
        }
        else
        {
			if (!$id)
         	{
            	$this->json_error('Hacking Attempt');
            	return;
        	 }
			 
            /* 不允许游客评论 */
            if (!Conf::get('guest_comment') && !$this->visitor->has_login)
            {
                $this->json_error('guest_comment_disabled');

                return;
            }
            $content = (isset($_POST['content'])) ? trim($_POST['content']) : '';
            //$type = (isset($_POST['type'])) ? trim($_POST['type']) : '';
            $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
            $hide_name = (isset($_POST['hide_name'])) ? trim($_POST['hide_name']) : '';
            if (empty($content))
            {
                $this->json_error('content_not_null');
                return;
            }
            //对验证码和邮件进行判断

            if (Conf::get('captcha_status.goodsqa'))
            {
                if (base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
                {
                    $this->json_error('captcha_failed');
                    return;
                }
            }
            if (!empty($email) && !is_email($email))
            {
                $this->json_error('email_not_correct');
                return;
            }
            $user_id = empty($hide_name) ? $_SESSION['user_info']['user_id'] : 0;
            $conditions = 'g.goods_id ='.$id;
            $goods_mod = & m('goods');
            $ids = $goods_mod->get(array(
                'fields' => 'goods_name',
                'conditions' => $conditions
            ));
            extract($ids);
            $data = array(
                'question_content' => html_script($content),
                'type' => 'goods',
                'item_id' => $id,
                'item_name' => addslashes($goods_name),
                'store_id' => $store_id,
                'email' => $email,
                'user_id' => $user_id,
                'time_post' => gmtime(),
            );
            if (!$goods_qa->add($data))
            {
                $this->json_error('add_fail');
            	return;
            }
            $this->json_result('', 'add_ok');
        }
    }
	
	function getGoodsProInfo()
	{
		$goods_id 	= intval($_GET['goods_id']);
		$spec_id 	= intval($_GET['spec_id']);
		
		// 在此获取该商品的促销策略，包括促销商品，会员价格，手机专享优惠价格等
		$result = FALSE;
		
		import('promotool.lib');
		$promotool = new Promotool();
		
		$result = $promotool->getItemProInfo($goods_id, $spec_id);
		
		if($result === FALSE) {
			$this->json_error($result);exit;
		}
		
		$this->json_result($result);
	}

    /**
     * 取得公共信息
     *
     * @param   int     $id
     * @param   int     $store_id  by PwordC
     * @return  false   失败
     *          array   成功
     */
    function _get_common_info($id,$store_id)//增加store_id，by PwordC
    {
        $cache_server =& cache_server();
        $key = 'page_of_goods_' . $id.$store_id;//增加缓存店铺标识，by PwordC
        $data = $cache_server->get($key);
        $cached = true;
        if ($data === false)
        {
            $cached = false;
            $data = array('id' => $id);

            /* 商品信息 */
            $goods = $this->_goods_mod->get_info($id);
            //获取店铺信息，判断是否开启， by PwordC
            $store_mod =& m('store');
            $store_info = $store_mod->get("store_id = {$store_id}");
			if ($store_info['state'] == 2)
            {
                $this->show_warning('the_store_is_closed');
                exit;
            }
            if (!$goods || $goods['if_show'] == 0 || $goods['closed'] == 1 || $store_info['state'] != 1)
            {
                $this->show_warning('goods_not_exist');
                return false;
            }
            $goods['tags'] = $goods['tags'] ? explode(',', trim($goods['tags'], ',')) : array();
			
			import('promotool.lib');
			$promotool = new Promotool(array('_store_id' => $store_id));//替换商品store_id属性 by PwordC
			
			$result = $promotool->getItemProInfo($goods['goods_id'], $goods['default_spec']);
			if($result !== FALSE) {
				if($result['pro_type'] == 'limitbuy') {
					$limitbuy_mod = &m('limitbuy');
					$limitbuy = $limitbuy_mod->get(array('conditions'=>"goods_id=".$goods['goods_id'], 'fields' => 'end_time,pro_name'));
					$goods['lefttime'] = Psmb_init()->lefttime($limitbuy['end_time']);
					$goods['pro_name'] = $limitbuy['pro_name'];
				}
				else $goods['pro_name'] = Lang::get($result['pro_type']);
			}
			
			/* 商品搭配套餐 */
			$goods['mealgoods'] = $this->_get_meal_goods($id,$store_id);

			//计算会员价格 价格+积分形式 by PwordC
			$integral_price = $goods['price']-$goods['max_exchange'];
			$goods['integral_price'] = $integral_price;
			
            $data['goods'] = $goods;
			
			/* 获取商品详情页显示所有该商品具有的营销工具信息 */
			//import('promotool.lib');
            $data['promotool'] = $promotool->getGoodsAllPromotoolInfo($id);
			
            /* 店铺信息 */
            if (!$store_id)//替换商品store_id属性 by PwordC
            {
                $this->show_warning('store of goods is empty');
                return false;
            }
            
            $this->set_store($store_id);//替换商品store_id属性 by PwordC
            
            $data['store_data'] = $this->get_store_data();
			
			$integral_mod = &m('integral');
			if($integral_mod->_get_sys_setting('integral_enabled'))
			{
				$data['goods']['integral_enabled'] = true;
				$data['goods']['exchange_price'] = $integral_mod->_get_sys_setting('exchange_rate')*$goods['max_exchange'];
			}
			
            /* 当前位置 */
            $data['cur_local'] = $this->_get_curlocal($goods['cate_id']);
            $data['goods']['related_info'] = $this->_get_related_objects($data['goods']['tags']);
            /* 分享链接 */
            $data['share'] = $this->_get_share($goods);
			
			 

            $cache_server->set($key, $data, 1800);
        }
        if ($cached)
        {
            $this->set_store($store_id);//替换商品store_id属性 by PwordC
        }

        return $data;
    }
	
	function get_default_logist($delivery_template_id,$store_id)
	{
		$store_mod = &m('store');
		$region_mod= &m('region');
		$delivery_mod = &m('delivery_template');
		
		// 如果没有设置运费模板，则取该店铺默认的运费模板
		if(!$delivery_template_id || !$delivery_mod->get($delivery_template_id))
		{
			$delivery = $delivery_mod->get(array(
				'condtions'=>'store_id='.$store_id,
				'order'=>'template_id',
			));
	
			// 如果店铺也没有默认的运费模板
			if(empty($delivery)) {
				return array();
			}
		}
		else {
			$delivery = $delivery_mod->get($delivery_template_id);
		}
		
		// 通过IP自动获取省和城市id
		$city_id = $region_mod->get_city_id_by_ip();

		$logist_fee = $delivery_mod->get_city_logist($delivery,$city_id);
		
		return !empty($logist_fee) ? current($logist_fee) : array();
	}

    function _get_related_objects($tags)
    {
        if (empty($tags))
        {
            return array();
        }
        $tag = $tags[array_rand($tags)];
        $ms =& ms();

        return $ms->tag_get($tag);
    }

    /* 赋值公共信息 */
    function _assign_common_info($data)
    {
		// 手机版要读取默认的运费（PC端不需要）
		$data['goods']['default_logist'] = $this->get_default_logist($data['goods']['delivery_template_id'], $data['goods']['store_id']);
		
        /* 商品信息 */
        $goods = $data['goods'];
        $this->assign('goods', $goods);
        $this->assign('sales_info', sprintf(LANG::get('sales'), $goods['sales'] ? $goods['sales'] : 0));
        $this->assign('comments', sprintf(LANG::get('comments'), $goods['comments'] ? $goods['comments'] : 0));

        /* 店铺信息 */
        $this->assign('store', $data['store_data']);

        /* 浏览历史 */
        $this->assign('goods_history', $this->_get_goods_history($data['id']));

        /* 默认图片 */
        $this->assign('default_image', Conf::get('default_goods_image'));

        /* 当前位置 */
        $this->_curlocal($data['cur_local']);

        /* 配置seo信息 */
        $this->_config_seo($this->_get_seo_info($data['goods']));

        /* 商品分享 */
        $this->assign('share', $data['share']);
		
		/* 赋值营销工具 */
		$this->assign('promotool', $data['promotool']);

        $this->import_resource(array(
            'script' => 'jquery.jqzoom.js',
            'style' => 'res:jqzoom.css'
        ));
    }

    /* 取得浏览历史 */
    function _get_goods_history($id, $num = 9)
    {
        $goods_list = array();
        $goods_ids  = ecm_getcookie('goodsBrowseHistory');
        $goods_ids  = $goods_ids ? explode(',', $goods_ids) : array();
        if ($goods_ids)
        {
            $rows = $this->_goods_mod->find(array(
                'conditions' => $goods_ids,
                'fields'     => 'goods_name,default_image',
            ));
            foreach ($goods_ids as $goods_id)
            {
                if (isset($rows[$goods_id]))
                {
                    empty($rows[$goods_id]['default_image']) && $rows[$goods_id]['default_image'] = Conf::get('default_goods_image');
                    $goods_list[] = $rows[$goods_id];
                }
            }
        }
        $goods_ids[] = $id;
        if (count($goods_ids) > $num)
        {
            unset($goods_ids[0]);
        }
        ecm_setcookie('goodsBrowseHistory', join(',', array_unique($goods_ids)));

        return $goods_list;
    }

    /* 取得销售记录 */
    function _get_sales_log($goods_id, $num_per_page)
    {
        $data = array();

        $page = $this->_get_page($num_per_page);
        $order_goods_mod =& m('ordergoods');
        $sales_list = $order_goods_mod->find(array(
            'conditions' => "goods_id = '$goods_id' AND order_alias.status = '" . ORDER_FINISHED . "'",
            'join'  => 'belongs_to_order',
            'fields'=> 'buyer_id, buyer_name, add_time, anonymous, goods_id, specification, price, quantity, evaluation',
            'count' => true,
            'order' => 'add_time desc',
            'limit' => $page['limit'],
        ));
		foreach($sales_list as $k=>$v) {
			$sales_list[$k]['buyer_name'] = cut_str($v['buyer_name']);
		}
        $data['sales_list'] = $sales_list;

        $page['item_count'] = $order_goods_mod->getCount();
        $this->_format_page($page);
        $data['page_info'] = $page;
        $data['more_sales'] = $page['item_count'] > $num_per_page;

        return $data;
    }

    /* 赋值销售记录 */
    function _assign_sales_log($data)
    {
        $this->assign('sales_list', $data['sales_list']);
        $this->assign('page_info',  $data['page_info']);
        $this->assign('more_sales', $data['more_sales']);
    }

    /* 取得商品评论 */
    function _get_goods_comment($goods_id, $num_per_page)
    {
        $data = array();

        $page = $this->_get_page($num_per_page);
        $order_goods_mod =& m('ordergoods');
        $comments = $order_goods_mod->find(array(
            'conditions' => "goods_id = '$goods_id' AND evaluation_status = '1'",
            'join'  => 'belongs_to_order',
            'fields'=> 'buyer_id, buyer_name, anonymous, evaluation_time, comment, evaluation',
            'count' => true,
            'order' => 'evaluation_time desc',
            'limit' => $page['limit'],
        ));
		if($comments)
		{
			foreach($comments as $key=>$comment)
			{
				//empty($comment['portrait']) && $comments[$key]['portrait']=Conf::get('default_user_portrait');
				$comments[$key]['buyer_name'] = cut_str($comment['buyer_name']);
			}
		}
        $data['comments'] = $comments;

        $page['item_count'] = $order_goods_mod->getCount();
        $this->_format_page($page);
        $data['page_info'] = $page;
        $data['more_comments'] = $page['item_count'] > $num_per_page;

        return $data;
    }

    /* 赋值商品评论 */
    function _assign_goods_comment($data)
    {
        $this->assign('goods_comments', $data['comments']);
        $this->assign('page_info',      $data['page_info']);
        $this->assign('more_comments',  $data['more_comments']);
    }

    /* 取得商品咨询 */
    function _get_goods_qa($goods_id,$num_per_page)
    {
        $page = $this->_get_page($num_per_page);
        $goods_qa = & m('goodsqa');
        $qa_info = $goods_qa->find(array(
            'join' => 'belongs_to_user',
            'fields' => 'member.user_name,question_content,reply_content,time_post,time_reply',
            'conditions' => '1 = 1 AND item_id = '.$goods_id . " AND type = 'goods'",
            'limit' => $page['limit'],
            'order' =>'time_post desc',
            'count' => true
        ));
        $page['item_count'] = $goods_qa->getCount();
        $this->_format_page($page);

        //如果登陆，则查出email
        if (!empty($_SESSION['user_info']))
        {
            $user_mod = & m('member');
            $user_info = $user_mod->get(array(
                'fields' => 'email',
                'conditions' => '1=1 AND user_id = '.$_SESSION['user_info']['user_id']
            ));
            extract($user_info);
        }

        return array(
            'email' => $email,
            'page_info' => $page,
            'qa_info' => $qa_info,
        );
    }

    /* 赋值商品咨询 */
    function _assign_goods_qa($data)
    {
        $this->assign('email',      $data['email']);
        $this->assign('page_info',  $data['page_info']);
        $this->assign('qa_info',    $data['qa_info']);
    }

    /* 更新浏览次数 */
    function _update_views($id)
    {
        $goodsstat_mod =& m('goodsstatistics');
		if($goodsstat_mod->get($id)){
        	$goodsstat_mod->edit($id, "views = views + 1");
		} else {
			$goodsstat_mod->add(array('goods_id' => $id, 'views' => 1));
		}
    }


    /**
     * 取得当前位置
     *
     * @param int $cate_id 分类id
     */
    function _get_curlocal($cate_id)
    {
        $parents = array();
        if ($cate_id)
        {
            $gcategory_mod =& bm('gcategory');
            $parents = $gcategory_mod->get_ancestor($cate_id, true);
        }

        $curlocal = array(
            array('text' => LANG::get('all_categories'), 'url' => url('app=category')),
        );
        foreach ($parents as $category)
        {
            $curlocal[] = array('text' => $category['cate_name'], 'url' => url('app=search&cate_id=' . $category['cate_id']));
        }
        $curlocal[] = array('text' => LANG::get('goods_detail'));

        return $curlocal;
    }

    function _get_share($goods)
    {
        $m_share = &af('share');
        $shares = $m_share->getAll();
        $shares = array_msort($shares, array('sort_order' => SORT_ASC));
        $goods_name = ecm_iconv(CHARSET, 'utf-8', $goods['goods_name']);
        $goods_url = urlencode(SITE_URL . '/' . str_replace('&amp;', '&', url('app=goods&id=' . $goods['goods_id'])));
        $site_title = ecm_iconv(CHARSET, 'utf-8', Conf::get('site_title'));
        $share_title = urlencode($goods_name . '-' . $site_title);
        foreach ($shares as $share_id => $share)
        {
            $shares[$share_id]['link'] = str_replace(
                array('{$link}', '{$title}'),
                array($goods_url, $share_title),
                $share['link']);
        }
        return $shares;
    }
    
    function _get_seo_info($data)
    {
        $seo_info = $keywords = array();
        $seo_info['title'] = $data['goods_name'] . ' - ' . Conf::get('site_title');        
        $keywords = array(
            $data['brand'],
            $data['goods_name'],
            $data['cate_name']
        );
        $seo_info['keywords'] = implode(',', array_merge($keywords, $data['tags']));        
        $seo_info['description'] = sub_str(strip_tags($data['description']), 10, true);
        return $seo_info;
    }
	
	function _get_meal_goods($id,$store_id)//增加store_id属性，兼容产品共用问题。
	{
		$this->_meal_mod =& m('meal');
		$this->_mealgoods_mod =& m('mealgoods');
		
		$meals = $this->_mealgoods_mod->find(array(
			'conditions' => 'status = 1 AND goods_id='.$id." AND store_id={$store_id}",
			'join'       => 'belongs_to_meal',
		));
		foreach($meals as $key=>$meal)
		{
			$meals_goods[$key] = current($this->_meal_mod->findAll(array(
				'conditions' => 'status = 1 AND meal_id='.$meal['meal_id']." AND user_id={$store_id}",
				'include'	=> array('has_mealgoods'),
				'order'      => 'meal_id desc',
				'fields'     => 'this.meal_id,price,title',
			)));
			foreach($meals_goods[$key]['meal_goods'] as $k=>$goods)
			{
				$mgoods[$k] = $this->_goods_mod->get(array(
					'conditions' => 'goods_id='.$goods['goods_id'],
					'fields'     => 'default_image,price,goods_name',
				));
				$meals_goods[$key]['sub_price'] += $mgoods[$k]['price']; 
				$mgoods[$k] && $meals_goods[$key]['meal_goods'][$k] = array_merge($goods,$mgoods[$k]);
				if($id == $goods['goods_id'])
				{
					unset($meals_goods[$key]['meal_goods'][$k]);
				}
			}
			$meals_goods[$key]['save_price'] = $meals_goods[$key]['sub_price'] - $meals_goods[$key]['price'];
			$meals_goods[$key]['width'] = count($meals_goods[$key]['meal_goods']) * 115;
		}
		return $meals_goods;
	}
	/**
	 *    获取应显示的会员价格
	 *
	 *    @author    PwordC
	 *    @param     
	 *    @return    
	 */
	function get_member_type_price()
	{
	    //获取配置信息
	    $config = $this->_get_vip_price_setting('config');
	    $config = unserialize($config);
	    
	    //获取对应等级价格
	    if ($this->_member_type ==0 || !$this->visitor->has_login){
	        $this->assign('price_0',1);
	    }else {
	        if ($config){
	            foreach ($config as $k=>$v){
	                if ($v==$this->_member_type){
	                    //dump($k);die();
	                    $this->assign($k,1);
	                }
	            }
	        }
	        
	    }
	    
	}
}

?>
