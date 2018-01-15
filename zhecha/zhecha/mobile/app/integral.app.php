<?php


class IntegralApp extends MallbaseApp
{
	var $_integral_mod;
	
	function __construct()
	{
		parent::__construct();
		$this->_integral_mod = &m('integral');
	}
	
    function index()
    {
		if(!$this->_integral_mod->_get_sys_setting('integral_enabled')){
			$this->show_warning('integral_disabled');
			exit;
		}
		$order = '';
		if(isset($_GET['order']) && !empty($_GET['order'])) { 
			$sort = trim($_GET['order']);
			if(in_array(strtolower($sort), array('add_time desc', 'add_time asc'))){
				$order = 'g.'.$sort;
			}
			elseif(in_array(strtolower($sort), array('sales desc', 'sales asc', 'price desc', 'price asc'))) {
				$order = $sort;
			}
		}
		
		$page = $this->_get_page(20);
		$goods_mod=&m('goods');
		$goods_list=$goods_mod->find(array(
			'conditions'=>'gi.max_exchange > 0 ',
			'join'      =>'has_goodsintegral,has_goodsstatistics,belongs_to_store',
			'fields'    =>'gi.max_exchange,g.default_image,g.goods_name,g.price,s.store_name,s.store_id,goods_statistics.sales',
			'limit'     =>$page['limit'],
			'count'     =>true,
			'order'     =>$order
		));
		if($goods_list)
		{
			$rate = $this->_integral_mod->_get_sys_setting('exchange_rate');
			foreach($goods_list as $key=>$goods){
				empty($goods['default_image']) && $goods_list[$key]['default_image']=Conf::get('default_goods_image');
				$goods_list[$key]['exchange'] = $goods['max_exchange'];
				$goods_list[$key]['exchange_price'] = $goods['max_exchange'] * $rate;
				$price = $goods['price'] - $goods_list[$key]['exchange_price'];
				if($price < 0) {
					$goods_list[$key]['exchange'] = round($goods['price'] / $rate, 2);
					$goods_list[$key]['exchange_price'] = $goods['price'];
					$price = 0;
				}
				$goods_list[$key]['price'] = $price;
			} 
		}
		$page['item_count'] = $goods_mod->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
		
		$this->headtag('<link href="{res file=css/integral.css}" rel="stylesheet" type="text/css" />');
        
        /* 配置seo信息 */
        $this->_config_seo($this->_get_seo_info('integral_list'));
		$this->_get_curlocal_title('integral_list');
		$this->assign('goods_list',$goods_list);
        $this->display('integral.index.html');
	}
	 function _get_seo_info($type)
    {
        $seo_info = array();
        $seo_info['title'] = Lang::get($type) . ' - ' .Conf::get('site_title');
        $seo_info['keywords'] = Conf::get($type);
        $seo_info['description'] = Lang::get($type) . ' - ' .Conf::get('site_title');
        return $seo_info;
    }
}

?>
