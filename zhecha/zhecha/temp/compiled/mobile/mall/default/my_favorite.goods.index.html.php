<?php echo $this->fetch('member.header.html'); ?>
<div id="page-my-favorite" class="mb10">
	<div class="page-body my-favorite mb10">
		<?php $_from = $this->_var['collect_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['name']['iteration']++;
?>
		<dl>
			<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"  class="attr clearfix">
				<dt><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="90" height="90"  /></dt>
				<dd>
					<h3><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h3>
					<p><?php echo price_format($this->_var['goods']['price']); ?></p>
				</dd>
			</a>
			<a href="javascript:;" class="J_AjaxRequest delete" confirm="您确定要删除它吗？" action="<?php echo url('app=my_favorite&act=drop&item_id=' . $this->_var['goods']['goods_id']. '&type=goods'); ?>">删除</a>
		</dl>
		<?php endforeach; else: ?>
        <div class="no-record">没有符合条件的商品</div>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</div>
    <?php if ($this->_var['collect_goods']): ?>
    <?php echo $this->fetch('page.bottom.html'); ?>
    <?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>
