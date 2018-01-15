<?php echo $this->fetch('member.header.html'); ?>
<div id="page-my-profile">
	<div class="page-body my-profile add-address mt10">
		<form class="J_AjaxForm" method="post">
			<ul class="padding10">
				<li>
					<label>性别: </label>
					<label>
						<input type="radio" class="J_AjaxFormFields" name="gender" value="0" <?php if ($this->_var['profile']['gender'] == 0): ?>checked="checked"<?php endif; ?> />
						保密 </label>
					<label>
						<input type="radio" class="J_AjaxFormFields" name="gender" value="1" <?php if ($this->_var['profile']['gender'] == 1): ?>checked="checked"<?php endif; ?> />
						男 </label>
					<label>
						<input type="radio" class="J_AjaxFormFields" name="gender" value="2" <?php if ($this->_var['profile']['gender'] == 2): ?>checked="checked"<?php endif; ?> />
						女 </label>
				</li>
				<li class="mt20">
					<input type="text" class="J_AjaxFormFields input" name="real_name" id="real_name" value="<?php echo htmlspecialchars($this->_var['profile']['real_name']); ?>" placeholder="真实姓名"/>
				</li>
				<li>
					<input type="text" class="J_AjaxFormFields input" placeholder="生日" id="birthday" name="birthday" value="<?php echo htmlspecialchars($this->_var['profile']['birthday']); ?>" />
				</li>
				<li>
					<input type="text" class="J_AjaxFormFields input" id="im_qq" name="im_qq" value="<?php echo htmlspecialchars($this->_var['profile']['im_qq']); ?>" placeholder="QQ"/>
				</li>
				<li>
					<input type="submit" class="J_AjaxFormSubmit btn-alipay" value="保存修改" />
				</li>
			</ul>
		</form>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?> 