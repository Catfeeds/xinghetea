<?php echo $this->fetch('member.header.html'); ?>
<div class="deposit margin10"> 
	<?php if ($this->_var['deposit_account']): ?>
	<div class="deposit-account">
		<div class="amount-overview mb10 pt10">
			<div class="action">
				<h2>账户名：<?php echo $this->_var['deposit_account']['account']; ?></h2>
				<p class="mt10 clearfix"> <span><a  href="<?php echo url('app=deposit&act=config'); ?>">账号配置</a></span> <span style="padding:0% 5%;"><a  href="<?php echo url('app=deposit&act=recordlist'); ?>">收支明细</a></span> <span><a  href="<?php echo url('app=deposit&act=rechargelist'); ?>">充值记录</a></span> </p>
				<div class="clear"></div>
			</div>
			<div class="account-info">
				<h1>账户余额</h1>
				<div class="explain">余额支付[<em>?<span></span></em>]： 
					<?php if ($this->_var['deposit_account']['pay_status'] == 'ON'): ?> 
					<a href="javascript:;" class="J_AjaxRequest" action="<?php echo url('app=deposit&act=pay_status&status=off'); ?>" confirm="点击后关闭余额支付功能，要确定么？">已开启>></a> 
					<?php else: ?> 
					<a href="javascript:;" class="J_AjaxRequest" action="<?php echo url('app=deposit&act=pay_status&status=on'); ?>"  confirm="点击后开启余额支付功能，要确定么？">已关闭>></a> 
					<?php endif; ?> 
				</div>
				<div class="balanceNum"> <em><?php echo ($this->_var['deposit_account']['money'] == '') ? '0' : $this->_var['deposit_account']['money']; ?></em>元可用<?php if ($this->_var['deposit_account']['frozen'] > 0): ?>，<b><a href="<?php echo url('app=deposit&act=frozenlist'); ?>"><?php echo $this->_var['deposit_account']['frozen']; ?></a></b> 元不可用<?php endif; ?>
					<div class="balanceBtn"> <a href="<?php echo url('app=deposit&act=recharge'); ?>" class="btn-alipay-solid"><span>充值</span></a> </div>
				</div>
			</div>
		</div>
		<div class="tradelist mb5">
			<div class="title clearfix">
				<h1 class="float-left">最近交易记录</h1>
				<div class="float-right"><a href="<?php echo url('app=deposit&act=tradelist'); ?>">所有交易记录</a></div>
			</div>
		</div>
		<?php if ($this->_var['recordlist']): ?> 
		<?php $_from = $this->_var['recordlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'record');if (count($_from)):
    foreach ($_from AS $this->_var['record']):
?>
		<div class="table-list mb10">
			<ul>
				<li class="first clearfix">
                	<span class="th float-left"><em>创建日期</em></span> <span class="td float-left"><em><?php echo local_date("Y.m.d H:i:s",$this->_var['record']['add_time']); ?></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>名称|备注</em></span> <span class="td float-left"><em><?php echo sub_str($this->_var['record']['title'],30); ?></em></span> 
                </li>
                <li class="clearfix"> 
                	<span class="th float-left"><em>商户订单号|交易号</em></span> <span class="td float-left"><em><?php echo $this->_var['record']['bizOrderId']; ?> | <?php echo $this->_var['record']['tradeNo']; ?></em></span> 
                </li>
                
				<li class="clearfix"> 
                	<span class="th float-left"><em>对方</em></span> <span class="td float-left"><em><?php echo $this->_var['record']['partyInfo']['name']; ?></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>金额</em></span>
                    <span class="td float-left">
                    	<em>
                        	<?php if ($this->_var['record']['flow'] == 'income'): ?>
                            <strong class="price green">+<?php echo $this->_var['record']['amount']; ?></strong>
                            <?php else: ?>
                            <strong class="price f60">-<?php echo $this->_var['record']['amount']; ?></strong>
                            <?php endif; ?>
                        </em>
                    </span>
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>状态</em></span> 
                    <span class="td float-left"><em class="<?php if (in_array ( $this->_var['record']['status'] , array ( 'CLOSED' ) ) || in_array ( $this->_var['record']['refund']['status'] , array ( 'CLOSED' ) )): ?>gray <?php elseif (! in_array ( $this->_var['record']['status'] , array ( 'SUCCESS' ) )): ?>f60<?php endif; ?>"><?php echo $this->_var['record']['status_label']; ?></em></span> 
                </li>
				<li class="clearfix"> <span class="th float-left"><em>查看</em></span> <span class="td float-left"><em class="detail"><a href="<?php echo url('app=deposit&act=record&tradeNo=' . $this->_var['record']['tradeNo']. ''); ?>">详情</a></em></span> </li>
			</ul>
		</div>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		<?php endif; ?>
		
		<div class="bank-info">
			<div class="clearfix">
				<h1>银行卡</h1>
				<div class="explain">已绑定<span><?php echo ($this->_var['bank_list']['count'] == '') ? '0' : $this->_var['bank_list']['count']; ?></span>张</div>
				<div class="action"> <a href="<?php echo url('app=bank&act=add'); ?>">+添加银行卡</a> </div>
			</div>
			<ul class="cards">
				<div class="clearfix">
					<?php $_from = $this->_var['bank_list']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'bank');$this->_foreach['fe_bank'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_bank']['total'] > 0):
    foreach ($_from AS $this->_var['bank']):
        $this->_foreach['fe_bank']['iteration']++;
?>
					<li class="card">
						<div class="wrap cleaffix">
							<h2><?php echo $this->_var['bank']['bank_name']; ?></h2>
							<div class="hd">
								<div class="number"><?php echo $this->_var['bank']['num']; ?></div>
								<div class="handle clearfix">
									<div class="card-type <?php echo $this->_var['bank']['type']; ?>"></div>
									<div class="card-handle"> <a href="<?php echo url('app=bank&act=edit&short_name=' . $this->_var['bank']['short_name']. ''); ?>">编辑</a> <a href="javascript:;" class="J_AjaxRequest" confirm="您确定要删除它吗？" action="<?php echo url('app=bank&act=drop&bid=' . $this->_var['bank']['bid']. ''); ?>">删除</a> </div>
								</div>
							</div>
						</div>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
				</div>
			</ul>
		</div>
	</div>
	<?php else: ?>
	<div class="notice-word">
		<p>请先配置帐户，点击 <a href="<?php echo url('app=deposit&act=config'); ?>">立即配置</a></p>
	</div>
	<?php endif; ?> 
</div>
<iframe id='iframe_post' name="iframe_post" src="about:blank" frameborder="0" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 