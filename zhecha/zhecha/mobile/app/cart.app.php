<?php

/**
 *    购物车控制器，负责会员购物车的管理工作，她与下一步售货员的接口是：购物车告诉售货员，我要买的商品是我购物车内的商品
 *
 *    @author    Garbin
 */

class CartApp extends MallbaseApp
{
    /**
     *    列出购物车中的商品
     *
     *    @author    Garbin
     *    @return    void
     */
    function index()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		
		if(!IS_POST)
		{
		
			list($carts, $allAmount,$allAmount_exchange) = $this->_get_carts($store_id);//增加积分统计参数 by PwordC
			$this->_curlocal(
				LANG::get('cart')
			);
			$this->_config_seo('title', Lang::get('confirm_goods') . ' - ' . Conf::get('site_title'));
	
			if (empty($carts))
			{
				$this->_cart_empty();
	
				return;
			}
			
					
	
			$this->assign('myCart', array('carts' => $carts, 'allAmount' => $allAmount,'allAmount_exchange' => $allAmount_exchange));//增加积分统计参数 by PwordC
			$this->_get_curlocal_title('confirm_goods');
			$this->display('cart.index.html');
		}
		else
		{
			$selectedList = array();
			$cart_mod = &m('cart');
			foreach($_POST['buy'] as $rec_id => $val)
			{
				// 过滤掉不是购物车中的商品，或者是购物车中的商品但不是自己的商品
				$rec = $cart_mod->get(array(
					'conditions' => 'rec_id='.$rec_id.' AND user_id='.$this->visitor->get('user_id').' AND session_id="'.SESS_ID.'"',
					'fields'	 => 'rec_id,store_id, goods_id',
				));
				if($rec) {
					$selectedList[] = $rec['rec_id'];
				}
			}
			
			if(empty($selectedList))
			{
				$this->show_warning('select_empty_by_cart');
				return;
			}
			
			// 到此，可以认为是正常的购买数据
			
			//　保存选中的商品
			$carts = $cart_mod->find(array(
				'conditions' => 'user_id='.$this->visitor->get('user_id').' AND session_id="'.SESS_ID.'"',
				'fields'	 => 'rec_id',
			));
			foreach($carts as $rec_id => $val)
			{
				$selected = 0;
				if(in_array($rec_id, $selectedList)) {
					$selected = 1;
				}
				$cart_mod->edit($rec_id, array('selected' => $selected));
			}
			
			header('Location:index.php?app=order&goods=cart');

		}
    }

    /**
     *    放入商品(根据不同的请求方式给出不同的返回结果)
     *
     *    @author    Garbin
     *    @return    void
     */
    function add()
    {
        $spec_id	= isset($_GET['spec_id']) ? intval($_GET['spec_id']) : 0;
        $quantity   = isset($_GET['quantity']) ? intval($_GET['quantity']) : 0;
		$selected   = isset($_GET['selected']) ? intval($_GET['selected']) : 0;
		//增加store_id参数，判断商品属于哪个店铺。by PwordC
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        if (!$spec_id || !$quantity)
        {
            return;
        }

        /* 是否有商品 */
        $spec_model =& m('goodsspec');
        $spec_info  =  $spec_model->get(array(
            'fields'        => 'g.goods_id, g.goods_name, g.spec_name_1, g.spec_name_2, g.default_image, gs.spec_1, gs.spec_2, gs.stock, gs.price,gs.price_1,gs.price_2,gs.price_3,gs.price_4,gs.price_5',
            'conditions'    => $spec_id,
            'join'          => 'belongs_to_goods',
        ));
        
        //赋值商品所属店铺id，by PwordC
        $spec_info['store_id'] = $store_id;

        if (!$spec_info)
        {
            $this->json_error('no_such_goods');
            /* 商品不存在 */
            return;
        }

        /* 如果是自己店铺的商品，则不能购买 */
        if ($this->visitor->get('manage_store'))
        {
            if ($spec_info['store_id'] == $this->visitor->get('manage_store'))
            {
                $this->json_error('can_not_buy_yourself');

                return;
            }
        }

        /* 是否添加过 */
        $model_cart =& m('cart');
        //增加店铺id筛选条件，by PwordC
        $item_info  = $model_cart->get("store_id={$store_id} and spec_id={$spec_id} AND session_id='" . SESS_ID . "'");
        if (!empty($item_info))
        {
            $this->json_error('goods_already_in_cart');

            return;
        }

        if ($quantity > $spec_info['stock'])
        {
            $this->json_error('no_enough_goods');
            return;
        }
		
		if($selected) {
			$model_cart->edit("session_id='" . SESS_ID . "' AND user_id=".$this->visitor->get('user_id'). ' AND selected=1', array('selected' => 0));
		}
		
		/* 读取促销价格 */
		import('promotool.lib');
		$promotool = new Promotool(array('_store_id' => $spec_info['store_id']));
		$result = $promotool->getItemProInfo($spec_info['goods_id'], $spec_info['spec_id']);
		if($result !== FALSE) {
			$spec_info['price'] = $result['pro_price'];
		}elseif ($price = $this->get_vip_price()) {//获取会员价  by PwordC
		    if ($spec_info[$price] != 0 || !empty($spec_info[$price])){
		        $spec_info['price'] = $spec_info[$price];		        
		    }
		}
		
        $spec_1 = $spec_info['spec_name_1'] ? $spec_info['spec_name_1'] . ':' . $spec_info['spec_1'] : $spec_info['spec_1'];
        $spec_2 = $spec_info['spec_name_2'] ? $spec_info['spec_name_2'] . ':' . $spec_info['spec_2'] : $spec_info['spec_2'];

        $specification = $spec_1 . ' ' . $spec_2;
        //获取商品所需抵扣积分  by PwordC
        $goods_integral_mod =& m('goods_integral');
        $goods_integral_info = $goods_integral_mod->get("goods_id={$spec_info['goods_id']}");
        $max_exchange = $goods_integral_info['max_exchange'];

        /* 将商品加入购物车 */
        $cart_item = array(
            'user_id'       => $this->visitor->get('user_id'),
            'session_id'    => SESS_ID,
            'store_id'      => $spec_info['store_id'],
            'spec_id'       => $spec_id,
            'goods_id'      => $spec_info['goods_id'],
            'goods_name'    => addslashes($spec_info['goods_name']),
            'specification' => addslashes(trim($specification)),
            'price'         => $spec_info['price']-$max_exchange,//计算扣除积分后，实际所需金额 by PwordC
            'quantity'      => $quantity,
            'goods_image'   => addslashes($spec_info['default_image']),
			'selected'		=> $selected,
            'max_exchange'  => $max_exchange,//增加所需积分字段 by PwordC
        );

        /* 添加并返回购物车统计即可 */
        $cart_model =&  m('cart');
        $cart_model->add($cart_item);
		
        $cart_status = $this->_get_cart_status();

        /* 更新被添加进购物车的次数 */
        $model_goodsstatistics =& m('goodsstatistics');
        $model_goodsstatistics->edit($spec_info['goods_id'], 'carts=carts+1');

        $this->json_result(array(
            'cart'      =>  $cart_status['status'],  //返回购物车状态
        ), 'addto_cart_successed');
    }
	
    /**
     *    丢弃商品
     *
     *    @author    Garbin
     *    @return    void
     */
    function drop()
    {
        /* 传入rec_id，删除并返回购物车统计即可 */
        $rec_id = isset($_GET['rec_id']) ? intval($_GET['rec_id']) : 0;
        if (!$rec_id)
        {
            return;
        }

        /* 从购物车中删除 */
        $model_cart =& m('cart');
        $droped_rows = $model_cart->drop('rec_id=' . $rec_id . ' AND session_id=\'' . SESS_ID . '\'', 'store_id');
        if (!$droped_rows)
        {
            return;
        }
		
        /* 返回结果 */
        $dropped_data = $model_cart->getDroppedData();
        $store_id     = $dropped_data[$rec_id]['store_id'];
        $cart_status = $this->_get_cart_status();
        $this->json_result(array(
            'cart'  =>  $cart_status['status'],                      //返回总的购物车状态
            'amount'=>  $cart_status['carts'][$store_id]['amount']   //返回指定店铺的购物车状态
        ),'drop_item_successed');
    }

    /**
     *    更新购物车中商品的数量，以商品为单位，AJAX更新
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function update()
    {
        $spec_id  = isset($_GET['spec_id']) ? intval($_GET['spec_id']) : 0;
        $quantity = isset($_GET['quantity'])? intval($_GET['quantity']): 0;
        if (!$spec_id || !$quantity)
        {
            /* 不合法的请求 */
            return;
        }

        /* 判断库存是否足够 */
        $model_spec =& m('goodsspec');
        $spec_info  =  $model_spec->get($spec_id);
        if (empty($spec_info))
        {
            /* 没有该规格 */
            $this->json_error('no_such_spec');
            return;
        }

        if ($quantity > $spec_info['stock'])
        {
            /* 数量有限 */
            $this->json_error('no_enough_goods');
            return;
        }

        /* 修改数量 */
        $where = "spec_id={$spec_id} AND session_id='" . SESS_ID . "'";
        $model_cart =& m('cart');
		

        /* 获取购物车中的信息，用于获取价格并计算小计 */
        $cart_spec_info = $model_cart->get($where);
        if (empty($cart_spec_info))
        {
            /* 并没有添加该商品到购物车 */
            return;
        }

        $store_id = $cart_spec_info['store_id'];

        /* 修改数量 */
        $model_cart->edit($where, array(
            'quantity'  =>  $quantity,
        ));

        /* 小计 */
        $subtotal   =   $quantity * $cart_spec_info['price'];
        //积分小计  by PwordC
        $subtotal_exchange = $quantity * $cart_spec_info['max_exchange'];

        /* 返回JSON结果 */
        $cart_status = $this->_get_cart_status();
        $this->json_result(array(
            'cart'      =>  $cart_status['status'],                     //返回总的购物车状态
			'price'     =>  $cart_spec_info['price'],
            'exchange'  =>  $cart_spec_info['max_exchange'],            //单品积分 by PwordC
			'quantity'  =>  $quantity,
            'subtotal'  =>  $subtotal,                                  //小计
            'subtotal_exchange' => $subtotal_exchange,                  //积分小计  by PwordC
            'amount'    =>  $cart_status['carts'][$store_id]['amount'],  //店铺购物车总计
            'amount_exchange' => $cart_status['carts'][$store_id]['amount_exchange'] //店铺积分总计  by PwordC
        ), 'update_item_successed');
    }

    /**
     *    获取购物车状态
     *
     *    @author    Garbin
     *    @return    array
     */
    function _get_cart_status()
    {
        /* 默认的返回格式 */
        $data = array(
            'status'    =>  array(
                'quantity'  =>  0,      //总数量
                'amount'    =>  0,      //总金额
                'amount_exchange' => 0, //总积分 byPwordC
                'kinds'     =>  0,      //总种类
            ),
            'carts'     =>  array(),    //购物车列表，包含每个购物车的状态
        );

        /* 获取所有购物车 */
        list($carts) = $this->_get_carts();
        if (empty($carts))
        {
            return $data;
        }
        $data['carts']  =   $carts;
        foreach ($carts as $store_id => $cart)
        {
            $data['status']['quantity'] += $cart['quantity'];
            $data['status']['amount']   += $cart['amount'];
            $data['status']['amount_exchange']   += $cart['amount_exchange'];//总积分 by PwordC
            $data['status']['kinds']    += $cart['kinds'];
        }

        return $data;
    }

    /**
     *    购物车为空
     *
     *    @author    Garbin
     *    @return    void
     */
    function _cart_empty()
    {
		$this->_get_curlocal_title('cart');
        $this->display('cart.empty.html');
    }

    /**
     *    以购物车为单位获取购物车列表及商品项
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_carts($store_id = 0)
    {
        $carts = array();

        /* 获取所有购物车中的内容 */
        $where_store_id = $store_id ? ' AND cart.store_id=' . $store_id : '';

        /* 只有是自己购物车的项目才能购买 */
        $where_user_id = $this->visitor->get('user_id') ? " AND cart.user_id=" . $this->visitor->get('user_id') : '';
        $cart_model =& m('cart');
        $cart_items = $cart_model->find(array(
            'conditions'    => 'session_id = \'' . SESS_ID . "'" . $where_store_id . $where_user_id,
            'fields'        => 'this.*,store.store_name',
            'join'          => 'belongs_to_store',
        ));
        if (empty($cart_items))
        {
            return $carts;
        }
		
		$allAmount = 0;
		//增加总积分 by PwordC
		$allAmount_exchange = 0;
        $kinds = array();
        foreach ($cart_items as $item)
        {
            /* 小计 */
            $item['subtotal']   = $item['price'] * $item['quantity'];
            //增加所需积分统计 by PwordC
            $item['subtotal_exchange'] = $item['max_exchange'] * $item['quantity'];
            $kinds[$item['store_id']][$item['goods_id']] = 1;

            /* 以店铺ID为索引 */
            empty($item['goods_image']) && $item['goods_image'] = Conf::get('default_goods_image');
            $carts[$item['store_id']]['store_name'] = $item['store_name'];
            $carts[$item['store_id']]['amount']     += $item['subtotal'];   //各店铺的总金额
            $carts[$item['store_id']]['amount_exchange']     += $item['subtotal_exchange'];//各店铺的总积分 by PwordC
            $carts[$item['store_id']]['quantity']   += $item['quantity'];   //各店铺的总数量
            $carts[$item['store_id']]['goods'][]    = $item;
			
			// 购物车中所有商品的总金额
			$allAmount += $item['subtotal'];
			$allAmount_exchange += $item['subtotal_exchange'];
        }
		
		
        foreach ($carts as $_store_id => $cart)
        {
            $carts[$_store_id]['kinds'] =   count(array_keys($kinds[$_store_id]));  //各店铺的商品种类数
        }

        return array($carts, $allAmount,$allAmount_exchange);
    }
    /**
     *    获取会员价
     *
     *    @author    PwordC
     *    @param     
     *    @return    
     */
    function get_vip_price(){
        //获取配置信息
        $status = $this->_get_vip_price_setting('status');
        if ($status ==0 ){
            return false;
        }
	    $config = $this->_get_vip_price_setting('config');
	    $config = unserialize($config);
	    $member_type = $this->_get_member_type();
	    //获取对应等级价格
	    if ($member_type ==0){
	        return false;
	    }else {
	        if ($config){
	            foreach ($config as $k=>$v){
	                if ($v==$member_type){
	                    return $k;
	                }
	            }
	        }
	        
	    }
    }
}

?>
