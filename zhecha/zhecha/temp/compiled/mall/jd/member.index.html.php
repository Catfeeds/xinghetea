<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
	function signInForIntergral()
	{
		var url = SITE_URL + '/index.php?app=member&act=sign_in_integral';
		$.getJSON(url,{},function(data){
			if(data.done)
			{
				$('#sign_in_integral').html(data.retval.amount);
			}
			alert(data.msg);
		});	
	}
</script>
<div class="content"> <?php echo $this->fetch('member.menu.html'); ?>
	<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
		<div class="profile clearfix">
			<div class="photo">
				<p><img src="<?php echo $this->_var['user']['portrait']; ?>" width="80" height="80" alt="" /></p>
			</div>
			<div class="info clearfix">
				<dl class="col-1 float-left">
					<dt> <span>欢迎您，</span><strong><?php echo htmlspecialchars($this->_var['user']['user_name']); ?></strong> <a href="<?php echo url('app=member&act=profile'); ?>">编辑个人资料>></a> </dt>
					<dd> <span>上次登录时间：<?php echo local_date("Y-m-d H:i:s",$this->_var['user']['last_login']); ?></span> <span>上次登录 IP：<?php echo $this->_var['user']['last_ip']; ?></span> </dd>
					<dd><?php echo sprintf('您有 <em class="red">%s</em> 条短消息，<a href="index.php?app=message&act=newpm">点击查看</a>', $this->_var['new_message']); ?><?php if ($this->_var['integral_enabled']): ?><span style="margin:0px 10px 0px 20px;">商城积分：<em class="red" id="sign_in_integral"><?php echo ($this->_var['user']['integral'] == '') ? '0' : $this->_var['user']['integral']; ?></em></span>
						
						<?php endif; ?></dd>
				</dl>
				<?php if ($this->_var['store'] && $this->_var['member_role'] == 'seller_admin'): ?>
				<dl class="col-2 float-left">
					<dt><strong>店铺评分</strong></dt>
					<dd>卖家信用：<a href="<?php echo url('app=store&act=credit&id=' . $this->_var['store']['store_id']. ''); ?>" target="_blank"><?php echo $this->_var['store']['credit_value']; ?></a><?php if ($this->_var['store']['credit_value'] >= 0): ?> <img src="<?php echo $this->_var['store']['credit_image']; ?>" align="absmiddle" /> <?php endif; ?> </dd>
					<dd>卖家好评率：<?php echo $this->_var['store']['praise_rate']; ?>%</dd>
				</dl>
				<?php endif; ?> 
			</div>
		</div>
		<div class="platform clearfix">
			<div class="col-1">
				<div class="buyer-notice box-notice box">
					<div class="hd clearfix">
						<h2>买家提醒</h2>
					</div>
					<div class="bd dealt">
						<div class="list">
							<h4>您需要立即处理：</h4>
							<dl class="clearfix">
								<dt>订单提醒：</dt>
								<dd> <span><?php echo sprintf('<a href="index.php?app=buyer_order&type=pending">待付款订单(<em>%s</em>)</a>', $this->_var['buyer_stat']['pending']); ?></span> <span><?php echo sprintf('<a href="index.php?app=buyer_order&type=shipped">待确认的订单(<em>%s</em>)</a>', $this->_var['buyer_stat']['shipped']); ?></span> <span><?php echo sprintf('<a href="index.php?app=buyer_order&type=finished">待评价的订单(<em>%s</em>)</a>', $this->_var['buyer_stat']['finished']); ?></span> </dd>
							</dl>
							
						</div>
						<div class="extra"></div>
					</div>
				</div>
				<?php if ($this->_var['store'] && $this->_var['member_role'] == 'seller_admin'): ?>
				<div class="seller-notice box-notice box">
					<div class="hd clearfix">
						<h2>卖家提醒</h2>
						<p></p>
					</div>
					<div class="bd">
						<div class="list">
							<dl class="clearfix">
								<dt>订单提醒：</dt>
								<dd> <span><?php echo sprintf('<a href="index.php?app=seller_order&type=submitted">待处理的订单(<em>%s</em>)</a>', $this->_var['seller_stat']['submitted']); ?></span> <span><?php echo sprintf('<a href="index.php?app=seller_order&type=accepted">待发货的订单(<em>%s</em>)</a>', $this->_var['seller_stat']['accepted']); ?></span> </dd>
							</dl>
							
						</div>
						<div class="extra"> <span>店铺等级：<?php echo $this->_var['sgrade']['grade_name']; ?></span> <span>有效期：<?php if ($this->_var['sgrade']['add_time']): ?><?php echo sprintf('剩余 %s 天', $this->_var['sgrade']['add_time']); ?><?php else: ?>不限制<?php endif; ?></span> <span>商品发布：<?php echo $this->_var['sgrade']['goods']['used']; ?>/<?php if ($this->_var['sgrade']['goods']['total']): ?><?php echo $this->_var['sgrade']['goods']['total']; ?><?php else: ?>不限制<?php endif; ?></span> <span>空间使用：<?php echo $this->_var['sgrade']['space']['used']; ?>M/<?php if ($this->_var['sgrade']['space']['total']): ?><?php echo $this->_var['sgrade']['space']['total']; ?>M<?php else: ?>不限制<?php endif; ?></span> </div>
					</div>
				</div>
				<?php endif; ?> 
				<?php if ($this->_var['_member_menu']['overview']): ?>
				<div class="apply-notice box-notice box">
					<div class="hd clearfix">
						<h2>开店提醒</h2>
					</div>
					<div class="bd">
						<div class="extra"> 
							<?php if ($this->_var['applying']): ?>
								<?php if ($this->_var['apply_remark']): ?>
								<?php echo sprintf('store_applying_reject', $this->_var['apply_remark'],$this->_var['user']['sgrade']); ?>
								<?php else: ?>
								<?php echo sprintf('您的店铺正在审核中。你可以：<a href="index.php?app=apply&step=2&id=%s">查看或修改店铺信息</a>', $this->_var['user']['sgrade']); ?>
								<?php endif; ?>
							<?php else: ?> 
							您目前不是卖家，您可以：<a href="<?php echo $this->_var['_member_menu']['overview']['url']; ?>" title="<?php echo $this->_var['_member_menu']['overview']['text']; ?>"><?php echo $this->_var['_member_menu']['overview']['text']; ?></a> 
							<?php endif; ?> 
						</div>
					</div>
				</div>
				<?php endif; ?> 
			</div>
			<div class="col-2"> 
				<?php if ($this->_var['store'] && $this->_var['member_role'] == 'seller_admin'): ?>
				<div class="rate-info box">
					<dl class="border-b total_evaluation w-full clearfix">
						<dt>综合评分：</dt>
						<dd>
							<div class="raty"> <span style="width:<?php echo ($this->_var['store']['evaluation_rate'] == '') ? '0' : $this->_var['store']['evaluation_rate']; ?>;"></span> </div>
							<b><?php echo ($this->_var['store']['avg_evaluation'] == '') ? '0' : $this->_var['store']['avg_evaluation']; ?></b> 分 </dd>
					</dl>
					<p> <strong>店铺动态评分</strong> 与行业相比 </p>
					<ul>
						<li> 商品评分 <span class="credit"><?php echo $this->_var['store']['avg_goods_evaluation']; ?></span> <span class="<?php echo $this->_var['store']['industy_compare']['goods_compare']['class']; ?>"> <i></i> <?php echo $this->_var['store']['industy_compare']['goods_compare']['name']; ?> <em><?php if ($this->_var['store']['industy_compare']['goods_compare']['value'] == 0): ?>----<?php else: ?><?php echo $this->_var['store']['industy_compare']['goods_compare']['value']; ?>%<?php endif; ?></em> </span> </li>
						<li> 服务评分 <span class="credit"><?php echo $this->_var['store']['avg_service_evaluation']; ?></span> <span class="<?php echo $this->_var['store']['industy_compare']['service_compare']['class']; ?>"> <i></i> <?php echo $this->_var['store']['industy_compare']['service_compare']['name']; ?> <em><?php if ($this->_var['store']['industy_compare']['service_compare']['value'] == 0): ?>----<?php else: ?><?php echo $this->_var['store']['industy_compare']['goods_compare']['value']; ?>%<?php endif; ?></em> </span> </li>
						<li> 发货评分 <span class="credit"><?php echo $this->_var['store']['avg_shipped_evaluation']; ?></span> <span class="<?php echo $this->_var['store']['industy_compare']['shipped_compare']['class']; ?>"> <i></i> <?php echo $this->_var['store']['industy_compare']['shipped_compare']['name']; ?> <em><?php if ($this->_var['store']['industy_compare']['shipped_compare']['value'] == 0): ?>----<?php else: ?><?php echo $this->_var['store']['industy_compare']['shipped_compare']['value']; ?>%<?php endif; ?></em> </span> </li>
					</ul>
				</div>
				<?php endif; ?>
				<div class="mall-notice box">
					<div class="hd clearfix">
						<h2>商城公告</h2>
					</div>
					<ul class="bd">
						<?php $_from = $this->_var['system_notice']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');if (count($_from)):
    foreach ($_from AS $this->_var['article']):
?>
						<li><a href="<?php echo url('app=article&act=view&article_id=' . $this->_var['article']['article_id']. ''); ?>" target="_blank"><?php echo sub_str(htmlspecialchars($this->_var['article']['title']),30); ?></a></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<div class="mall-customer box">
					<div class="hd">
						<h2>平台客服联系方式</h2>
					</div>
					<ul class="bd">
						<li>电话联系：23456789</li>
						<li>手机联系：88997788</li>
						<li>电子邮件：abc@psmoban.com</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?> 