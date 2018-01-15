<?php echo $this->fetch('header.html'); ?>
<div id="page-gift"> <?php echo $this->fetch('curlocal.html'); ?>
	<div class="w-shop mt10 clearfix">
		<div class="col-sub w210"> <?php echo $this->fetch('left.html'); ?> </div>
		<div class="col-main ml10 w980">
			<div class="gift-detail mb20 clearfix">
				<div class="default-image float-left"><img width="300" src="<?php echo $this->_var['goods']['default_image']; ?>" /></div>
				<ul class="gift-info float-left">
					<li>
						<h3><?php echo $this->_var['goods']['goods_name']; ?></h3>
					</li>
					<li><span class="first">市面价格：</span><span class="price"><?php echo price_format($this->_var['goods']['price']); ?></span></li>
				</ul>
			</div>
			<div class="attr-tabs">
				<ul class="user-menu">
					<li class="active"> <a style="border-left:1px solid #ddd;" href="javascript:;"> <span> 赠品详情 </span> </a> </li>
				</ul>
			</div>
			<div class="option_box">
				<div class="default fs14"> <?php echo html_filter($this->_var['goods']['description']); ?> </div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>