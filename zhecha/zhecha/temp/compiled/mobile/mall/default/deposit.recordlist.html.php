<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
	$('#add_time_from').date();
	$('#add_time_to').date();
});
</script>
<div class="deposit margin10">
	<div class="recordlist"> 
		<?php if ($this->_var['records']['list']): ?>
		<div class="notice-word">
			<p>您资金账户余额变动的所有财务明细</p>
		</div>
		<?php endif; ?>
		<div class="title clearfix">
			<form method="get" class="mr10">
				<input type="hidden" name="app" value="deposit" />
				<input type="hidden" name="act" value="recordlist" />
				<input class="text mt10" type="text" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" />
				<span class="split pt10">-</span>
				<input class="text mt10" id="add_time_to" type="text" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" />
				<input type="submit" class="btn-alipay mt10" style="width:120px;" value="搜索" />
			</form>
			<div class=" mt10"> 
            	<span class="mr20">
                	总收入 <strong class="price"><?php echo $this->_var['records']['total_income']; ?></strong> 元，
               	 	总支出 <strong class="price"><?php echo $this->_var['records']['total_outlay']; ?></strong> 元 
                </span>
                <span>
                	<a href="<?php echo url('app=deposit&act=monthbill'); ?>" class="inline-block pl5 pr5" style="background:#39F;color:#fff; line-height:25px;">下载月账单</a>
                </span>
            </div>
		</div>
		<?php $_from = $this->_var['records']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'record');if (count($_from)):
    foreach ($_from AS $this->_var['record']):
?>
		<div class="table-list mb10">
			<ul>
				<li class="first clearfix"> 
                	<span class="th float-left"><em>付款日期</em></span> 
                    <span class="td float-left"><em><?php echo local_date("Y.m.d H.i.s",$this->_var['record']['add_time']); ?></em></span> 
                </li>
				<li class="clearfix">  
                	<span class="th float-left"><em>商户订单号|交易号</em></span>
                    <span class="td float-left"><em><?php echo $this->_var['record']['bizOrderId']; ?> | <?php echo $this->_var['record']['tradeNo']; ?></em></span>
                </li>
				<li class="clearfix"> <span class="th float-left"><em class="two-col">类型</em></span> <span class="td float-left"><em class="two-col"><?php echo $this->_var['record']['tradeTypeName']; ?></em></span> </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>收入(元)</em></span> 
                    <span class="td float-left"><em><strong class="price green"><?php if ($this->_var['record']['flow'] == 'income'): ?>+<?php echo $this->_var['record']['amount']; ?><?php else: ?>&nbsp;<?php endif; ?></strong></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>支出(元)</em></span> 
                    <span class="td float-left"><em> <strong class="price"><?php if ($this->_var['record']['flow'] == 'outlay'): ?>-<?php echo $this->_var['record']['amount']; ?><?php else: ?>&nbsp;<?php endif; ?></strong></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>余额(元)</em></span> 
                    <span class="td float-left"><em><strong class="price"><?php echo $this->_var['record']['balance']; ?></strong></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>支付方式</em></span> 
                    <span class="td float-left"><em> <?php echo $this->_var['record']['fundchannel']; ?></em></span> 
                </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>详情</em></span> 
                    <span class="td float-left"><em class="detail"><a href="<?php echo url('app=deposit&act=record&tradeNo=' . $this->_var['record']['tradeNo']. ''); ?>">查看</a></em></span> 
                </li>
			</ul>
		</div>
		<?php endforeach; else: ?>
		<div class="notice-word">
			<p class="yellow">没有交易记录</p>
		</div>
		<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
	</div>
	<div id="datePlugin"></div>
</div>
<?php echo $this->fetch('page.bottom.html'); ?>
<?php echo $this->fetch('footer.html'); ?> 