<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
	$('.detail-info img').attr('style','width:100%; height:100%;');
})
</script>
<div id="page-goods" class="page-goods" style="margin-bottom:36px;"> 
	<?php echo $this->fetch('goodsinfo.html'); ?>
    <ul class="menus-tab clearfix" >
    	<li class="active"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">商品详情</a></li>
    	<li><a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">商品评价</a></li>
        <li><a href="<?php echo url('app=goods&act=saleslog&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">购买记录</a></li>
        <li><a href="<?php echo url('app=goods&act=qa&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>#module">我要咨询</a></li>
    </ul>
    <div class="detail-info pt10 pb10 clearfix"> <?php echo html_filter($this->_var['goods']['description']); ?> </div>
</div>
<?php echo $this->fetch('footer.html'); ?>
