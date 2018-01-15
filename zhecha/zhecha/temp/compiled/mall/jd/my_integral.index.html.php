<?php echo $this->fetch('member.header.html'); ?>
<div class="content"> <?php echo $this->fetch('member.menu.html'); ?>
	<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<div class="public_index table1">
				<div class="user-integral-info mb10">
					<div class="valid user-integral"> <b class="des">可用的积分</b> <b class="piont"><?php echo ($this->_var['integral_piont'] == '') ? '0' : $this->_var['integral_piont']; ?></b> </div>
					<div style="border:0px;" class="invalid user-integral"> <b class="des">冻结的积分</b> <b class="piont decr"><?php echo ($this->_var['frozen_integral'] == '') ? '0' : $this->_var['frozen_integral']; ?></b> </div>
					<div class="clear"></div>
				</div>
				<?php if ($this->_var['integral_log']): ?>
				<div class="user-integral-detail mb10">
					<table>
						<tr>
							<th>来源/用途</th>
							<th>积分变化</th>
							<th>余额</th>
							<th width="80">状态</th>
							<th>日期</th>
							<th width="300">备注</th>
						</tr>
						<?php $_from = $this->_var['integral_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'integral');$this->_foreach['log'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['log']['total'] > 0):
    foreach ($_from AS $this->_var['integral']):
        $this->_foreach['log']['iteration']++;
?>
						<tr>
							<td><?php echo $this->_var['integral']['name']; ?></td>
							<td class="change"><?php if ($this->_var['integral']['changes'] > 0): ?> 
								<span class="plus">+<?php echo $this->_var['integral']['changes']; ?></span> 
								<?php else: ?> 
								<span class="minus"><?php echo $this->_var['integral']['changes']; ?></span> 
								<?php endif; ?></td>
							<td class="balance"><span><?php echo $this->_var['integral']['balance']; ?></span></td>
							<td><?php echo $this->_var['integral']['state']; ?></td>
							<td><?php echo local_date("Y年m月d日 H:i:s",$this->_var['integral']['add_time']); ?></td>
							<td width="300">
                            	<div style="padding-left:5px; text-align:left">
								<?php echo $this->_var['integral']['flag']; ?>
								</div>
                             </td>
						</tr>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</table>
				</div>
				<?php echo $this->fetch('member.page.bottom.html'); ?> 
				<?php else: ?>
				<div class="notice-word">
					<p>没有符合条件的记录</p>
				</div>
				<?php endif; ?> 
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<iframe id='iframe_post' name="iframe_post" src="about:blank" frameborder="0" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 