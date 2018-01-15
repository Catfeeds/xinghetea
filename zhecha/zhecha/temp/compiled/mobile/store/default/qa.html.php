<div class="qas padding10"> 
	<?php $_from = $this->_var['qa_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'qainfo');$this->_foreach['fe_qa'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_qa']['total'] > 0):
    foreach ($_from AS $this->_var['qainfo']):
        $this->_foreach['fe_qa']['iteration']++;
?>
	<div  class="list">
		<div <?php if (($this->_foreach['fe_qa']['iteration'] == $this->_foreach['fe_qa']['total'])): ?>style="border:0px;"<?php endif; ?> class="it">
			<dl>
				<dt>咨询内容</dt>
				<dd class="qa-content mt5"><?php echo nl2br(htmlspecialchars($this->_var['qainfo']['question_content'])); ?></dd>
				<p class="mt5 w-full clearfix"> <span><?php if ($this->_var['qainfo']['user_name']): ?><?php echo $this->_var['qainfo']['user_name']; ?><?php else: ?>游客<?php endif; ?></span><em><?php echo local_date("Y-m-d H:i:s",$this->_var['qainfo']['time_post']); ?></em> </p>
			</dl>
			<?php if ($this->_var['qainfo']['reply_content']): ?>
			<dl class="mt10">
				<dt class="store-replay">店主回复: </dt>
				<dd class="mt5"><?php echo nl2br(htmlspecialchars($this->_var['qainfo']['reply_content'])); ?></dd>
				<p class="mt5"> <span><?php echo local_date("Y-m-d H:i:s",$this->_var['qainfo']['time_reply']); ?></span> </p>
			</dl>
			<?php endif; ?> 
		</div>
	</div>
	<?php endforeach; else: ?>
	<div class="list padding10">没有符合条件的记录</div>
	<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	<?php if ($this->_var['qa_info']): ?><?php echo $this->fetch('page.bottom.html'); ?><?php endif; ?> </div>
<?php if ($_GET['app'] == 'goods' || $_GET['app'] == 'groupbuy'): ?>
<div class="fill_in pl10 pr10 pb10">
	<form class="J_AjaxForm" method="post" action="index.php?app=<?php echo $_GET['app']; ?><?php if ($_GET['act']): ?>&amp;act=<?php echo $_GET['act']; ?><?php elseif ($_GET['app'] == 'goods'): ?>&amp;act=qa<?php endif; ?>&amp;id=<?php echo $_GET['id']; ?>">
		<p style="border:1px solid #ddd;background:#fff;height:100px;margin-bottom:10px;border-radius:5px;">
			<textarea name="content" id="content" class="J_AjaxFormFields textarea" style="border:none;width:100%;height:100%;background:none;"></textarea>
		</p>
		<p> 
			<?php if (! $this->_var['guest_comment_enable'] && ! $this->_var['visitor']['user_id']): ?> 
			您需要先&nbsp;[<a href="index.php?app=member&act=login">登录</a>]&nbsp;后才可以发布咨询 
			<?php else: ?> 
			<span>
			<input type="text" class="J_AjaxFormFields input" id="email" name="email" value="<?php echo $this->_var['email']; ?>"  placeholder="请输入电子邮箱"/>
			</span> 
			<?php if ($this->_var['captcha']): ?> 
			<span>
			<input type="text" class="J_AjaxFormFields input captcha" id="captcha2" name="captcha" placeholder="请输入验证码"/>
			</span> <span><a href="javascript:;"><img id="captcha" onclick="javascript:change_captcha($('#captcha'));"  src="<?php echo url('app=captcha&$random_number='); ?>" /></a></span> 
			<?php endif; ?> 
			<?php if ($_SESSION['user_info']): ?>
		<p>
			<label>
				<input type="checkbox" class="J_AjaxFormFields" name="hide_name" id="hide_name" value="hide" />
				匿名发表</label>
		</p>
		<?php endif; ?> 
		<?php endif; ?>
		</p>
		<p class="mt10">
			<input type="hidden" class="J_AjaxFormSuccessRet" name="ret_url" value="<?php if ($_GET['app'] == 'groupbuy'): ?><?php echo url('app=groupbuy&id=' . $this->_var['group']['group_id']. ''); ?><?php else: ?><?php echo url('app=goods&act=qa&id=' . $_GET['id']. '&store_id=' . $_GET['store_id']. ''); ?><?php endif; ?>" />
			<input type="submit" class="J_AjaxFormSubmit J_AjaxFormFields btn-alipay" value="发布咨询" name="qa" />
		</p>
	</form>
</div>
<?php endif; ?> 
