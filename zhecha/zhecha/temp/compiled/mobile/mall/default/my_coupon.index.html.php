<?php echo $this->fetch('member.header.html'); ?>
<div id="page-my-coupon" class="mb10">
	<div class="page-body my-coupon">
		<div class="record-coupon clearfix">
			<a href="mobile/index.php?app=my_coupon&act=bind" class="float-right btn">优惠券登记</a>
		</div>
		<?php $_from = $this->_var['coupons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'coupon');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['coupon']):
        $this->_foreach['v']['iteration']++;
?>
		<dl style="margin-bottom:15px;" class="inclusive-box f66">
			<dd><label>优惠券号码 :</label><span>  <?php echo $this->_var['coupon']['coupon_sn']; ?></span></dd>
			<dd><label>支付方式 :</label><span> <?php if ($this->_var['coupon']['coupon_value']): ?><?php echo $this->_var['coupon']['coupon_value']; ?><?php else: ?>没有限制<?php endif; ?></span></dd>
			<dd><label>使用期限 :</label><span>  <?php echo local_date("Y-m-d",$this->_var['coupon']['start_time']); ?> 至 <?php if ($this->_var['coupon']['end_time']): ?><?php echo local_date("Y-m-d",$this->_var['coupon']['end_time']); ?><?php else: ?>没有限制<?php endif; ?></span></dd>
			<dd><label>使用条件 :</label><span> <?php if ($this->_var['coupon']['min_amount']): ?><?php echo sprintf('一次购物满 %s ', $this->_var['coupon']['min_amount']); ?><?php else: ?>没有限制<?php endif; ?></span></dd>
            <dd><label>发放店铺 :</label><span> <a href="<?php echo url('app=store&id=' . $this->_var['coupon']['store_id']. ''); ?>"><?php echo $this->_var['coupon']['store_name']; ?></a></span></dd>
            <dd><label>可用次数 :</label><span>  <?php if ($this->_var['coupon']['remain_times'] == - 1): ?>没有限制<?php else: ?><?php echo $this->_var['coupon']['remain_times']; ?><?php endif; ?></span></dd>
			<dd><label>是否有效 :</label><span>  <?php if ($this->_var['coupon']['valid']): ?><img src="<?php echo $this->res_base . "/" . 'images'; ?>/usable.gif" align="absMiddle" /><?php else: ?><img src="<?php echo $this->res_base . "/" . 'images'; ?>/unusable.gif" align="absMiddle"/><?php endif; ?></span></dd>
		</dl> 
		<div class="margin10">
			<a href="javascript:;" class="J_AjaxRequest btn-alipay" confirm="您确定要删除它吗？" ret_url="<?php echo url('app=my_coupon'); ?>" confirm="dd" action="<?php echo url('app=my_coupon&act=drop&id=' . $this->_var['coupon']['coupon_sn']. ''); ?>">删除</a>
		</div>   
		<?php endforeach; else: ?>
        <div class="no-record">没有符合条件的记录</div>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</div>
    <?php if ($this->_var['coupons']): ?>
    <?php echo $this->fetch('page.bottom.html'); ?>
    <?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>