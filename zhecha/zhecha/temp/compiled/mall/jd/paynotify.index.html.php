<?php echo $this->fetch('header.html'); ?>
<style type="text/css">
.mall-nav{display:none}
</style>
<div id="mycart" class="w auto">
   <div class="step step3 mt10 clearfix">
      <span class="fs14 f60">1.查看购物车</span>
      <span class="fs14 f60">2.确认订单信息</span>
      <span class="fs14 fff">3.付款</span>
      <span class="fs14">4.确认收货</span>
      <span class="fs14">5.评价</span>
   </div>
   <div class="order-form cashier succeed clearfix mt20 mb20">
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