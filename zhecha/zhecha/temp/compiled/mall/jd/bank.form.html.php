<?php echo $this->fetch('member.header.html'); ?>

<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public table bank">
            	<div class="bank-add">
                	<div class="notice-word"><p>当您申请提现时平台会向您这里设置的银行卡汇款，请确保您的银行卡是正确的。</p></div>
                	<form method="post">
                		<div class="form">
                			<dl class="bank-list clearfix">
                        		<dt>开户银行</dt>
                            	<dd>
                                	<ul class="ui-list-icons clearfix">
                                    	<?php $_from = $this->_var['bank_inc']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'bank');$this->_foreach['fe_bank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_bank']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['bank']):
        $this->_foreach['fe_bank']['iteration']++;
?>
										<li>
											<input type="radio" name="short_name" id="<?php echo $this->_var['key']; ?>" value="<?php echo $this->_var['key']; ?>" <?php if (($this->_foreach['fe_bank']['iteration'] <= 1)): ?>checked="checked" <?php endif; ?> <?php if ($_GET['short_name'] == $this->_var['key']): ?> checked="checked" <?php endif; ?>  />
											<label class="  icon-box current " for="<?php echo $this->_var['key']; ?>" >
												<span class="icon-cashier icon-cashier-<?php echo $this->_var['key']; ?>" title="<?php echo $this->_var['bank']; ?>">&nbsp;</span>
											</label>
										</li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                          			</ul>
                                </dd>
                                
                        	</dl>
                            <dl class="clearfix">
                        		<dt>卡 号</dt>
                            	<dd><input type="text" name="num" class="text width_normal" value="<?php echo $this->_var['card']['num']; ?>" /></dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>户 名</dt>
                            	<dd><input type="text" name="account_name" class="text width_normal" value="<?php echo $this->_var['card']['account_name']; ?>" /></dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>开户支行</dt>
                            	<dd>
									<input type="text" name="open_bank" class="text width_normal" value="<?php echo $this->_var['card']['open_bank']; ?>" />
									<span class="gray">示例：XXX银行XXX支行</span>
								</dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>卡类型</dt>
                            	<dd>
                                	<label><input type="radio" name="type" value="debit" <?php if ($_GET['type'] == 'debit' || $this->_var['card']['type'] == 'debit'): ?> checked="checked" <?php endif; ?> />储蓄卡（借记卡）</label>
                                    <label><input type="radio" name="type" value="credit" <?php if ($_GET['type'] == 'credit' || $this->_var['card']['type'] == 'credit'): ?> checked="checked" <?php endif; ?> />信用卡（贷记卡）</label>
                                </dd>
                                
                        	</dl>
                            <dl class="clearfix">
                        		<dt>验证码</dt>
                            	<dd class="captcha clearfix">
                                	<input type="text" name="captcha" class="text" id="captcha1" />
                                    <a href="javascript:change_captcha($('#captcha'));" class="renewedly"><img id="captcha" src="index.php?app=captcha&amp;<?php echo $this->_var['random_number']; ?>" /></a>
                                </dd>
                        	</dl>
                            <dl class="clearfix">
                        		<dt>&nbsp;</dt>
                            	<dd>
                                	 <span class="btn-alipay"><input type="submit"  value="提交" /></span>
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
