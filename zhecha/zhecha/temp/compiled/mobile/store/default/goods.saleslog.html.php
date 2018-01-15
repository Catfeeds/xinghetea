<?php echo $this->fetch('header.html'); ?>
<div id="page-goods" class="page-goods" style="margin-bottom:100px;"> 
	<?php echo $this->fetch('goodsinfo.html'); ?>
    <ul class="menus-tab clearfix" >
    	<li><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">商品详情</a></li>
    	<li><a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">商品评价</a></li>
        <li class="active"><a href="<?php echo url('app=goods&act=saleslog&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">购买记录</a></li>
        <li><a href="<?php echo url('app=goods&act=qa&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">我要咨询</a></li>
    </ul>
    <?php echo $this->fetch('saleslog.html'); ?>
</div>
<?php echo $this->fetch('footer.html'); ?>