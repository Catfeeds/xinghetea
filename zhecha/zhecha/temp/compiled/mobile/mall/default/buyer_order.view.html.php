<?php echo $this->fetch('member.header.html'); ?>
<div id="page-order-view">
	<div class="page-body">
		<div class="order-goods"> 
			<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
			<div class="list clearfix">
				<div class="pic float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['order']['seller_id']. ''); ?>"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="50" height="50" class="block" /></a></div>
				<div class="text float-left"> <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['order']['seller_id']. ''); ?>">
					<h3><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h3>
					<span><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></span> </a> </div>
				<div class="pri float-right"> <?php echo price_format($this->_var['goods']['price']); ?><br />x <?php echo $this->_var['goods']['quantity']; ?> </div>
			</div>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
			<?php $_from = $this->_var['gift_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
			<div class="list clearfix">
				<div class="pic float-left"><a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>"><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50" class="block" /></a></div>
				<div class="text float-left"> <a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>">
					<h3><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h3>
					<span><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></span> </a> <em class="label-gift">赠品</em></div>
				<div class="pri float-right"> <?php echo price_format($this->_var['goods']['price']); ?><br />x <?php echo $this->_var['goods']['quantity']; ?></div>
			</div>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
			<div class="o-g-bt">
				<p> 运费： <span><?php echo price_format($this->_var['order_extm']['shipping_fee']); ?>(<?php echo htmlspecialchars($this->_var['order_extm']['shipping_name']); ?>)</span><br />
					优惠： <span><?php echo price_format($this->_var['order']['discount']); ?></span><br />
					付款：<span><?php echo price_format($this->_var['order']['order_amount']); ?></span> </p>
				<h3><?php echo call_user_func("order_status",$this->_var['order']['status']); ?></h3>
				<a href="<?php echo url('app=buyer_order'); ?>" class="btn">返回订单列表</a> </div>
		</div>
		<div class="seller-info inclusive-box f66">
			<dl>
				<dt>卖家信息</dt>
				<dd>
					<label>店铺名 :</label>
					<span> <?php echo htmlspecialchars($this->_var['order']['store_name']); ?></span></dd>
				<dd>
					<label>电话号码 :</label>
					<!--<span> <?php echo (htmlspecialchars($this->_var['order']['tel']) == '') ? '-' : htmlspecialchars($this->_var['order']['tel']); ?></span>--><a href="tel:<?php echo $this->_var['order']['tel']; ?>" style="padding-left:20px;background:url(<?php echo $this->res_base . "/" . 'images/botel.png'; ?>) no-repeat;">拨打电话</a></dd>
				<dd>
					<label>手&nbsp;&nbsp;&nbsp;机 :</label>
					<!--<span> <?php echo (htmlspecialchars($this->_var['order']['phone_mob']) == '') ? '-' : htmlspecialchars($this->_var['order']['phone_mob']); ?></span>--><a href="tel:<?php echo $this->_var['order']['phone_mob']; ?>" style="padding-left:20px;background:url(<?php echo $this->res_base . "/" . 'images/bophone.png'; ?>) no-repeat;">拨打手机</a></dd>
				<dd>
					<label>详细地址 :</label>
					<span> <?php echo (htmlspecialchars($this->_var['order']['address']) == '') ? '-' : htmlspecialchars($this->_var['order']['address']); ?></span></dd>
			</dl>
		</div>
		<div class="shipping-info inclusive-box f66">
			<dl>
				<dt>订单信息</dt>
				<dd>
					<label>订单号 :</label>
					<span> <?php echo $this->_var['order']['order_sn']; ?></span></dd>
				<dd>
					<label>支付方式 :</label>
					<span> <?php echo htmlspecialchars($this->_var['order']['payment_name']); ?></span></dd>
				<dd>
					<label>配送方式 :</label>
					<span> <?php echo htmlspecialchars($this->_var['order_extm']['shipping_name']); ?></span></dd>
				<dd>
					<label>上架 :</label>
					<span> <?php echo (local_date("Y-m-d H:i:s",$this->_var['order']['order_add_time']) == '') ? '-' : local_date("Y-m-d H:i:s",$this->_var['order']['order_add_time']); ?></span></dd>
				<?php if ($this->_var['order']['pay_time']): ?>
				<dd>
					<label>支付时间 :</label>
					<span> <?php echo (local_date("Y-m-d H:i:s",$this->_var['order']['pay_time']) == '') ? '-' : local_date("Y-m-d H:i:s",$this->_var['order']['pay_time']); ?></span></dd>
				<?php endif; ?> 
				<?php if ($this->_var['order']['ship_time']): ?>
				<dd>
					<label>发货时间 :</label>
					<span> <?php echo (local_date("Y-m-d H:i:s",$this->_var['order']['ship_time']) == '') ? '-' : local_date("Y-m-d H:i:s",$this->_var['order']['ship_time']); ?></span></dd>
				<?php endif; ?> 
				<?php if ($this->_var['order']['finished_time']): ?>
				<dd>
					<label>完成时间 :</label>
					<span> <?php echo (local_date("Y-m-d H:i:s",$this->_var['order']['finished_time']) == '') ? '-' : local_date("Y-m-d H:i:s",$this->_var['order']['finished_time']); ?></span></dd>
				<?php endif; ?> 
				<?php if ($this->_var['order']['postscript']): ?>
				<dd>
					<label>给卖家的附言 :</label>
					<span> <?php echo (htmlspecialchars($this->_var['order']['postscript']) == '') ? '-' : htmlspecialchars($this->_var['order']['postscript']); ?></span></dd>
				<?php endif; ?>
			</dl>
		</div>
		<div style="margin-bottom:15px;"  class="consignee_info inclusive-box f66">
			<dl>
				<dt>收货人及物流信息</dt>
				<dd>
					<label>收货地址 :</label>
					<span> <?php echo htmlspecialchars($this->_var['order_extm']['address']); ?></span></dd>
				<dd>
					<label>邮政编码 :</label>
					<span> <?php echo ($this->_var['order_extm']['zipcode'] == '') ? '-' : $this->_var['order_extm']['zipcode']; ?></span></dd>
				<dd>
					<label>收货人姓名 :</label>
					<span> <?php echo (htmlspecialchars($this->_var['order_extm']['consignee']) == '') ? '-' : htmlspecialchars($this->_var['order_extm']['consignee']); ?></span></dd>
				<dd>
					<label>电话号码 :</label>
					<!--<span> <?php echo ($this->_var['order_extm']['phone_tel'] == '') ? '-' : $this->_var['order_extm']['phone_tel']; ?></span>--><a href="tel:<?php echo $this->_var['order_extm']['phone_tel']; ?>" style="padding-left:20px;background:url(<?php echo $this->res_base . "/" . 'images/botel.png'; ?>) no-repeat;">拨打电话</a></dd>
				<dd>
					<label>手&nbsp;&nbsp;&nbsp;机 :</label>
					<!--<span> <?php echo ($this->_var['order_extm']['phone_mob'] == '') ? '-' : $this->_var['order_extm']['phone_mob']; ?></span>--><a href="tel:<?php echo $this->_var['order_extm']['phone_mob']; ?>" style="padding-left:20px;background:url(<?php echo $this->res_base . "/" . 'images/bophone.png'; ?>) no-repeat;">拨打手机</a></dd>
			</dl>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?> 