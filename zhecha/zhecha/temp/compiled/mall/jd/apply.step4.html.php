<?php echo $this->fetch('top.html'); ?>
<style type="text/css">
.w{width:990px;}
</style>
<div id="main" class="w-full">
	<div class="page-apply">
		<div class="w logo mt10">
			<p><a href="<?php echo $this->_var['site_url']; ?>" title="<?php echo $this->_var['site_title']; ?>"><img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>" /></a></p>
		</div>
		<div class="w content clearfix">
			<div class="left">
				<div class="title">
					<h3>商家入驻申请</h3>
				</div>
				<ul class="list">
					<li><i></i>入驻流程</li>
					<li><i></i>签订入驻协议</li>
					<li><i></i>提交申请</li>
					<li><i></i>平台审核</li>
					<li class="current"><i></i>店铺开通</li>
				</ul>
				<div class="title">
					<h3>平台联系方式</h3>
				</div>
				<ul class="call">
					<li><span>电话：</span>400-8888888</li>
					<li><span>手机：</span>15888888888</li>
					<li><span>邮箱：</span>psmoban@psmb.com</li>
				</ul>
			</div>
			<div class="right">
				<div class="joinin-step">
				  <ul>
					<li class="first current"><span>签订入驻协议</span></li>
					<li class="current"><span>填写商家信息</span></li>
					<li class="current"><span>平台审核</span></li>
					<li class="last current"><span>店铺开通</span></li>
				  </ul>
				</div>
				<div class="apply-status apply-end">
				    <p><i></i>您的店铺已经开通了。 <a href="<?php echo url('app=seller_admin'); ?>">管理我的店铺</a> <a href="<?php echo url('app=store&id=' . $this->_var['visitor']['user_id']. ''); ?>" target="_blank">查看我的店铺</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>