<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
    regionInit("region");
});
</script>
<div id="page-my-address">
	<div class="page-body add-address">
		<form class="J_AjaxForm" method="post">
			<ul class="padding10">
				<li>
					<input type="text"  class="J_AjaxFormFields input" name="consignee" id="consignee" value="<?php echo htmlspecialchars($this->_var['address']['consignee']); ?>" placeholder="请填写您的真实姓名"/>
				</li>
				<li class="mb10 edit-region">
					<div id="region">
						<input type="hidden" name="region_id" value="<?php echo $this->_var['address']['region_id']; ?>" id="region_id" class="J_AjaxFormFields mls_id" />
						<input type="hidden" name="region_name" id="region_name" value="<?php echo htmlspecialchars($this->_var['address']['region_name']); ?>" class="J_AjaxFormFields mls_names" />
						<?php if ($this->_var['address']['region_id']): ?> 
						<span><?php echo htmlspecialchars($this->_var['address']['region_name']); ?></span>
						<input type="button" value="编辑" class="edit_region" />
						<select style="display:none; ">
							<option>请选择...</option>
							  <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
						</select>
						<?php else: ?>
						<select>
							<option>请选择...</option>
							  <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
						</select>
						<?php endif; ?> 
					</div>
				</li>
				<li>
					<input type="text"   class="J_AjaxFormFields input" id="address" name="address" value="<?php echo htmlspecialchars($this->_var['address']['address']); ?>" placeholder="不必重复填写地区"/>
				</li>
				<li>
					<input type="text"  class="J_AjaxFormFields input" placeholder="邮政编码" id="zipcode" name="zipcode" value="<?php echo htmlspecialchars($this->_var['address']['zipcode']); ?>" />
				</li>
				<li>
					<input type="text"   class="J_AjaxFormFields input" id="phone_tel" name="phone_tel" value="<?php echo $this->_var['address']['phone_tel']; ?>" placeholder="区号 - 电话号码 - 分机"/>
				</li>
				<li>
					<input type="text"   class="J_AjaxFormFields input" placeholder="手&nbsp;&nbsp;&nbsp;机" id="phone_mob" name="phone_mob" value="<?php echo $this->_var['address']['phone_mob']; ?>"/>
				</li>
				<li>
					<input type="hidden" class="J_AjaxFormFields J_AjaxFormSuccessRet" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
					<input type="submit" class="J_AjaxFormSubmit btn-alipay" value="<?php if ($this->_var['address']['addr_id']): ?>编辑地址<?php else: ?>新增地址<?php endif; ?>" />
				</li>
			</ul>
		</form>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>