<?php echo $this->fetch('header.html'); ?> 
<script type="text/javascript">
$(function(){
	$("#agreement_next").click(function(){
		var agreement = $("#input_apply_agreement").prop('checked');
		if(agreement){
			location.href = REAL_SITE_URL + '/index.php?app=apply&step=2';
			return;
		}else{
			layer.open({content: "请阅读并同意入驻协议", className:'layer-popup', time: 3});
			return false;
		}
	});
});
</script>
<div id="main" class="w-full">
	<div class="page-apply">
		<div class="content clearfix">
			<div class="apply-agreement padding10">
				<div class="agreement-content"><?php echo $this->_var['setup_store']['content']; ?></div>
				<div class="agreement-btn mt10 mb10">
					<input id="input_apply_agreement" name="agreement" type="checkbox" checked="checked">
					<label for="input_apply_agreement">我已阅读并同意以上协议</label>
				</div>
				<div><a id="agreement_next" href="javascript:;" class="btn-alipay">下一步，填写商家信息</a></div>
			</div>
		</div>
	</div>
</div>
</div>
<?php echo $this->fetch('footer.html'); ?>