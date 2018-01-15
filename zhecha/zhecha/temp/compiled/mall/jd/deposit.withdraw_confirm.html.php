<?php echo $this->fetch('member.header.html'); ?>
<script>
$(function(){
	$('#deposit-withdraw-confirm').submit(function(){
		if($(this).find('input[name="password"]').val()=='') {
			alert('账户支付密码不能为空');
			return false;
		}
		if($(this).find('input[name="captcha"]').val()=='') {
			alert('请输入验证码');
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
            	<div class="deposit-withdraw-confirm">
                	<div class="notice-word"><p>向平台商提交提现申请，审核通过之后，平台将向您提交的银行卡汇款，请确保您的银行卡信息正确</p></div>
                	<form method="post" id="deposit-withdraw-confirm">
                    	<div class="title">
                        	确认提现信息
                        </div>
                		<div class="form">
                        	<div class="confirm-info">
                            	<dl class="clearfix">
                                	<dt>银行卡信息：</dt>
                                    <dd>
                                    	<strong><?php echo $this->_var['bank']['account_name']; ?></strong> <?php echo $this->_var['bank']['bank_name']; ?>(<?php echo $this->_var['bank']['num']; ?>)
                                    </dd>
                                </dl>
                                <dl class="clearfix">
                                	<dt>提现金额：</dt>
                                    <dd>
                                    	<span class="money"><?php echo $this->_var['widthdraw']['money']; ?></span> 元
                                    </dd>
                                </dl>
                            </div>
                            <?php if ($this->_var['widthdraw']['total'] > $this->_var['deposit_account']['money']): ?>
                            <div class="notice-word draw-notice-word">
                            	<p class="yellow">对不起！您的提现金额 <em class="price"><?php echo $this->_var['widthdraw']['total']; ?></em> 大于您目前的账户余额<em class="price"><?php echo $this->_var['deposit_account']['money']; ?></em>，请您减少提现金额。</p>
                            </div>
                            <?php endif; ?>
                            <div class="confirm-form">
                            	<dl class="clearfix">
                        			<dt>支付密码：</dt>
                            		<dd><input type="password" name="password" class="text" value="" /></dd>
                        		</dl>
                                <dl class="clearfix">
                        			<dt>验证码：</dt>
                            		<dd class="captcha">
                                		<input type="text" name="captcha" class="text" id="captcha1" />
                                    	<a href="javascript:change_captcha($('#captcha'));" class="renewedly"><img id="captcha" src="index.php?app=captcha&amp;<?php echo $this->_var['random_number']; ?>" /></a>
                                	</dd>
                        		</dl>
                            	<dl class="clearfix">
                        			<dt>&nbsp;</dt>
                            		<dd class="submit">
                                		<span class="btn-alipay">
                                        	<input type="hidden" name="bid" value="<?php echo $this->_var['bank']['bid']; ?>" />
                                			<input type="submit" value="确认提现" <?php if ($this->_var['widthdraw']['total'] > $this->_var['deposit_account']['money']): ?> disabled="disabled" <?php endif; ?>/>
                                    	</span>
                                        <a class="goback" href="<?php echo url('app=deposit&act=withdraw'); ?>">返回修改</a>
                                	</dd>
                        		</dl>
                            </div>
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
