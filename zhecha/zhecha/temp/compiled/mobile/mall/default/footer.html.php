<div id="footer"> 
	<?php if ($this->_var['index']): ?>
	<div class="auth">
		<p class="clearfix web-info mt10"> <a href="javascript:;"><span>电脑版</span></a> <a href="javascript:;"><span><font color="#f23015">触屏版</font></span></a> <a href="javascript:;"><span>关于我们</span></a> <a href="javascript:;"><span style="border:0px;">联系我们</span></a> </p>
		<p class="mt10 copyright pb10">&copy; Copyright <a href="http://tea.7starsoft.com/">浙江省茶叶集团兴润合茶业科技有限公司</a> <?php echo $this->_var['statistics_code']; ?></p>
	</div>
	<?php endif; ?> 
	
	<?php if (! $this->_var['index']): ?>
	<div class="float-back-top" onclick="window.scroll(0,0);"></div>
	<?php endif; ?>
	<?php if (! in_array ( $_GET['app'] , array ( 'cart' , 'order' , 'cashier' ) )): ?>
    <div class="gap"></div>
	<div class="float-layer">
		<div class="global-nav global-nav-current">
			<div class="global-nav__nav-wrap">
				<div class="global-nav__nav-item"> <a href="<?php echo $this->_var['real_site_url']; ?>" class="global-nav__nav-link <?php if ($_GET['app'] == '' || $_GET['app'] == 'default'): ?>current<?php endif; ?>"><i class="psmb-icon-font global-nav__icon-index">&#xe6b8;</i> <span class="global-nav__nav-tit">首页</span></a> </div>
            	<?php if ($this->_var['malljoin'] == - 1): ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=dcenter'); ?>" class="global-nav__nav-link <?php if ($_GET['app'] == 'dcenter'): ?>current<?php endif; ?>"><i class="psmb-icon-font global-nav__icon-category">&#xe6cc;</i> <span class="global-nav__nav-tit">分销中心</span></a> </div>
                <?php else: ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=category'); ?>" class="global-nav__nav-link <?php if ($_GET['app'] == 'category'): ?>current<?php endif; ?>"><i class="psmb-icon-font global-nav__icon-category">&#xe67c;</i> <span class="global-nav__nav-tit">分类</span></a> </div>
                <?php endif; ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=search&act=form'); ?>" class="global-nav__nav-link <?php if ($_GET['act'] == 'form'): ?>current<?php endif; ?>"><i class="psmb-icon-font global-nav__icon-search">&#xe65c;</i> <span class="global-nav__nav-tit">搜索</span></a> </div>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=cart'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-shop-cart">&#xe6af;</i> <span class="global-nav__nav-tit">购物车</span> <span class="global-nav__nav-shop-cart-num" id="carId"><?php echo $this->_var['cart_goods_kinds']; ?></span></a> </div>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=member'); ?>" class="global-nav__nav-link <?php if ($_GET['app'] == 'member'): ?>current<?php endif; ?>"><i class="psmb-icon-font global-nav__icon-my-yhd">&#xe686;</i> <span class="global-nav__nav-tit">用户中心</span></a> </div>
			</div>
			<div class="global-nav__operate-wrap"> <span class="global-nav__operate-cart-num" id="globalId"><?php echo $this->_var['cart_goods_kinds']; ?></span> </div>
		</div>
	</div>
	<?php endif; ?> 
</div>
</body>
</html>