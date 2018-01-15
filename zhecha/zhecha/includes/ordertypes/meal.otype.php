<?php

include_once(ROOT_PATH . '/includes/ordertypes/normal.otype.php');

/**
 *    套餐的订单
 *
 *    @author    Garbin
 *    @usage    none
 */
class MealOrder extends NormalOrder
{
    var $_name = 'meal';
	
	// 获取购物车中的商品数据，用来计算订单可使用的最大积分值
	function getOrderGoodsInfo($user_id = 0, $extraParams = array())
	{
		extract($extraParams);
		
		$amount = 0;
		$meal_items = array();
		
		if(!$meal_id || !$user_id || !$specs)
		{
			return false;
		}
		$model_goodsspec = &m('goodsspec');
		$model_meal = &m('meal');
		$meal = $model_meal->findAll(array(
			'conditions' => 'meal_id='.intval($meal_id),
			'include' => array('has_mealgoods'),
		));
		if(!$meal)
		{
			return false;
		}
		
		$meal = current($meal);
		
		$meal_goods = $meal['meal_goods'];
				
		// 记录套餐商品
		foreach($meal_goods as $goods)
		{
			$check_goods_1[] = $goods['goods_id'];
		}
		
		//  记录客户选中的商品规格
		foreach($specs as $rec_id => $spec_id)
		{
			$goods = $model_goodsspec->get(array(
				'conditions' => 'spec_id='.intval($spec_id),
				'join'		 => 'belongs_to_goods', 
				'fields'	 => 'this.*,goods_name,default_image as goods_image,spec_name_1,spec_name_2'//去掉store_id属性，兼容商品共用，by PwordC
			));
			//重新赋值商品store_id，by PwordC
			$goods['store_id'] = $meal['user_id'];
			if($goods)
			{
				$goods['quantity'] = 1;//  套餐商品默认都是购买一件
				!empty($goods['spec_1']) && $goods['specification'] = $goods['spec_name_1'] . ':' . $goods['spec_1'];	
				!empty($goods['spec_2']) && $goods['specification'] .= ' ' . $goods['spec_name_2'] . ':' . $goods['spec_2']; 
				
				$meal_items[$rec_id] = $goods;
				$check_goods_2[] = $goods['goods_id'];
			}
		}
		sort($check_goods_1);
		sort($check_goods_2);
				
		/* 买家选择购买的商品和套餐商品不一致 */
		if(array_diff($check_goods_1, $check_goods_2) || array_diff($check_goods_2, $check_goods_1))
		{
			return false;
		}
		
		return array($meal_items, $meal);
	}
}

?>
