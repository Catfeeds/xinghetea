<?php echo $this->fetch('member.header.html'); ?>
<style>
.particular_wrap b{font-weight:normal}
.particular_wrap{border:1px #D2E6FD solid;padding:10px; background:#EBF4FE; line-height:25px;}
</style>
<div class="content margin10">
	<div class="particular">
		<div class="particular_wrap clearfix">
			<p class="<?php if ($this->_var['icon'] == "notice"): ?>success<?php else: ?>defeated<?php endif; ?>"> <span></span> <b style="float: left; margin-right:20px;"><?php echo $this->_var['message']; ?></b> 
				<?php if ($this->_var['err_file']): ?> 
				<b style="clear: both; float: left; font-size: 15px; margin-right:20px;">Error File: <strong><?php echo $this->_var['err_file']; ?></strong> at <strong><?php echo $this->_var['err_line']; ?></strong> line.</b> 
				<?php endif; ?> 
				<?php if ($this->_var['icon'] != "notice"): ?> 
				<font style="clear: both;"> 
				<?php $_from = $this->_var['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?> 
				<a style="color:#aaa;" href="<?php echo $this->_var['item']['href']; ?>">>> <?php echo $this->_var['item']['text']; ?></a><br />
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
				</font> 
				<?php endif; ?> 
			</p>
		</div>
	</div>
</div>
<script type="text/javascript">
//<!CDATA[
<?php if ($this->_var['redirect']): ?>
window.setTimeout("<?php echo $this->_var['redirect']; ?>", 1000);
<?php endif; ?>
//]]>
</script> 
<?php echo $this->fetch('footer.html'); ?>