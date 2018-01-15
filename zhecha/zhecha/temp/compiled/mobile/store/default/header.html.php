<?php echo $this->fetch('top.html'); ?>
<div id="header" class="w-full"> 
	
	<div class="bar-wrap">
		<div class="top-bar"> <a href="javascript:pageBack();" class="pageback"><span></span></a>
			<h2 class="yahei"><?php echo $this->_var['curlocal_title']; ?></h2>
			<a href="javascript:;" class="pagemore J_pagemore"><span></span></a>
        </div>
		<div class="eject-tab J_eject_tab w-full clearfix hidden">
			<a href="<?php echo $this->_var['mobile_site_url']; ?>"> <span></span><p>首页</p></a>
			<a href="<?php echo url('app=search'); ?>"> <span class="icon2"></span><p>分类搜索</p></a>
			<a href="<?php echo url('app=cart'); ?>"> <span class="icon3"></span><p>购物车</p></a>
			<a href="<?php echo url('app=member'); ?>"> <span class="icon4"></span><p>用户中心</p></a>
		</div>
	</div>
	 
</div>
