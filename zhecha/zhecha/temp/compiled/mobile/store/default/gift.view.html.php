<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
	$('.detail-info img').attr('style','width:100%; height:100%;');
})
</script>
<div id="page-gift" class="page-gift" style="margin-bottom:36px;">
  <div class="w-shop mt10 clearfix">
    <div class="gift-detail mb20 padding10 clearfix">
      <div class="default-image float-left"><img width="100" height="100" class="block" src="<?php echo $this->_var['goods']['default_image']; ?>" /></div>
      <ul class="gift-info float-left">
        <li>
          <h3><?php echo $this->_var['goods']['goods_name']; ?></h3>
        </li>
        <li><span class="first">市面价格：</span><span class="price"><?php echo price_format($this->_var['goods']['price']); ?></span></li>
      </ul>
    </div>
    <ul class="menus-tab clearfix" >
    	<li class="active"><a href="javascript:;">赠品详情</a></li>
    </ul>
    <div class="option_box">
      <div class="detail-info padding10 clearfix"> <?php echo html_filter($this->_var['goods']['description']); ?> </div>
    </div>
  </div>
</div>
<?php echo $this->fetch('footer.html'); ?>