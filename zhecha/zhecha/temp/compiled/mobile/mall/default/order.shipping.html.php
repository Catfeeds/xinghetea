<script type="text/javascript">
	var shippings = <?php echo $this->_var['shippings']; ?>;
	var addresses = <?php echo $this->_var['addresses']; ?>;
	var integralExchangeRate 	= <?php echo ($this->_var['goods_info']['integralExchange']['rate'] == '') ? '0' : $this->_var['goods_info']['integralExchange']['rate']; ?>;
	var integralMaxPoints 		= <?php echo ($this->_var['goods_info']['integralExchange']['maxPoints'] == '') ? '0' : $this->_var['goods_info']['integralExchange']['maxPoints']; ?>;
	$(function(){
		
		/* 订单总额初始化 */
		fill_order_amount();
					
		/*  收货地址初始化 */
		fill_address_info($('.J_AddressEach').find('input[name="addr_id"]').val());
					 
		$('.J_LogistFeesSelect').on('change', function(){
			fill_order_amount();
		});
		
		$('.J_AddressEach').click(function(){
			$(this).parent().children().removeClass('selected_address');
			$(this).addClass('selected_address');
			$(this).parent().children().find('input[name="addr_id"]').prop('checked' ,false);
			$(this).find('input[name="addr_id"]').prop('checked', true);
			
			var addr_id = $(this).find('input[name="addr_id"]').val();
			
			/* 选中地址后，自动收缩地址列表 */
			select_ship_address();
			
			/* 加载该收货地址对应的运费 */
			fill_logist_fee_by_address(addr_id);
						
			/* 赋值收货地址 */
			//fill_address_info(addr_id);
						
			/* 更新订单总额 */
			fill_order_amount();
						
		});
		
		$('.J_GrowBuy').click(function(){
			
			// 重置优惠券和积分
			if($(this).prop('checked') == false) {
				$('.J_UseCouponSelect-'+$(this).parents('.J_Store').attr('store_id')).prop('selectedIndex', 0);
				$('.J_UseIntegralCheckbox').prop('checked', false);
				$('.J_IntegralAmount').val('').prop('disabled', true);
				$('.J_IntegralPrice').html('0.00');
			}
			fill_order_amount();
		});	
		
		$('.J_UseIntegralCheckbox').click(function(){
			$('.J_IntegralAmount').val('');
			if($(this).prop('checked')==true) {
				$('.J_IntegralAmount').val(integralMaxPoints);
			}
			$('.J_IntegralAmount').prop('disabled', $(this).attr('checked')==false);
			fill_order_amount();
		});

		$('.J_IntegralAmount').keyup(function(){
			if(isNaN($(this).val())) {
				layer.open({content: "积分值必须是数字", time: 3});
				$(this).val(integralMaxPoints);  
				$(this).select();
			}
			if(parseFloat($(this).val()) < 0)
			{
				layer.open({content: "积分值不能为负数", time: 3});
				$(this).val(integralMaxPoints);
				$(this).select();  
			}
			else
			{
				if($(this).val().toString().indexOf('.') > 0)
				{
					// 必须先判断是不是有点，再判断小数点位数，要不JS报错
					if($(this).val().toString().split(".")[1].length > 2)
					{
						$(this).val(number_format($(this).val(), 2));
					}
				}
			}
			fill_order_amount();
		});
		
		$('.J_MoreAddress').click(function(){
			select_ship_address();
		});
					
	});
	
	function select_ship_address()
	{
		$('.J_AddressEach').parent().find('dl').toggleClass('hidden');
		$('.J_AddressEach').parent().find('.selected_address').removeClass('hidden');
		if($('.J_AddressEach').parent().find('.hidden').length > 0) {
			$('.J_MoreAddress').show();
			//$('.J_MoreAddress').html('展开');
		} else {
			$('.J_MoreAddress').hide();
			//$('.J_MoreAddress').html('收起');
		}
	}
				
	/* 赋值收货地址 */
	function fill_address_info(addr_id)
	{
		address = addresses[addr_id];
		phone = address['phone_mob'];
		if(phone=='') phone = address['phone_tel'];
		$('.J_AddressDetail').html(address['region_name'] + ' ' + address['address']);
		$('.J_Consignee').html(address['consignee'] + ' ' + phone);
	}
				
	/* 加载该收货地址对应的运费 */
	function fill_logist_fee_by_address(addr_id)
	{
		$('.J_Store').each(function(){
			store_id = $(this).attr('store_id');
			$('.J_LogistFeesSelect-' + store_id).children().remove();
			shipping_data = shippings[store_id][addr_id];
				
			$.each(shipping_data,function(k,v) {
				html = '<option value="'+k+'" price="'+v.logist_fees+'">'+v.name+'：'+number_format(v.logist_fees,2)+'</option>';
				$('.J_LogistFeesSelect-' + store_id).append(html);
			});
		});
	}
	
	/* 设置总费用 */ 
	function fill_order_amount()
	{
		var order_amount, logist_fee, coupon_value, integral_value, mealprefer_value, fullprefer_value, growbuy_value,order_amount_exchange;//增加积分总计 by PwordC
		
		order_amount = integral_value =  order_amount_exchange = 0;
	
		$('.J_Store').each(function(index, element){
			store_id = $(this).attr('store_id');
			store_amount = goods_amount = logist_fee = coupon_value = growbuy_value = mealprefer_value = fullprefer_value = 0;
			//增加积分计算 by PwordC
			store_amount_exchange = goods_amount_exchange =0;
			
			$('.J_Subtotal-'+store_id).each(function(index, element) {
                goods_amount += parseFloat($(this).attr('price'));
                goods_amount_exchange += parseFloat($(this).attr('exchange'));//计算积分 by PwordC
            });
			
			$('.J_GrowBuy-' + store_id).each(function(index, element) {
				if($(this).prop('checked') == true) {
					growbuy_value += parseFloat($(this).attr('price'));
				}
			});
			
			//  如果存在搭配套餐
			if($('.J_MealPreferPrice-'+store_id).length > 0) {
				mealprefer_value = parseFloat($('.J_MealPreferPrice-'+store_id).html());
			}
			
			// 如果存在满折满减
			if($('.J_FullPreferPrice-'+store_id).length > 0) {
				fullprefer_value = parseFloat($('.J_FullPreferPrice-'+store_id).html());
			}
		
			logist_fee = parseFloat($('.J_LogistFeesSelect-'+store_id).find('option:selected').attr('price'));
						
			$('.J_LogistFees-'+store_id).html(number_format(logist_fee, 2));
			
			store_amount = goods_amount+growbuy_value-mealprefer_value-fullprefer_value+logist_fee;
			
			$('.J_UseCouponSelect-'+store_id+' option').each(function(index, element) {
                if($(this).attr('price') != undefined && (parseFloat($(this).attr('price')) > store_amount)) {
					$(this).prop('disabled', true);
				} else {
					$(this).prop('disabled', false);
				}
            });

			if($('.J_UseCouponSelect-'+store_id).val() != ''){
				coupon_value = parseFloat($('.J_UseCouponSelect-'+store_id).find('option:selected').attr('price'));
				store_amount -= coupon_value;
			}
			
			store_amount = goods_amount+growbuy_value-mealprefer_value-fullprefer_value+logist_fee-coupon_value;
			//计算总积分 by PwordC
			store_amount_exchange = goods_amount_exchange;
			
			$('.J_OrderAmount-'+store_id).html(number_format(store_amount.toFixed(2), 2));
			
			order_amount += parseFloat(store_amount);
			order_amount_exchange += parseFloat(store_amount_exchange);
			
		});

		<?php if ($this->_var['goods_info']['allow_integral']): ?>
		if($('.J_UseIntegralCheckbox').prop('checked')==true && $('.J_IntegralAmount').val()>0){
			usePoints = parseFloat($('.J_IntegralAmount').val());
			
			if(usePoints > integralMaxPoints) {
				usePoints = integralMaxPoints;
				layer.open({content: "积分值不合理", time: 3});
				$('.J_IntegralAmount').val(usePoints);
			}
			
			integral_value = (usePoints * integralExchangeRate).toFixed(4);
			if(integral_value > order_amount) {
				integral_value 	= order_amount;
				usePoints		= number_format((integral_value / integralExchangeRate).toFixed(2), 2);
				layer.open({content: "积分值不合理", time: 3});
				$('.J_IntegralAmount').val(usePoints);
			} 
			integral_value = parseFloat(integral_value).toFixed(2);
			$('.J_IntegralPrice').html(number_format(integral_value, 2));
			
		} else $('.J_IntegralPrice').html('0.00');
		<?php endif; ?>
	
		$('.J_OrderAmount').html(number_format((order_amount-integral_value).toFixed(2), 2)+ '+' + order_amount_exchange + '积分');
	}
