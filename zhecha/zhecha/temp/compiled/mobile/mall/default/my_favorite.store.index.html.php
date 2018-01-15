<?php echo $this->fetch('member.header.html'); ?> 
<div id="page-my-favorite" class="mb10">
	<div class="page-body my-favorite-store"> 
		<?php $_from = $this->_var['collect_store']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');$this->_foreach['fe_store'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_store']['total'] > 0):
    foreach ($_from AS $this->_var['store']):
        $this->_foreach['fe_store']['iteration']++;
?>
		<dl <?php if (($this->_foreach['fe_store']['iteration'] == $this->_foreach['fe_store']['total'])): ?> style="border-bottom:0"<?php endif; ?>>
			<dt> <a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['store']['store_logo']; ?>" width="45" height="45"  /></a> <a class="store-name" href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['store']['store_name']); ?></a> <a href="javascript:;" class="J_AjaxRequest delete" confirm="您确定要删除它吗？" action="<?php echo url('app=my_favorite&act=drop&item_id=' . $this->_var['store']['store_id']. '&type=store'); ?>">删除</a> </dt>
			<?php if ($this->_var['store']['goods_list']): ?>
			<dd style="margin-left:50px;">
				<p>最新宝贝</p>
				<div class="new-goods">
					<ul class="clearfix">
						<?php $_from = $this->_var['store']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
						<li> <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><img  width="75" height="75" src="<?php echo $this->_var['goods']['default_image']; ?>" /></a> </li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
					</ul>
				</div>
			</dd>
			<?php endif; ?>
		</dl>
		<?php endforeach; else: ?>
		<div class="no-record">没有符合条件的店铺</div>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	</div>
	<?php if ($this->_var['collect_store']): ?> 
	<?php echo $this->fetch('page.bottom.html'); ?> 
	<?php endif; ?> 
</div>
<?php echo $this->fetch('footer.html'); ?> 