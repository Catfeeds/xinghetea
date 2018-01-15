<?php echo $this->fetch('member.header.html'); ?>
<div class="mt10">
	<div class="user-integral-info clearfix">
		<div class="valid user-integral">
			<div class="wrap"> <b class="des">可用的积分</b> <b class="piont red"><?php echo ($this->_var['integral_piont'] == '') ? '0' : $this->_var['integral_piont']; ?></b> </div>
		</div>
		<div class="invalid user-integral">
			<div class="wrap" style="border:0px;"> <b class="des">冻结的积分</b> <b class="piont"><?php echo ($this->_var['frozen_integral'] == '') ? '0' : $this->_var['frozen_integral']; ?></b> </div>
		</div>
	</div>
	<div class="integral_sort">
		<ul class="clearfix">
			<li><a href="<?php echo url('app=my_integral'); ?>" <?php if ($_GET['type'] == ''): ?>class="active"<?php endif; ?>><span>所有明细</span></a></li>
			<li><a href="<?php echo url('app=my_integral&type=integral_income'); ?>" <?php if ($_GET['type'] == 'integral_income'): ?>class="active"<?php endif; ?>><span>收入</span></a></li>
			<li><a href="<?php echo url('app=my_integral&type=integral_pay'); ?>" <?php if ($_GET['type'] == 'integral_pay'): ?>class="active"<?php endif; ?>><span>支出</span></a></li>
		</ul>
	</div>
	<div class="user-integral-detail pt10 bgf">
		<div class="tt clearfix"> <span class="notes"><em>名称</em></span><span class="what"><em>积分变化</em></span><span class="when"><em>日期</em></span></div>
		<?php if ($this->_var['integral_log']): ?>
		<ul class="ct">
			<?php $_from = $this->_var['integral_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'integral');$this->_foreach['fe_log'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_log']['total'] > 0):
    foreach ($_from AS $this->_var['integral']):
        $this->_foreach['fe_log']['iteration']++;
?>
			<li class="it clearfix" <?php if (($this->_foreach['fe_log']['iteration'] == $this->_foreach['fe_log']['total'])): ?> style="border-bottom:0;"<?php endif; ?>>
				<div class="col notes"><em><?php echo $this->_var['integral']['name']; ?></em></div>
				<div class="col what"> <em> 
					<?php if ($this->_var['integral']['integral_change'] > 0): ?> 
					<span class="plus">+<?php echo $this->_var['integral']['changes']; ?></span> 
					<?php else: ?> 
					<span class="minus"><?php echo $this->_var['integral']['changes']; ?></span> 
					<?php endif; ?> 
					</em> </div>
				<div class="col when"><em><?php echo local_date("Y-m-d H:i:s",$this->_var['integral']['add_time']); ?></em></div>
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<?php else: ?>
		<div class="notice-word mt10"><p>没有符合条件的记录</p></div>
		<?php endif; ?>
	</div>
</div>
<?php echo $this->fetch('page.bottom.html'); ?>
<?php echo $this->fetch('footer.html'); ?>

