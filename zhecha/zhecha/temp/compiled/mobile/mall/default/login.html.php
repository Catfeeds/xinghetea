<?php echo $this->fetch('header.html'); ?>
<div id="main" class="pb20">
	<div id="page-login" class="page-auth">
		<form class="J_AjaxForm" method="POST">
			<dl class="form pb20">
				<dd>
					<input type="text" id="user_name" name="user_name" class="J_AjaxFormFields input" placeholder=" 请输入用户名" />
				</dd>
				<dd>
					<input type="password" name="password" id="password" class="J_AjaxFormFields input" placeholder=" 请输入密码" />
				</dd>
				<?php if ($this->_var['captcha']): ?>
				<dd class="captcha clearfix">
					<input type="text" name="captcha" class="J_AjaxFormFields input float-left" id="captcha1" placeholder=" 请输入验证码" />
					<img id="captcha" class="float-left" src="<?php echo url('app=captcha&$random_number='); ?>" onclick="javascript:change_captcha($('#captcha'));"  /><br />
				</dd>
				<?php endif; ?>
				<dd>
					<label class="pt10 pb10"><input type="checkbox" name="AutoLogin" id="AutoLogin" class="J_AjaxFormFields" value="1" />七日内免登录</label>
				</dd>
				<dd>
					<input type="hidden" class="J_AjaxFormSuccessRet J_AjaxFormFields" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
					<input type="submit" class="J_AjaxFormSubmit btn-alipay" value="登录" />
				</dd>
			</dl>
		</form>
		
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>