</script>

<div id="select-address">
	<div class="title mb10">
		<!--<b class="fs14">收货人地址</b> <a href="<?php echo url('app=my_address'); ?>" target="_blank">[管理收货地址]</a>-->
	</div>
	<?php if ($this->_var['my_address']): ?>
	<div class="oldaddress clearfix">
		<?php $_from = $this->_var['my_address']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'address');$this->_foreach['fe_address'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_address']['total'] > 0):
    foreach ($_from AS $this->_var['address']):
        $this->_foreach['fe_address']['iteration']++;
?>
		<dl class="clearfix <?php if (($this->_foreach['fe_address']['iteration'] <= 1)): ?> selected_address<?php endif; ?> <?php if (! ($this->_foreach['fe_address']['iteration'] <= 1)): ?> hidden <?php endif; ?> J_AddressEach" >
			<dt>
				<input type="checkbox" name="addr_id" value="<?php echo $this->_var['address']['addr_id']; ?>" <?php if (($this->_foreach['fe_address']['iteration'] <= 1)): ?> checked="checked" <?php endif; ?>/>
				<b>收货人：<?php echo htmlspecialchars($this->_var['address']['consignee']); ?> <?php if ($this->_var['address']['phone_mob']): ?><?php echo $this->_var['address']['phone_mob']; ?><?php else: ?><?php echo $this->_var['address']['phone_tel']; ?><?php endif; ?> </b>
			</dt>
			<dd class="addr-bd"> <?php echo $this->_var['address']['region_name']; ?> <?php echo htmlspecialchars($this->_var['address']['address']); ?></dd>
		</dl>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</div>
	<?php endif; ?>
	<div class="use-new-address clearfix">
		<a href="<?php echo url('app=my_address&act=add'); ?>" class="btn-new-addr" >使用新地址</a>
		<a href="javascript:;" class="btn-more-addr J_MoreAddress" >&nbsp;</a>
	</div>
</div>
