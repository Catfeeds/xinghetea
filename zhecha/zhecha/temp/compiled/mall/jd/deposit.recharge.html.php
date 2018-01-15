<?php echo $this->fetch('member.header.html'); ?>
<script>
$(function(){
	$('#deposit-recharge').submit(function(){
		if($(this).find('input[name="money"]').val()=='' || $(this).find('input[name="money"]').val() <= 0) {
			alert('充值金额不能为空且必须大于0');
			return false;
		}
	});
})
</script>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public table deposit">
            	<div class="deposit-withdraw deposit-recharge">
                	<div class="notice-word"><p>可以通过线上为自己的账户充值，充值成功后即时到账</p></div>
                	<form method="post" id="deposit-recharge">
                    	<div class="title clearfix">
                        	<h2 class="float-left">充值到您的预存款账户</h2>
                        </div>
                		<div class="form">
                            <dl class="bank-list clearfix" ectype="online">
                        		<dt style="line-height:35px;">充值渠道：</dt>
                            	<dd>
                                	<ul class="ui-list-icons clearfix">
                                    	<?php $_from = $this->_var['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');$this->_foreach['fe_payment'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_payment']['total'] > 0):
    foreach ($_from AS $this->_var['payment']):
        $this->_foreach['fe_payment']['iteration']++;
?>
                                    	<li class="clearfix" >
											<input class="float-left" <?php if (($this->_foreach['fe_payment']['iteration'] <= 1)): ?> checked="checked"<?php endif; ?> type="radio" name="payment_code" id="payment_<?php echo $this->_var['payment']['payment_code']; ?>" value="<?php echo $this->_var['payment']['payment_code']; ?>" />
											<label class="float-left  icon-box" for="payment_<?php echo $this->_var['payment']['payment_code']; ?>" >
												<span class="icon-cashier icon-cashier-<?php echo $this->_var['payment']['payment_code']; ?>">&nbsp;</span>
											</label>
										</li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
          
                                </dd>  
                        	</dl>
                            
                            <dl class="clearfix">
                        		<dt>充值金额：</dt>
                            	<dd><input type="text" name="money" class="text" value="" /> 元</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>充值备注：</dt>
                            	<dd><textarea name="remark" class="text"></textarea></dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>&nbsp;</dt>
                            	<dd class="submit">
                                	<span class="btn-alipay">
                                		<input type="submit" value="下一步" />
                                    </span>
                                </dd>
                        	</dl>
                		</div>
					</form>
				</div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>

    <div class="clear"></div>
</div>
<iframe id='iframe_post' name="iframe_post" src="about:blank" frameborder="0" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>
