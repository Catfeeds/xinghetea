<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
	$('.J_Period a').click(function(){
		$(this).parent().find('a').removeClass('selected');
		$(this).addClass('selected');
		$(this).parent().find('input[name="period"]').val($(this).attr('value'));
	});
	
	$('.J_Buy').click(function(){
		//if(confirm('确定后将直接从账户余额扣除支付的金额，要确认吗？'))
		//{
			var aid = $.trim($('input[name="aid"]').val());
			var period = $.trim($('input[name="period"]').val());
			$.getJSON(SITE_URL + '/index.php?app=appmarket&act=buy', {'id':aid, 'period':period}, function(data){
				if (data.done)
				{
					//alert(data.msg);
					location.href= SITE_URL + '/index.php?app=appmarket&act=cashier&id=' + data.retval.bid;
					
				}
				else
				{
					alert(data.msg);
				}
			});
		//}
	});
});
</script>
<div id="page-promotool" class="page-promotool">
	<div class="content">
		<div class="totline"></div>
		<div class="botline"></div>
		<?php echo $this->fetch('member.menu.html'); ?>
		<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
			<?php echo $this->fetch('member.submenu.html'); ?>
			<div class="wrap">
				<div class="public_select">
					<div class="appmarket">
						<div class="appdetail mb20 clearfix">
							<form>
								<div class="default-image float-left"><img width="240" height="167" src="<?php echo $this->_var['appmarket']['logo']; ?>" /></div>
								<ul class="app-info float-left">
									<li>
										<h3><font class="f60">[<?php echo $this->_var['lang'][$this->_var['appmarket']['appid']]; ?>]</font> <?php echo $this->_var['appmarket']['title']; ?></h3>
										<p class="gray"><?php echo $this->_var['appmarket']['summary']; ?></p>
									</li>
									<li class="twocol"> <span class="first">价<em style="margin:0 8px;"></em>格：</span> <span class="price"><em><?php echo price_format($this->_var['appmarket']['config']['charge']); ?></em> 元/月</span> </li>
									<li class="twocol clearfix"> <span class="float-left first">周<em style="margin:0 8px;"></em>期：</span> 
										<span class="float-left period clearfix J_Period">
											<?php $_from = $this->_var['appmarket']['config']['period']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?> 
											<?php if (($this->_foreach['fe_item']['iteration'] <= 1)): ?>
											<input type="hidden" name="period" value="<?php echo $this->_var['item']['key']; ?>" class="J_Period" />
											<?php endif; ?>
											<a href="javascript:;" value="<?php echo $this->_var['item']['key']; ?>" <?php if (($this->_foreach['fe_item']['iteration'] <= 1)): ?> class="selected"<?php endif; ?>><b><?php echo $this->_var['item']['value']; ?></b></a> 
											<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
										</span> </li>
									<li class="twocol">
										
										<span>
											<input type="hidden" name="aid" value="<?php echo $this->_var['appmarket']['aid']; ?>" class="J_Aid" />
											<input type="button" class="btn-buy J_Buy" value="<?php if (! $this->_var['appmarket']['checkIsRenewal']): ?>购买<?php else: ?>续费<?php endif; ?>" />
										</span>
									</li>
								</ul>
							</form>
						</div>
						<div class="attr-tabs">
							<ul class="user-menu">
								<li class="active"> <a style="border-left:1px solid #ddd;" href="javascript:;"> <span> 应用详情 </span> </a> </li>
								<!--<li> <a style="border-left:1px solid #ddd;" href="javascript:;"> <span> 销售记录 </span> </a> </li>-->
							</ul>
							</ul>
						</div>
						<div class="option_box">
							<div class="default fs14"> <?php echo html_filter($this->_var['appmarket']['description']); ?> </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 