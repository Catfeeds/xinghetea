<?php

class StoreApp extends StorebaseApp
{
    function index()
    {
        /* 店铺信息 */
        $_GET['act'] = 'index';
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        $this->set_store($id);
        $store = $this->get_store_data();
		
		$distribution_mod =&m('distribution');
		$store = array_merge($store, $distribution_mod->getCheckJoinDistributionInfo($this->visitor->get('user_id'), $id, intval($_GET['did'])));
		
		$this->setCookieDid($store['did'], $id);
		
        $this->assign('store', $store);

        /* 取得推荐商品 */
        $this->assign('recommended_goods',array_chunk($this->_get_recommended_goods($id,6),2));

        /* 取得最新商品 */
        $this->assign('new_goods',array_chunk($this->_get_new_goods($id,6),2));
		
		/* 取得热卖商品 */
        $this->assign('sales_goods',array_chunk($this->_get_sales_goods($id,6),2));
		
		/* 取得热气商品 */
        $this->assign('hot_goods',array_chunk($this->_get_hot_goods($id,6),2));
		
		$this->assign('store_static',$this->store_static($id));

		$template = $store['wap_theme'] ? current(explode('|',$store['wap_theme'])) : 'default';
		$decoration_mod = &af('decoration',array('template'=>$template,'type'=>'mobile','store_id'=>$id));
		$this->assign($decoration_mod->_get_decoration_data());
	
		/* 配置seo信息 */
        $this->_config_seo($this->_get_seo_info($store));
		$this->_get_curlocal_title($store['store_name']);
        $this->display('store.index.html');
    }
	function store_static($id)
	{
		$goods_mod =& bm('goods', array('_store_id' => $id));
        $new_count =count($goods_mod->find(array('conditions'=>'add_time > '.strtotime(date('Y-M-01')))));
		$order_mod = &m('order');
		$order_count = count($order_mod->find('seller_id = '.$id.' AND finished_time > 0'));
		return array('new_count' => $new_count,'order_count' => $order_count);
	}
	
	function map()
	{
		$store_id = intval($_GET['id']);
		if(!$store_id)
		{
			$this->show_warning('Hacking Attempt');
			return;
		}
		
		$store_mod = &m('store');
		$store = $store_mod->get(array('conditions'=>'store_id='.$store_id, 'fields'=> 'latlng,store_name,store_logo'));
		if(!$store || !$store['latlng'])
		{
			$this->show_warning('not_config_position');
			return;
		}
		empty($store['store_logo']) && $store['store_logo'] = Conf::get('default_store_logo');
		
		$latlng = explode(',',$store['latlng']);
		$store = array_merge($store, array('lat' => $latlng[0], 'lng' => $latlng[1]));

		$this->assign('store', $store);
		
		$this->headtag('<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak='.Conf::get('baidukey.browser').'"></script>');
		$this->_config_seo('title', Lang::get('store_map') . ' - ' . $store['store_name']);
		$this->_get_curlocal_title('store_map');
		$this->display('store.map.html');
	}
	
    function search()
    {
        /* 店铺信息 */
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }
        $this->set_store($id);
        $store = $this->get_store_data();
        $this->assign('store', $store);

        /* 搜索到的商品 */
        $this->_assign_searched_goods($id);

        /* 当前位置 */
        $this->_curlocal(LANG::get('all_stores'), 'index.php?app=search&amp;act=store',
            $store['store_name'], 'index.php?app=store&amp;id=' . $store['store_id'],
            LANG::get('goods_list')
        );

