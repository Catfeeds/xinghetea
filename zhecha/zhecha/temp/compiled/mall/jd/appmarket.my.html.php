<?php echo $this->fetch('member.header.html'); ?>
<div id="page-promotool" class="page-promotool">
	<div class="content">
		<div class="totline"></div>
		<div class="botline"></div>
		<?php echo $this->fetch('member.menu.html'); ?>
		<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
			<?php echo $this->fetch('member.submenu.html'); ?>
			<div class="wrap">
				<div class="public_select">
					<div class="appmarket apprenewal">
						<div class="bundle bundle-list">
							<div class="applist">
								<?php if ($this->_var['apprenewal']): ?>
								<ul class="list-each clearfix">
									<?php $_from = $this->_var['apprenewal']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?>
									<li class="clearfix">
										<div class="pic"><a href="<?php echo url('app=appmarket&act=view&id=' . $this->_var['item']['aid']. ''); ?>" target="_blank"><img height="70"  src="<?php echo $this->_var['item']['logo']; ?>" /></a></div>
										<div class="info">
											<p class="title"><font class="f60">[<?php echo $this->_var['lang'][$this->_var['item']['appid']]; ?>]</font> <?php echo $this->_var['item']['title']; ?></p>
											<p class="price clearfix"> 
												<span class="gray fs14">购买时间</span> <strong class="mr10"><?php echo local_date("Y-m-d",$this->_var['item']['add_time']); ?></strong>
												<?php if ($this->_var['item']['timediff']): ?>
												<span class="gray fs14">到期时间</span> <strong><?php echo local_date("Y-m-d",$this->_var['item']['expired']); ?></strong> 
												<span class="fs14 ml10" style="color:#900"><?php echo $this->_var['item']['timediff']['format']; ?></span>
												<?php else: ?> 
												<span class="gray fs14">已过期</span>
												<?php endif; ?> 
											</p>
											<p class="btn-fields mt10 clearfix"> <a class="btn-buy" href="<?php echo url('app=appmarket&act=view&id=' . $this->_var['item']['aid']. ''); ?>" target="_blank">
												<?php if (! $this->_var['item']['checkIsRenewal']): ?> 
												购买 
												<?php else: ?> 
												续费 
												<?php endif; ?> 
												</a> 
												
											</p>
										</div>
									</li>
									<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
								</ul>
								<div class="mt20 clearfix"><?php echo $this->fetch('member.page.bottom.html'); ?></div>
								<?php else: ?>
								<div class="notice-word"><p>没有符合条件的记录</p></div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 