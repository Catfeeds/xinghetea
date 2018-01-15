<?php

include_once(ROOT_PATH . '/includes/ordertypes/normal.otype.php');

/**
 *    团购的订单
 *
 *    @author    Garbin
 *    @usage    none
 */
class GroupbuyOrder extends NormalOrder
{
    var $_name = 'groupbuy';
	
	// 获取团购中的商品数据，用来计算订单可使用的最大积分值
	function getOrderGoodsInfo($user_id = 0, $extraParams = array())
	{
		extract($extraParams);

		$groupbuy_items = array();
		
		$model_groupbuy =& m('groupbuy');
  		$groupbuy_info = $model_groupbuy->get(array(
			'join'  => 'be_join, belong_store, belong_goods',
			'conditions'    => $model_groupbuy->getRealFields("groupbuy_log.user_id={$user_id} AND groupbuy_log.group_id={$group_id} AND groupbuy_log.order_id=0 AND this.state=" . GROUP_FINISHED),
			'fields'    => 'store.store_id, store.store_name, store.im_qq, store.sgrade, goods.goods_id, goods.goods_name, goods.default_image, groupbuy_log.quantity, groupbuy_log.spec_quantity, this.spec_price',
		));
		if (!empty($groupbuy_info))
		{
			/* 库存信息 */
			$model_goodsspec = &m('goodsspec');
			$goodsspec = $model_goodsspec->find('goods_id='. $groupbuy_info['goods_id']);
	
			/* 获取商品信息 */
			$spec_quantity = unserialize($groupbuy_info['spec_quantity']);
			$spec_price    = unserialize($groupbuy_info['spec_price']);
	
			$goods_image = empty($groupbuy_info['default_image']) ? Conf::get('default_goods_image') : $groupbuy_info['default_image'];
			foreach ($spec_quantity as $spec_id => $spec_info)
			{
				$the_price = $spec_price[$spec_id]['price'];
				$subtotal = $spec_info['qty'] * $the_price;
				$groupbuy_items[$group_id] = array(
					'goods_id'  => $groupbuy_info['goods_id'],
					'goods_name'  => $groupbuy_info['goods_name'],
					'spec_id'  => $spec_id,
					'specification'  => $spec_info['spec'],
					'price'  => $the_price,
					'quantity'  => $spec_info['qty'],
					'goods_image'  => $goods_image,
					'subtotal'  => $subtotal,
					'stock' => $goodsspec[$spec_id]['stock'],
					'store_id' => $groupbuy_info['store_id']
				);
			}
     	}
		return array($groupbuy_items);
	}
}

?>