        $this->_config_seo('title', Lang::get('goods_list') . ' - ' . $store['store_name']);
        $this->display('store.search.html');
    }

    

    /* 取得推荐商品 */
    function _get_recommended_goods($id, $num = 12)
    {
        $goods_mod =& m('goods');//禁用业务模型，使用普通模型获取产品，by PwordC
        $goods_list = $goods_mod->find(array(
            'conditions' => "closed = 0 AND if_show = 1 AND recommended = 1",
            'fields'     => 'goods_name, default_image, price',
            'limit'      => $num,
        ));
        foreach ($goods_list as $key => $goods)
        {
            empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
        }

        return $goods_list;
    }

    function _get_new_groupbuy($id, $num = 12)
    {
        $model_groupbuy =& m('groupbuy');
        $groupbuy_list = $model_groupbuy->find(array(
            'fields'    => 'goods.default_image, this.group_name, this.group_id, this.spec_price, this.end_time',
            'join'      => 'belong_goods',
            'conditions'=> $model_groupbuy->getRealFields('this.state=' . GROUP_ON . ' AND this.store_id=' . $id . ' AND end_time>'. gmtime()),
            'order'     => 'group_id DESC',
            'limit'     => $num
        ));
        if (empty($groupbuy_list))
        {
            $groupbuy_list = array();
        }
        foreach ($groupbuy_list as $key => $_g)
        {
            empty($groupbuy_list[$key]['default_image']) && $groupbuy_list[$key]['default_image'] = Conf::get('default_goods_image');
            $tmp = current(unserialize($_g['spec_price']));
            $groupbuy_list[$key]['price'] = $tmp['price'];
            $groupbuy_list[$key]['lefttime'] = lefttime($_g['end_time']);
        }

        return $groupbuy_list;
    }

    /* 取得最新商品 */
    function _get_new_goods($id, $num = 12)
    {
        $goods_mod =& m('goods');//禁用业务模型，使用普通模型获取产品，by PwordC
        $goods_list = $goods_mod->find(array(
            'conditions' => "closed = 0 AND if_show = 1",
            'fields'     => 'goods_name, default_image, price',
            'order'      => 'add_time desc',
            'limit'      => $num,
        ));
        foreach ($goods_list as $key => $goods)
        {
            empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
        }

        return $goods_list;
    }
	
	/* 取得热卖商品 */
    function _get_sales_goods($id, $num = 12)
    {
        $goods_mod =& m('goods');//禁用业务模型，使用普通模型获取产品，by PwordC
        $goods_list = $goods_mod->find(array(
            'conditions' => "closed = 0 AND if_show = 1",
			'join'       => 'has_goodsstatistics',
            'fields'     => 'goods_name, default_image, price',
            'order'      => 'goodsstatistics.sales desc',
            'limit'      => $num,
        ));
        foreach ($goods_list as $key => $goods)
        {
            empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
        }

        return $goods_list;
    }
	
	/* 取得人气商品 */
    function _get_hot_goods($id, $num = 12)
    {
        $goods_mod =& m('goods');//禁用业务模型，使用普通模型获取产品，by PwordC
        $goods_list = $goods_mod->find(array(
            'conditions' => "closed = 0 AND if_show = 1",
			'join'       => 'has_goodsstatistics',
            'fields'     => 'goods_name, default_image, price',
            'order'      => 'goodsstatistics.views desc',
            'limit'      => $num,
        ));
        foreach ($goods_list as $key => $goods)
        {
            empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
        }

        return $goods_list;
    }

    /* 搜索到的结果 */
    function _assign_searched_goods($id)
    {
        $goods_mod =& m('goods');//禁用业务模型，使用普通模型获取产品，by PwordC
        $search_name = LANG::get('all_goods');

        $conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'goods_name',
                'name'  => 'keyword',
                'equal' => 'like',
            ),
        ));
        if ($conditions)
        {
            $search_name = sprintf(LANG::get('goods_include'), $_GET['keyword']);
            $sgcate_id   = 0;
        }
        else
        {
            $sgcate_id = empty($_GET['cate_id']) ? 0 : intval($_GET['cate_id']);
        }

        if ($sgcate_id > 0)
        {
            $gcategory_mod =& bm('gcategory', array('_store_id' => $id));
            $sgcate = $gcategory_mod->get_info($sgcate_id);
            $search_name = $sgcate['cate_name'];

            $sgcate_ids = $gcategory_mod->get_descendant_ids($sgcate_id);
        }
        else
        {
            $sgcate_ids = array();
        }

        /* 排序方式 */
        $orders = array(
            'add_time desc' => LANG::get('add_time_desc'),
            'price asc' => LANG::get('price_asc'),
            'price desc' => LANG::get('price_desc'),
        );
        $this->assign('orders', $orders);

        $page = $this->_get_page(16);
        $goods_list = $goods_mod->get_list(array(
            'conditions' => 'closed = 0 AND if_show = 1' . $conditions,
            'count' => true,
            'order' => empty($_GET['order']) || !isset($orders[$_GET['order']]) ? 'add_time desc' : $_GET['order'],
            'limit' => $page['limit'],
        ), $sgcate_ids);
        foreach ($goods_list as $key => $goods)
        {
            empty($goods['default_image']) && $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
        }
        $this->assign('searched_goods', array_chunk($goods_list,2));

        $page['item_count'] = $goods_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);

        $this->assign('search_name', $search_name);
    }

   
    function _get_seo_info($data)
    {
        $seo_info = $keywords = array();
        $seo_info['title'] = $data['store_name'] . ' - ' . Conf::get('site_title');        
        $keywords = array(
            str_replace("\t", ' ', $data['region_name']),
            $data['store_name'],
        );
        //$seo_info['keywords'] = implode(',', array_merge($keywords, $data['tags']));
        $seo_info['keywords'] = implode(',', $keywords);
        $seo_info['description'] = sub_str(strip_tags($data['description']), 10, true);
        return $seo_info;
    }
}

?>
