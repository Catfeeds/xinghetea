<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
	$('#fullfree_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('li');
            error_td.find('label').hide();
            error_td.append(error);
        },
        success       : function(label){
            label.remove();
        },
		submitHandler:function(form) {
			if($('input[name="fullfree[fullamount]"]').val()=='' && $('input[name="fullfree[fullquantity]"]').val()=='') {
				alert('满金额和满件必须设置一项');
				$(form).find('input[name="fullfree[fullamount]"]').focus();
				$(form).find('input[type="text"]').addClass('error');
				return;
			}
			else form.submit();
		},
        onkeyup: false,
        rules : {
            "fullfree[fullamount]" : {
                number     : true,
				min		   : 0.01
            },
            "fullfree[fullquantity]"  : {
                 digits    : true
            }
        },
        messages : {
            "fullfree[fullamount]"  : {
                number     : '此项仅能为数字',
                min : '金额必须大于0.01元'
            },
            "fullfree[fullquantity]" : {
                digits  : '此项仅能为整数'
            }
        }
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
					<div class="promotool">
						<div class="bundle bundle-list">
                        	<?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
                            <div class="notice-word"><p><?php echo $this->_var['appAvailable']['msg']; ?></p></div>
                        	<?php else: ?>
							<div class="notice-word">
								<p class="yellow-big">温馨提示: 可设置单笔订单满多少件包邮和商品总额满多少金额包邮，满足其中一项即执行该优惠。</p>
							</div>
							<div class="promotool-form fullfree">
								<form id="fullfree_form" method="post">
									<ul class="form">
										<li>
											<h3>满金额包邮</h3>
										</li>
										<li>
											<input type="text" name="fullfree[fullamount]" id="fullfree[fullamount]" class="input" value="<?php echo $this->_var['fullfree']['rules']['fullamount']; ?>" />
											<span>元</span></li>
										<li>
											<h3>满件包邮</h3>
										</li>
										<li>
											<input type="text" name="fullfree[fullquantity]" id="fullfree[fullquantity]" class="input" value="<?php echo $this->_var['fullfree']['rules']['fullquantity']; ?>" />
											<span>件</span></li>
										<li>
											<h3>启用</h3>
										</li>
										<li class="slide-checkbox-radio clearfix">
											<input type="checkbox" name="status" value="1" id="check_1" class="slide-box slide-checkbox" <?php if ($this->_var['fullfree']['status'] || ! $this->_var['fullfree']): ?> checked="checked" <?php endif; ?>/>
											<label for="check_1" class="slide-trigger"></label>
										</li>
										<li>
											<input type="submit" value="提交" class="submit" />
										</li>
									</ul>
								</form>
							</div>
                            <?php endif; ?>
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