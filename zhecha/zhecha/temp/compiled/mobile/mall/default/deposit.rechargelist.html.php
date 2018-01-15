<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
	$('#add_time_from').date();
	$('#add_time_to').date();
});
</script>
<div class="deposit margin10">
	<div class="drawlist">
		<div class="tab-ttl">
			<ul class="withdraw-tab clearfix">
				<li class="current"><a href="<?php echo url('app=deposit&act=rechargelist'); ?>"><span>充值记录</span></a></li>
			</ul>
		</div>
		<div class="title clearfix">
			<form method="get">
				<input type="hidden" name="app" value="deposit" />
				<input type="hidden" name="act" value="rechargelist" />
				<input class="text mt10" type="text" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" />
				<span class="split pt10">-</span>
				<input class="text mt10" id="add_time_to" type="text" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" />
				<select name="status" class="mt10">
					<option value="">请选择...</option>
					<option value="success" <?php if ($_GET['status'] == 'success'): ?> selected="selected" <?php endif; ?>>交易完成</option>
					<option value="verifing" <?php if ($_GET['status'] == 'verifing'): ?> selected="selected" <?php endif; ?>>审核中</option>
				</select>
				<input type="submit" class="btn-alipay mt10" style="width:120px;" value="搜索" />
			</form>
		</div>
		<div class="content mt10"> 
			<?php $_from = $this->_var['records']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'record');if (count($_from)):
    foreach ($_from AS $this->_var['record']):
?>
			<div class="table-list mb10">
				<ul>
					<li class="first clearfix"> <span class="th float-left"><em>创建时间</em></span> <span class="td float-left"><em><?php echo local_date("Y.m.d H.i.s",$this->_var['record']['add_time']); ?></em></span> </li>
				<li class="clearfix"> <span class="th float-left"><em>名称|备注</em></span> <span class="td float-left"><em><?php echo sub_str($this->_var['record']['title'],30); ?></em></span> </li>
				<li class="clearfix"> <span class="th float-left"><em>商户订单号|交易号</em></span> <span class="td float-left"><em><?php echo $this->_var['record']['bizOrderId']; ?> | <?php echo $this->_var['record']['tradeNo']; ?></em></span> </li>
				
				<li class="clearfix"> <span class="th float-left"><em>收/支</em></span> <span class="td float-left"><em class="green">收入</em></span> </li>
				<li class="clearfix"> <span class="th float-left"><em>金额(元)</em></span> <span class="td float-left"><em><?php echo $this->_var['record']['amount']; ?></em></span> </li>
				<li class="clearfix"> <span class="th float-left"><em>资金渠道</em></span> <span class="td float-left"><em><?php echo $this->_var['record']['fundchannel']; ?></em></span> </li>
				<li class="clearfix"> 
                	<span class="th float-left"><em>状态</em></span> 
                    <span class="td float-left"><em class="<?php if (in_array ( $this->_var['record']['status'] , array ( 'CLOSED' ) )): ?>gray<?php elseif (! in_array ( $this->_var['record']['status'] , array ( 'SUCCESS' ) )): ?>f60<?php endif; ?>"><?php echo $this->_var['record']['status_label']; ?></em></span> 
                </li>
				<li class="clearfix"> <span class="th float-left"><em>操作</em></span> <span class="td float-left"><em><a href="<?php echo url('app=deposit&act=record&tradeNo=' . $this->_var['record']['tradeNo']. ''); ?>">查看</a></em></span> </li>
				</ul>
			</div>
			<?php endforeach; else: ?> 
			<div class="notice-word mt10">
				<p>没有符合条件的记录 <a href="<?php echo url('app=deposit&act=recharge'); ?>">马上充值</a></p>
			</div>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		</div>
	</div>
	<div id="datePlugin"></div>
</div>
<?php echo $this->fetch('page.bottom.html'); ?>
<?php echo $this->fetch('footer.html'); ?> 