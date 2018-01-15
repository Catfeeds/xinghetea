<?php echo $this->fetch('member.header.html'); ?>
<div id="page-buyer-order">
	<div class="buyer-order-body page-body"> 
		<?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['order']):
?>
		<div class="order-form mb10">
			<h2 class="clearfix">
				<p class="name"><label class="ml10"><input type="checkbox" value="<?php echo $this->_var['order']['order_id']; ?>" class="checkitem J_CheckItem" <?php if ($this->_var['order']['status'] != ORDER_PENDING && $this->_var['order']['status'] != ORDER_SUBMITTED): ?> disabled="disabled" <?php endif; ?> /><a><?php echo htmlspecialchars($this->_var['order']['seller_name']); ?></a></label></p>
				<p class="num ml10">订单号: <?php echo $this->_var['order']['order_sn']; ?><?php if ($this->_var['order']['extension'] == 'groupbuy'): ?><span class="color8">[团购]</span><?php endif; ?></p>
			</h2>
			
			<?php $_from = $this->_var['order']['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
			<div <?php if (! ($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?>style="border-bottom-style:dashed"<?php endif; ?> class="con clearfix">
				<div class="pic float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['order']['seller_id']. ''); ?>" ><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="50" height="50"  /></a></div>
				<div class="txt float-left ml5"> 
					<p><a href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></p>
					<?php if ($this->_var['goods']['specification']): ?>
					<span class="attr mt5"><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></span><br />
					<?php endif; ?>
					<span class="order-amount mt5"><?php echo price_format($this->_var['goods']['price']); ?> x <?php echo $this->_var['goods']['quantity']; ?>件</span>
				</div>
			</div>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
			<?php $_from = $this->_var['order']['order_gift']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
			<div class="con clearfix">
				<div class="pic float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['order']['seller_id']. ''); ?>" ><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50"  /></a></div>
				<div class="txt float-left ml5"> 
					<p class="attr mt5 fs14"><a href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></p>
					<span class="mt5 gray fs14"><?php echo price_format($this->_var['goods']['price']); ?> x <?php echo $this->_var['goods']['quantity']; ?>件</span><br />
					<em class="label-gift">赠品</em>
				</div>
			</div>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			
			<p class="status clearfix">
				<em>
					<?php echo call_user_func("order_status",$this->_var['order']['status']); ?><?php if ($this->_var['order']['evaluation_status']): ?>&nbsp;我已评价<?php endif; ?> 
					<?php if ($this->_var['order']['refund_status'] == 'SUCCESS'): ?>
					<span class="ml10" style="color:#096;">退款成功</span> 
					<?php elseif ($this->_var['order']['refund_status'] == 'CLOSED'): ?> 
					<span class="ml10 gray">退款关闭</span> 
					<?php elseif ($this->_var['order']['refund_status']): ?> 
					<span class="ml10 f60">退款中</span>
					<?php endif; ?>
				</em>
                <span class="float-right pr10 fs14 f60">合计：<?php echo price_format($this->_var['order']['order_amount']); ?></span>
			</p>
			<div class="operate-btn pt10">
				
				<a class="btn1 mr10" href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>" >查看订单</a> 
				
				<a class="btn1 mr10" href="<?php echo url('app=cashier&order_id=' . $this->_var['order']['order_id']. ''); ?>"  id="order<?php echo $this->_var['order']['order_id']; ?>_action_pay" <?php if ($this->_var['order']['status'] != ORDER_PENDING): ?> style="display:none"<?php endif; ?> >付款</a>
            	
                <a class="btn1 mr10" href="<?php echo url('app=buyer_order&act=confirm_order&order_id=' . $this->_var['order']['order_id']. ''); ?>" <?php if ($this->_var['order']['status'] != ORDER_SHIPPED): ?> style="display:none"<?php endif; ?>>确认收货</a>
				 
                <a class="btn1 mr10" href="<?php echo url('app=buyer_order&act=cancel_order&order_id=' . $this->_var['order']['order_id']. ''); ?>" <?php if ($this->_var['order']['status'] != ORDER_PENDING && $this->_var['order']['status'] != ORDER_SUBMITTED): ?> style="display:none"<?php endif; ?>>取消订单</a>
                		
				<?php if ($this->_var['enable_express']): ?> 
				<a class="btn1 mr10" href="<?php echo url('app=order_express&order_id=' . $this->_var['order']['order_id']. ''); ?>" <?php if ($this->_var['order']['status'] != ORDER_SHIPPED && $this->_var['order']['status'] != ORDER_FINISHED): ?> style="display:none"<?php endif; ?>>查看物流</a> 
				<?php endif; ?> 
				
				<a class="btn1 mr10" href="<?php echo url('app=buyer_order&act=evaluate&order_id=' . $this->_var['order']['order_id']. ''); ?>"  id="order<?php echo $this->_var['order']['order_id']; ?>_evaluate" <?php if ($this->_var['order']['status'] != ORDER_FINISHED || $this->_var['order']['evaluation_status'] != 0): ?>style="display:none"<?php endif; ?>>我要评价</a>
 
                <?php if ($this->_var['order']['payment_code'] != 'cod'): ?>
				<?php if ($this->_var['order']['refund_status']): ?>
                <a class="btn1 mr10" href="<?php echo url('app=refund&act=view&refund_id=' . $this->_var['order']['refund_id']. ''); ?>">退款详情</a>
				<?php elseif (( in_array ( $this->_var['order']['status'] , array ( 20 , 30 ) ) )): ?>
				<a class="btn1 mr10" href="<?php echo url('app=refund&act=add&order_id=' . $this->_var['order']['order_id']. ''); ?>">退款/退货</a>
				<?php endif; ?>
                <?php endif; ?>
				
			</div>
		</div>
		<?php endforeach; else: ?>
		<div class="no-record"> <span>没有符合条件的订单</span> </div>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	</div>
	<?php if ($this->_var['orders']): ?> 
    <div class="batchopt J_BatchOpt clearfix hidden"><a href="javascript:;" class="delete J_BatchCancel"  uri="<?php echo url('app=buyer_order&act=cancel_order'); ?>" name="order_id">取消订单</a> <a href="javascript:;" class="mergepay J_MergePay" uri="<?php echo url('app=cashier'); ?>" name="order_id">合并付款</a></div>
	<?php echo $this->fetch('page.bottom.html'); ?> 
	<?php endif; ?> 
</div>
<?php echo $this->fetch('footer.html'); ?> 