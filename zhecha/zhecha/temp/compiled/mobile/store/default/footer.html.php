<div id="footer">
	<?php if ($_GET['app'] != 'goods'): ?>
	<div class="float-back-top" onclick="window.scroll(0,0);"></div>
    <?php else: ?>
    <div class="float-cart"><a href="<?php echo url('app=cart'); ?>"></a></div>
    <?php endif; ?>
	<div class="gap"></div>
	<?php if ($_GET['app'] != 'goods'): ?>
	<div class="float-layer">
		<div class="global-nav global-nav-current">
			<div class="global-nav__nav-wrap"> 
                <div class="global-nav__nav-item"> <a href="<?php echo $this->_var['real_site_url']; ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-index">&#xe6b8;</i> <span class="global-nav__nav-tit">商城首页</span></a> </div>
                <?php if ($this->_var['store']['isMyDistributionStore']): ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=dcenter'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-category">&#xe6cc;</i> <span class="global-nav__nav-tit">分销中心</span></a> </div>
                <?php elseif ($this->_var['store']['canJoinInStore']): ?>
                <div class="global-nav__nav-item"> <a href="<?php echo url('app=apply&act=distribution'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-category">&#xe6ca;</i> <span class="global-nav__nav-tit">我要分销</span></a> </div>
                <?php else: ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=category'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-category">&#xe67c;</i> <span class="global-nav__nav-tit">分类</span></a> </div>
                <?php endif; ?>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=store&act=search&id=' . $this->_var['store']['store_id']. ''); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-search">&#xe65c;</i> <span class="global-nav__nav-tit">搜索</span></a> </div>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=cart'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-shop-cart">&#xe6af;</i> <span class="global-nav__nav-tit">购物车</span> <span class="global-nav__nav-shop-cart-num" id="carId"><?php echo $this->_var['cart_goods_kinds']; ?></span></a> </div>
				<div class="global-nav__nav-item"> <a href="<?php echo url('app=member'); ?>" class="global-nav__nav-link"><i class="psmb-icon-font global-nav__icon-my-yhd">&#xe686;</i> <span class="global-nav__nav-tit">用户中心</span></a> </div>
			</div>
			<div class="global-nav__operate-wrap"> <span class="global-nav__operate-cart-num" id="globalId"><?php echo $this->_var['cart_goods_kinds']; ?></span> </div>
		</div>
	</div>
	<?php endif; ?> 
    <?php echo $this->_var['statistics_code']; ?>
</div>
</body></html>