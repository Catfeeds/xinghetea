<?php echo $this->fetch('member.header.html'); ?>
<div id="page-message">
	<div class="pm-list pm-view margin10">
        <ul>
    		<li>
            	<div class="send-user-logo">
                	<img width="50" height="50"  src="<?php echo $this->_var['message']['portrait']; ?>" />
                </div>
            	<div class="detail" <?php if (! $this->_var['replies']): ?>style="border:0px;"<?php endif; ?>>
                    <p class="t"><span><?php echo $this->_var['message']['user_name']; ?></span><em><?php echo local_date("Y-m-d H:i",$this->_var['message']['add_time']); ?></em></p>
                    <p class="d"><?php echo call_user_func("short_msg_filter",$this->_var['message']['content']); ?></p>
                </div>
            </li>
            <?php if ($this->_var['box'] == 'privatepm'): ?>
            <?php $_from = $this->_var['replies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'reply');$this->_foreach['fe_re'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_re']['total'] > 0):
    foreach ($_from AS $this->_var['reply']):
        $this->_foreach['fe_re']['iteration']++;
?>
            <li>
            	<div class="send-user-logo">
                	<img width="50" height="50"  src="<?php echo $this->_var['reply']['portrait']; ?>" />
                </div>
            	<div class="detail" <?php if (($this->_foreach['fe_re']['iteration'] == $this->_foreach['fe_re']['total'])): ?>style="border:0px;"<?php endif; ?>>
                    <p class="t"><span><?php echo $this->_var['reply']['user_name']; ?></span><em><?php echo local_date("Y-m-d H:i",$this->_var['reply']['add_time']); ?></em></p>
                    <p class="d"><span style="color:#15B7F2;">[回复]</span><?php echo call_user_func("short_msg_filter",$this->_var['reply']['content']); ?></p>
                </div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
    	</ul>
    </div>
    <?php if ($this->_var['box'] == 'privatepm'): ?>
    <div class="reply-form margin10">
        <form method="post" class="J_AjaxForm" enctype="multipart/form-date">
       	 	<div class="msg-content"><p><textarea class="J_AjaxFormFields" name="msg_content" placeholder="回复信息内容"></textarea></p></div>
            <input type="hidden" class="J_AjaxFormSuccessRet J_AjaxFormFields" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
            <div class="btn clearfix"><input class="float-right mr10 J_AjaxFormSubmit" type="submit"  value="提交" /></div>
        </form>
    </div>
    <?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>
