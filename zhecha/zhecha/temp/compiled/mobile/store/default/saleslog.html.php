<div class="saleslog-list w-full">
	<table>
		<?php if ($this->_var['sales_list']): ?>
		<tr class="tt">
			<th>买家</th>
			<th>购买价</th>
			<th>购买数量</th>
			<th>上架</th>
		</tr>
		<?php endif; ?> 
		<?php $_from = $this->_var['sales_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sales');if (count($_from)):
    foreach ($_from AS $this->_var['sales']):
?>
		<tr>
			<td><?php if ($this->_var['sales']['anonymous']): ?>***<?php else: ?><?php echo htmlspecialchars($this->_var['sales']['buyer_name']); ?><?php endif; ?></td>
			<td><?php echo price_format($this->_var['sales']['price']); ?></td>
			<td><?php echo $this->_var['sales']['quantity']; ?> </td>
			<td><?php echo local_date("Y-m-d",$this->_var['sales']['add_time']); ?></td>
		</tr>
		<?php endforeach; else: ?>
		<tr>
			<td colspan="6"><span class="light">没有符合条件的记录</span></td>
		</tr>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</table>
</div>
