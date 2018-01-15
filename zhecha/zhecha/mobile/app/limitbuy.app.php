<?php


class LimitbuyApp extends MallbaseApp
{
    function index()
    {
		$limitbuy_mod = &m('limitbuy');
		
		$page = $this->_get_page(20);
		$goods_list = $limitbuy_mod->find(array(
			'conditions'=>'start_time <='.gmtime(). ' AND end_time>='.gmtime(),
			'join'      =>'belong_goods',
			'fields'    =>'this.*,g.default_image,g.price,g.default_spec,g.goods_name,g.default_spec',
			'limit'     =>$page['limit'],
			'count'     =>true,
			'order'     =>'pro_id DESC'
		));
		if($goods_list)
		{
			$store_mod = &m('store');
			$goodsstatistics_mod = &m('goodsstatistics');
			
			import('promotool.lib');
			$promotool = new Promotool();
			
			foreach ($goods_list as $key => $goods)
			{
				$result = $promotool->getItemProInfo($goods['goods_id'], $goods['default_spec']);
				if($result !== FALSE) {
					$goods_list[$key]['pro_price'] = $result['pro_price'];
				} else $goods_list[$key]['pro_price'] = $goods['price'];
				
				$goods['default_image'] || $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
				
				$store = $store_mod->get(array('conditions'=>'store_id='.$goods['store_id'], 'fields'=>'store_name,store_id'));
				
				$goodsstatistics = $goodsstatistics_mod->get('goods_id='.$goods['goods_id']);
		
				$goods_list[$key]['store_name'] = $store['store_name'];
				$goods_list[$key]['sales']      = $goodsstatistics['sales'];
				//$goods_list[$key]['comment']  = $goodsstatistics['comments'];
			}
		}

		$page['item_count'] = $limitbuy_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
		
		/* 商品展示方式 */
        $display_mode = ecm_getcookie('goodsDisplayMode');
        if (empty($display_mode) || !in_array($display_mode, array('list', 'squares')))
        {
            $display_mode = 'squares'; // 默认格子方式
        }
        $this->assign('display_mode', $display_mode);
		
		/* 当前位置 */
        $this->_curlocal(array(array('text'=>Lang::get('limitbuy_list'), 'url'=>'')));
		 /* 取得导航 */
        $this->assign('navs', $this->_get_navs());
        
        /* 配置seo信息 */
		$this->_config_seo('title', Lang::get('limitbuy_list') . ' - ' . Conf::get('site_title'));
		
		 $this->_get_curlocal_title('limitbuy_list');

		$this->assign('goods_list',$goods_list);
        $this->display('limitbuy.index.html');
	}
}

?>
