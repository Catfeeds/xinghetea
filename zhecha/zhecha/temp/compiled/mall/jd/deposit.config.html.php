<?php echo $this->fetch('member.header.html'); ?>
<script>
function gs_callback(id, codeType, code)
{
	$('input[name="codeType"]').val(codeType);
	$('input[name="code"]').val(code);
	DialogManager.close(id);
	$('#deposit-config').submit();
}
</script>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public table deposit">
            	<div class="deposit-config">
                	<div class="notice-word"><p>资金账户名一旦设置后，将不允许修改，请填写正确的信息</p></div>
                	<form method="post" id="deposit-config">
                		<div class="form">
                			<dl class="clearfix">
                        		<dt>账 户 名</dt>
                            	<dd><input type="text" name="account" class="text width_normal" value="<?php echo $this->_var['deposit_account']['account']; ?>" <?php if ($this->_var['deposit_account']): ?> readonly="readonly"<?php endif; ?> /></dd>
                                <dd class="gray">您的资金账户名（手机号或邮箱）</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>真实姓名</dt>
                            	<dd><input type="text" name="real_name" class="text width_normal" value="<?php echo $this->_var['deposit_account']['real_name']; ?>" /></dd>
                                <dd class="gray"></dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>支付密码</dt>
                            	<dd><input type="password" name="password" class="text width_normal" /></dd>
                                <dd class="gray">付款的时候，需要输入支付密码才能付款成功</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>重复密码</dt>
                            	<dd><input type="password" name="password_confirm" class="text width_normal" /></dd>
                                <dd class="gray">确认您的密码</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>开启余额支付</dt>
                            	<dd>
                                	<label><input type="radio" name="pay_status" <?php if ($this->_var['deposit_account']['pay_status'] == 'ON' || ! $this->_var['deposit_account']): ?> checked="checked" <?php endif; ?>value="on" /> 是</label>
                                    <label><input type="radio" name="pay_status" <?php if ($this->_var['deposit_account']['pay_status'] == 'OFF'): ?> checked="checked"<?php endif; ?> value="off" /> 否</label>
                                </dd>
                                <dd class="gray">通过此开关，可以设置您的账户余额资金是否可以用于支付，以保护您的资金安全</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>&nbsp;</dt>
                            	<dd>
                                	 <span class="btn-alipay">
										<input type="hidden" name="codeType" value="" />
										<input type="hidden" name="code" value="" />
										<input type="button" value="提交"  gs_id="deposit_captcha" gs_name="deposit_captcha" gs_callback="gs_callback" gs_title="验证码" gs_width="400"  gs_type="captcha" ectype="gselector" gs_opacity="0.05" gs_class="simple-blue" id="deposit-captcha" />
									</span>
                                </dd>
                        	</dl>
                		</div>
					</form>
				</div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>

    <div class="clear"></div>
</div>
<iframe id='iframe_post' name="iframe_post" src="about:blank" frameborder="0" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>
