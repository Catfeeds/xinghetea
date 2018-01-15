<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
	$('#exclusive_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('li');
            error_td.find('label').hide();
            error_td.append(error);
        },
        success       : function(label){
            label.remove();
        },
		submitHandler:function(form) {
			if($('input[name="exclusive[discount]"]').val()=='' && $('input[name="exclusive[decrease]"]').val()=='') {
				alert('折扣和减价必须设置一项');
				$(form).find('input[name="exclusive[discount]"]').focus();
				$(form).find('input[type="text"]').addClass('error');
				return;
			}
			else form.submit();
		},
        onkeyup: false,
        rules : {
            "exclusive[discount]" : {
                number     : true,
				min		   : 0.01,
				max  	   : 9.99
            },
            "exclusive[decrease]" : {
                 number    : true,
				 min       : 0.01
            }
        },
        messages : {
            "exclusive[discount]"  : {
                number     : '折扣必须是大于0.01小于9.99的数值',
                min : '折扣必须是大于0.01小于9.99的数值',
				max : '折扣必须是大于0.01小于9.99的数值'
            },
            "exclusive[decrease]" : {
                number  : '减价金额必须是数值',
				min	     : '减价金额必须大于0.01元',
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
								<p class="yellow-big">温馨提示: 用户通过手机等移动设备下单，可以享受一定的减价或折扣优惠，建议用折扣来设置。</p>
							</div>
							<div class="promotool-form exclusive">
								<form id="exclusive_form" method="post">
									<ul class="form">
										<li>
											<h3>折扣优惠<span class="gray">(默认)</span></h3>
										</li>
										<li>
											<input type="text" name="exclusive[discount]" id="exclusive[discount]" class="input" value="<?php echo $this->_var['exclusive']['rules']['discount']; ?>" />
											<span>折</span></li>
										<li>
											<h3>减价优惠</h3>
										</li>
										<li>
											<input type="text" name="exclusive[decrease]" id="exclusive[decrease]" class="input" value="<?php echo $this->_var['exclusive']['rules']['decrease']; ?>" />
											<span>元</span></li>
										<li>
											<h3>启用</h3>
										</li>
										<li class="slide-checkbox-radio clearfix">
											<input type="checkbox" name="status" value="1" id="check_1" class="slide-box slide-checkbox" <?php if ($this->_var['exclusive']['status'] || ! $this->_var['exclusive']): ?> checked="checked" <?php endif; ?>/>
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