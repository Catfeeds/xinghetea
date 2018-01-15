<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
	$('#fullprefer_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('li');
            error_td.find('label').hide();
            error_td.append(error);
        },
        success       : function(label){
            label.remove();
        },
		submitHandler:function(form) {
			if($('input[name="prefer[amount]"]').val()=='') {
				alert('订单满金额必须是数值且金额必须大于0.01');
				$(form).find('input[name="prefer[amount]"]').focus().addClass('error');
				return;
			}
			if($('input[name="prefer[type]"]:checked').length > 1) {
				alert('折扣和减价优惠只能选择其一');
				return;
			}
			if($('input[name="prefer[type]"]:checked').length < 1) {
				alert('折扣和减价优惠必须选择一项');
				return;
			}
			if($('input[name="prefer[type]"]:checked').val() == 'discount' && ($.trim($('input[name="prefer[discount]"]').val())=='')){
				$('input[name="prefer[discount]"]').focus();
				alert('折扣必须是大于0.01小于9.99的数值');
				return;
			}
			if($('input[name="prefer[type]"]:checked').val() == 'decrease' && ($.trim($('input[name="prefer[decrease]"]').val())=='')){
				$('input[name="prefer[decrease]"]').focus();
				alert('减价金额必须是数值');
				return;
			}
			else form.submit();
		},
        onkeyup: false,
        rules : {
			"prefer[amount]" : {
				number     : true,
				min		   : 0.01
			},
            "prefer[discount]" : {
                number     : true,
				min		   : 0.01,
				max  	   : 9.99
            },
			"prefer[decrease]" : {
				number     : true,
				min        : 0.01
			}
        },
        messages : {
			"prefer[amount]" : {
				number     : '订单满金额必须是数值',
				min        : '金额必须大于0.01元',
			},
            "prefer[discount]"  : {
                number     : '折扣必须是大于0.01小于9.99的数值',
                min : '折扣必须是大于0.01小于9.99的数值',
				max : '折扣必须是大于0.01小于9.99的数值'
            },
			"prefer[decrease]" : {
				number     : '减价金额必须是数值',
				min        : '减价金额必须大于0.01元',
			}
        }
    });
	$('input[name="prefer[type]"]').click(function(){
		if($(this).prop('checked') == true) {
			$(this).parent().parent().find('input[name="prefer[type]"]').prop('checked', false);
			$(this).prop('checked', true);
		} else {
			$(this).parent().parent().find('input[name="prefer[type]"]').prop('checked', false);
		}
		
		$('input[name="prefer[discount]"], input[name="prefer[decrease]"').attr('disabled', true);
		$(this).parent().find('input[type="text"]').attr('disabled', !$(this).prop('checked'));
		//$(this).parent().find('input[type="text"]').val('');
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
								<p class="yellow-big">温馨提示: 可设置单笔订单商品总额满多少后打多少折，或者减多少元，满足其中一项即执行该优惠，建议设置为打折优惠。</p>
							</div>
							<div class="promotool-form fullprefer">
								<form id="fullprefer_form" method="post">
									<ul class="form">
										<li>
											<h3>满折满减设置</h3>
										</li>
										<li class="mb10"> <span>订单满</span>
											<input type="text" name="prefer[amount]" id="prefer[amount]" class="input" value="<?php echo $this->_var['fullprefer']['rules']['amount']; ?>" />
											<span>元</span>
										</li>
										<li class="mb10">
											<input type="checkbox" style="margin-left:12px;" name="prefer[type]" value="discount" <?php if ($this->_var['fullprefer']['rules']['type'] == 'discount'): ?> checked="checked"<?php endif; ?> />
											<span>打</span>
											<input type="text" style="width:80px;" name="prefer[discount]" id="prefer[discount]" class="input" value="<?php echo $this->_var['fullprefer']['rules']['discount']; ?>" <?php if ($this->_var['fullprefer']['rules']['type'] != 'discount'): ?>disabled="disabled" <?php endif; ?> />
											<span>折</span>
										</li>
										
										<li class="mb10">
											<input type="checkbox" style="margin-left:12px;" name="prefer[type]" value="decrease"  <?php if ($this->_var['fullprefer']['rules']['type'] == 'decrease'): ?> checked="checked"<?php endif; ?>/> <span>减</span>
											<input type="text" style="width:80px;" name="prefer[decrease]" id="prefer[decrease]" class="input" value="<?php echo $this->_var['fullprefer']['rules']['decrease']; ?>" <?php if ($this->_var['fullprefer']['rules']['type'] != 'decrease'): ?>disabled="disabled" <?php endif; ?> />
											<span>元</span> </li>
										<li>
											<h3>启用</h3>
										</li>
										<li class="slide-checkbox-radio clearfix">
											<input type="checkbox" name="status" value="1" id="check_1" class="slide-box slide-checkbox" <?php if ($this->_var['fullprefer']['status'] || ! $this->_var['fullprefer']): ?> checked="checked" <?php endif; ?>/>
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