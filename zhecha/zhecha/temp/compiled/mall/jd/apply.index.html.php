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
					<li class="current"><i></i>入驻流程</li>
					<li><i></i>签订入驻协议</li>
					<li><i></i>填写商家信息</li>
					<li><i></i>平台审核</li>
					<li><i></i>店铺开通</li>
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
				<div class="title"> 入驻流程 </div>
				<div class="joinin-i">
					<span><i class="a"></i>签订入驻协议</span>
					<i class="arrow"></i>
					<span><i class="b"></i>填写商家信息</span>
					<i class="arrow"></i>
					<span><i class="c"></i>平台审核</span>
					<i class="arrow"></i>
					<span><i class="d"></i>店铺开通</span>
				</div>
				<div class="title"> 入驻指南 </div>
				<div class="joinin-info">
					<ul class="nav J_tab">
						<?php $_from = $this->_var['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['fe_article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_article']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['fe_article']['iteration']++;
?>
						<li <?php if (($this->_foreach['fe_article']['iteration'] <= 1)): ?>class="on"<?php endif; ?> <?php if (($this->_foreach['fe_article']['iteration'] == $this->_foreach['fe_article']['total'])): ?> style="border:0;"<?php endif; ?>><?php echo htmlspecialchars($this->_var['article']['title']); ?></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
					<ul class="tab-content">
						<?php $_from = $this->_var['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['fe_article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_article']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['fe_article']['iteration']++;
?>
						<li <?php if (! ($this->_foreach['fe_article']['iteration'] <= 1)): ?> class="hidden"<?php endif; ?>><?php echo $this->_var['article']['content']; ?></li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
              </div>
			  <div class="joinin-btn">
			  		<a href="<?php echo url('app=apply&step=1'); ?>" target="_self">我要入驻</a>
			  </div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>