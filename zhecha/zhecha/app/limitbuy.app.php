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
		
		import('promotool.lib');
		$promotool = new Promotool();
		
		if($goods_list)
		{
			foreach ($goods_list as $key => $goods)
			{
				$result = $promotool->getItemProInfo($goods['goods_id'], $goods['default_spec']);
				if($result !== FALSE) {
					$goods_list[$key]['pro_price'] = $result['pro_price'];
				} else $goods_list[$key]['pro_price'] = $goods['price'];
				
				$goods['default_image'] || $goods_list[$key]['default_image'] = Conf::get('default_goods_image');
			}
		}
		
		$page['item_count'] = $limitbuy_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
		
		/* 当前位置 */
        $this->_curlocal(array(array('text'=>Lang::get('limitbuy_list'),'url'=>'')));
		 /* 取得导航 */
        $this->assign('navs', $this->_get_navs());
        
        /* 配置seo信息 */
		$this->_config_seo('title', Lang::get('limitbuy_list') . ' - ' . Conf::get('site_title'));

		$this->assign('goods_list',$goods_list);
        $this->display('limitbuy.index.html');
	}
}

?>
