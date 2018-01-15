<?php echo $this->fetch('member.header.html'); ?>
<div id="page-buyer-groupbuy">
	<div class="page-body buyer-groupbuy"> 
		<?php $_from = $this->_var['groupbuy_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'group');$this->_foreach['_group_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_group_f']['total'] > 0):
    foreach ($_from AS $this->_var['group']):
        $this->_foreach['_group_f']['iteration']++;
?> 
		<dl class="seller-info inclusive-box f66 clearfix">
			<dd class="clearfix">
				<label>活动名称 :</label><span><?php echo htmlspecialchars($this->_var['group']['group_name']); ?></span></dd>
			<dd class="clearfix">
				<label>活动状态 :</label>
				<span><?php echo call_user_func("group_state",$this->_var['group']['state']); ?></span></dd>
			<dd class="clearfix">
				<label>结束日期 :</label>
				<span><?php echo local_date("Y-m-d",$this->_var['group']['end_time']); ?></span></dd>
			<dd class="clearfix">
				<label>成团数 :</label>
				<span><?php echo $this->_var['group']['min_quantity']; ?></span></dd>
			<dd class="clearfix">
				<label>订购数 :</label>
				<span><?php echo $this->_var['group']['quantity']; ?></span></dd>
			<dd class="clearfix">
				<label>订购详情 :</label>
				<span><?php $_from = $this->_var['group']['spec_quantity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
				<?php if ($this->_var['spec']['qty'] > 0): ?><?php if ($this->_var['spec']['spec']): ?><?php echo $this->_var['spec']['spec']; ?><?php else: ?>默认规格<?php endif; ?>: <?php echo $this->_var['spec']['qty']; ?>件<?php endif; ?><br />
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </span> </dd>
		</dl>
		
		<div class="btn-box"> 
		<?php $_from = $this->_var['group']['ican']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'name');if (count($_from)):
    foreach ($_from AS $this->_var['name']):
?>
			<?php if ($this->_var['name'] == 'view'): ?> <a target="_blank" href="<?php echo url('app=groupbuy&id=' . $this->_var['group']['group_id']. ''); ?>" style="display:none">查看</a> 
			<?php elseif ($this->_var['name'] == 'buy'): ?> <a target="_blank" href="<?php echo url('app=order&goods=groupbuy&group_id=' . $this->_var['group']['group_id']. ''); ?>">购买</a> 
			<?php elseif ($this->_var['name'] == 'view_order'): ?> <a target="_blank" href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['group']['order_id']. ''); ?>">查看订单</a> 
			<?php elseif ($this->_var['name'] == 'exit_group'): ?> <a href="javascript:drop_confirm('您确定要退出该团购活动吗？','<?php echo url('app=buyer_groupbuy&act=exit_group&id=' . $this->_var['group']['group_id']. ''); ?>')">退出团购</a> <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<?php endforeach; else: ?>
		<div class="notice-word mt10"><p>没有符合条件的记录</p></div>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>