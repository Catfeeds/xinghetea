<?php echo $this->fetch('member.header.html'); ?>
<style type="text/css">
.tradelist li{text-align:center; width:130px;}
</style>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right">
  		<?php echo $this->fetch('member.curlocal.html'); ?>
    	<?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
        	<div class="search_order clearfix mb20">
                				<form method="get" class="clearfix">
               						<div class="float-left">
                						<span class="title">店铺名称:</span>
                						<input class="text_normal" type="text" name="real_name" value="<?php echo htmlspecialchars($_GET['real_name']); ?>" />
                						<input type="hidden" name="app" value="my_distribution" />
                						<input type="submit" class="btn" value="搜索" />
                					</div>
                					<?php if ($_GET['real_name']): ?>
                    				<a class="detlink" href="<?php echo url('app=my_distribution'); ?>">取消检索</a>
                					<?php endif; ?>
								</form>
        					</div>
            <div class="tradelist">
                    	<div class="subtit">
                        	<ul class="clearfix">
                            	<li>用户名</li>
                                <li>店铺名称</li>
                                <li>电话号码</li>
                                <li>加入时间</li>
                                <li>分销佣金</li>
                                <li>分销订单</li>
                            </ul>
                        </div>
                        <div class="list clearfix">
                        	<?php if ($this->_var['teams']): ?>
                        	<?php $_from = $this->_var['teams']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'team');if (count($_from)):
    foreach ($_from AS $this->_var['team']):
?>
                        	<ul class="clearfix" <?php if ($this->_var['team']['refund']): ?> style="border-bottom:1px #ddd dashed;color:gray"<?php endif; ?>>
                            	<li><?php echo htmlspecialchars($this->_var['team']['user_name']); ?></li>
                                <li><?php echo htmlspecialchars($this->_var['team']['real_name']); ?></li>
                                <li><?php echo $this->_var['team']['phone_mob']; ?></li>
                                <li><?php echo local_date("Y.m.d H:i:s",$this->_var['team']['add_time']); ?></li>
                                <li><?php echo price_format($this->_var['team']['amount']); ?></li>
                                <li><a href="<?php echo url('app=my_distribution&act=order&did=' . $this->_var['team']['did']. ''); ?>">查看</a></li>
                            </ul>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <?php else: ?>
                            <ul class="no-data"><li>没有符合条件的记录</li></ul>
                            <?php endif; ?>
                        </div>
                        <?php if ($this->_var['teams']): ?><div class="mt10 clearfix"><?php echo $this->fetch('member.page.bottom.html'); ?></div><?php endif; ?>
                    </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>