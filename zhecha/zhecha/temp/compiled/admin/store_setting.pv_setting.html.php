<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>店铺功能设置</p>
    <ul class="subnav">
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=vip_price">会员价设置</a></li>
        <li><span>pv设置</span></li>
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=add_goods">是否允许添加产品</a></li>
        </ul>
</div>

<div class="info">
	<form method="post" enctype="multipart/form-data">
		<table class="infoTable">
			<tr>
				<th class="paddingT15">是否启用:</th>
				<td class="paddingT15"><span class="onoff"> <label
						id="pv_setting_allow"
						class="cb-enable <?php if ($this->_var['pv_setting']['status'] == 1): ?>selected<?php endif; ?>">启用</label>
						<label id="pv_setting_unallow" class="cb-disable <?php if ($this->_var['pv_setting']['status'] == 0): ?>selected<?php endif; ?>">禁用</label>
						<input name="pv_setting_allow" value="1"
						type="radio"<?php if ($this->_var['pv_setting']['status'] == 1): ?>checked<?php endif; ?>> <input
						 name="pv_setting_allow" value="0"
						type="radio"<?php if ($this->_var['pv_setting']['status'] == 0): ?>checked<?php endif; ?>>
				</span> <span class="grey notice"></span></td>
			</tr>
			
			<tr id="pv_view" style="<?php if ($this->_var['pv_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						显示名称:</label></th>
				<td class="paddingT15 wordSpacing5">
				<input  id="pv_view" type="text"
					name="pv_view" value="<?php echo $this->_var['pv_setting']['pv_view']; ?>" />
					<label class="field_notice">请输入pv显示名称，如不输入则显示名称为pv</label>
				</td>
			</tr>
			</div>

			<tr>
				<th></th>
				<td class="ptb20"><input class="formbtn J_FormSubmit"
					type="submit" name="Submit" value="提交" /> <input
					class="formbtn" type="reset" name="Submit2" value="重置" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	$(function() {
		$('#pv_setting_allow').click(function() {
			$('#pv_view').show();

		});
		$('#pv_setting_unallow').click(function() {
			$('#pv_view').hide();

		});

		

	});
</script>
<?php echo $this->fetch('footer.html'); ?>
