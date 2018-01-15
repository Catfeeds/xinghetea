<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>店铺功能设置</p>
    <ul class="subnav">
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=vip_price">会员价设置</a></li>
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=pv_setting">pv设置</a></li>
        <li><span>是否允许添加产品</span></li>
        </ul>
</div>

<div class="info">
	<form method="post" enctype="multipart/form-data">
		<table class="infoTable">
			<tr>
				<th class="paddingT15">是否允许添加产品:</th>
				<td class="paddingT15"><span class="onoff"> <label
						id="add_goods_allow"
						class="cb-enable <?php if ($this->_var['add_goods']['status'] == 1): ?>selected<?php endif; ?>">允许</label>
						<label id="add_goods_unallow" class="cb-disable <?php if ($this->_var['add_goods']['status'] == 0): ?>selected<?php endif; ?>">禁止</label>
						<input name="add_goods_allow" value="1"
						type="radio"<?php if ($this->_var['add_goods']['status'] == 1): ?>checked<?php endif; ?>> <input
						 name="add_goods_allow" value="0"
						type="radio"<?php if ($this->_var['add_goods']['status'] == 0): ?>checked<?php endif; ?>>
				</span> <span class="grey notice"></span></td>
			</tr>

			</div>

			<tr>
				<th></th>
				<td class="ptb20"><input class="formbtn J_FormSubmit"
					type="submit" name="Submit" value="提交" /> 
				</td>
			</tr>
		</table>
	</form>
</div>

<?php echo $this->fetch('footer.html'); ?>
