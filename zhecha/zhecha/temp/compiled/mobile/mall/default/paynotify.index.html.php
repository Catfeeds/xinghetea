<?php echo $this->fetch('header.html'); ?>
<div id="mycart" class="auto">
   <div class="order-form cashier succeed clearfix mt10">
		<div class="order_info">
   			<div class="ico <?php if ($this->_var['payInfo']['payment_code'] != 'cod' && in_array ( $this->_var['payInfo']['status'] , array ( 'PENDING' , 'CLOSED' ) )): ?>defeated<?php endif; ?>"></div>
            <div class="text">
				<h4><?php echo $this->_var['payInfo']['status_label']; ?></h4>
				<div class="btn">
                	<?php $_from = $this->_var['payInfo']['Links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?>
                	<a href="<?php echo $this->_var['item']['link']; ?>"><?php echo $this->_var['item']['text']; ?></a> <?php if (! ($this->_foreach['fe_item']['iteration'] == $this->_foreach['fe_item']['total'])): ?>|<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            </div>
        </div>
   </div>
</div>
<?php echo $this->fetch('footer.html'); ?>