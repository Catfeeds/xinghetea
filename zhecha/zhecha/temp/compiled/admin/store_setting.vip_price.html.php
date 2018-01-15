<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>店铺功能设置</p>
    <ul class="subnav">
        <li><span>会员价设置</span></li>
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=pv_setting">pv设置</a></li>
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=add_goods">是否允许添加产品</a></li>
        
        </ul>
</div>

<div class="info">
	<form method="post" enctype="multipart/form-data">
		<table class="infoTable">
			<tr>
				<th class="paddingT15">是否启用:</th>
				<td class="paddingT15"><span class="onoff"> <label
						id="vip_price_allow"
						class="cb-enable <?php if ($this->_var['vip_price_setting']['status'] == 1): ?>selected<?php endif; ?>">启用</label>
						<label id="vip_price_unallow" class="cb-disable <?php if ($this->_var['vip_price_setting']['status'] == 0): ?>selected<?php endif; ?>">禁用</label>
						<input name="vip_price_allow" value="1"
						type="radio"<?php if ($this->_var['vip_price_setting']['status'] == 1): ?>checked<?php endif; ?>> <input
						name="vip_price_allow" value="0"
						type="radio"<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>checked<?php endif; ?>>
				</span> <span class="grey notice"></span></td>
			</tr>
			
			<tr id="vip_price_1" style="<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						会员价1:</label></th>
				<td class="paddingT15 wordSpacing5">
				<input  id="price_1" type="text"
					name="price_1" value="<?php echo $this->_var['vip_price_setting']['price']['price_1']; ?>" />
					<label class="field_notice">请输入会员价对应等级，如不输入为不启用该会员价。</label>
				</td>
			</tr>
			<tr id="vip_price_2" style="<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						会员价2:</label></th>
				<td class="paddingT15 wordSpacing5"><input
					 id="price_2" type="text"
					name="price_2" value="<?php echo $this->_var['vip_price_setting']['price']['price_2']; ?>" />
					<label class="field_notice">请输入会员价对应等级，如不输入为不启用该会员价。</label></td>
			</tr>
			<tr id="vip_price_3" style="<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						会员价3:</label></th>
				<td class="paddingT15 wordSpacing5"><input
					 id="price_3" type="text"
					name="price_3" value="<?php echo $this->_var['vip_price_setting']['price']['price_3']; ?>" />
					<label class="field_notice">请输入会员价对应等级，如不输入为不启用该会员价。</label></td>
			</tr>
			<tr id="vip_price_4" style="<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						会员价4:</label></th>
				<td class="paddingT15 wordSpacing5"><input
					 id="price_4" type="text"
					name="price_4" value="<?php echo $this->_var['vip_price_setting']['price']['price_4']; ?>" />
					<label class="field_notice">请输入会员价对应等级，如不输入为不启用该会员价。</label></td>
			</tr>
			<tr id="vip_price_5" style="<?php if ($this->_var['vip_price_setting']['status'] == 0): ?>display:none;<?php endif; ?>">
				<th class="paddingT15"><label for="price_format">
						会员价5:</label></th>
				<td class="paddingT15 wordSpacing5"><input
					 id="price_5" type="text"
					name="price_5" value="<?php echo $this->_var['vip_price_setting']['price']['price_5']; ?>" />
					<label class="field_notice">请输入会员价对应等级，如不输入为不启用该会员价。</label></td>
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
		$('#vip_price_allow').click(function() {
			$('#vip_price_1').show();
			$('#vip_price_2').show();
			$('#vip_price_3').show();
			$('#vip_price_4').show();
			$('#vip_price_5').show();
		});
		$('#vip_price_unallow').click(function() {
			$('#vip_price_1').hide();
			$('#vip_price_2').hide();
			$('#vip_price_3').hide();
			$('#vip_price_4').hide();
			$('#vip_price_5').hide();
		});

		

	});
</script>
<?php echo $this->fetch('footer.html'); ?>
