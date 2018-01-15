<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>

<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public table deposit">
            	<div class="drawlist">
                	<div class="tab-ttl">
                		<ul class="withdraw-tab clearfix">
                        	<li class="current"><a href="<?php echo url('app=deposit&act=rechargelist'); ?>"><span>充值记录</span></a></li>
                    	</ul>
                    </div>
                	<div class="title clearfix">
                    	<form method="get" class="float-left">
                        	<input type="hidden" name="app" value="deposit" />
                            <input type="hidden" name="act" value="rechargelist" />
                			<input type="text" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /> <span>-</span>
                            <input type="text" name="add_time_to" id="add_time_to"  value="<?php echo $_GET['add_time_to']; ?>" />
                            <select name="status">
                            	<option value="">请选择...</option>
                            	<option value="success" <?php if ($_GET['status'] == 'success'): ?> selected="selected"<?php endif; ?>>交易完成</option>
                                <option value="verifing" <?php if ($_GET['status'] == 'verifing'): ?> selected="selected"<?php endif; ?>>审核中</option>
                            </select>
                			<input type="submit" class="btn-withdraw" value="搜索" />
        				</form>
                        <div class="float-left">
                    		
                        </div>
                    </div>
                    <ul class="subtit">
                    	<li class="clearfix">
                        	<div class="time">创建时间</div>
                            <div class="info">名称 | 备注</div>
                        	<div class="tradeNo">商户订单号 | 交易号</div>
                            <div class="method">收/支</div>
                            <div class="money">金额(元)</div>
                            <div class="fundchannel">资金渠道</div>
                            <div class="status">状态</div>
                            <div class="handle">操作</div>
                        </li>
                    </ul>
                    <div class="content">
                    	<ul>
                    		<?php $_from = $this->_var['records']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'record');if (count($_from)):
    foreach ($_from AS $this->_var['record']):
?>
                        	<li class="clearfix">
                            	<div class="time"><?php echo local_date("Y.m.d H.i.s",$this->_var['record']['add_time']); ?></div>
                                <div class="info">充值</div>
                        		<div class="tradeNo"><?php echo $this->_var['record']['bizOrderId']; ?> | <?php echo $this->_var['record']['tradeNo']; ?></div>
                        		
                            	<div class="method"><span class="green">收入</span></div>
                            	<div class="money"><?php echo $this->_var['record']['amount']; ?></div>
                            	<div class="fundchannel"><?php echo $this->_var['record']['fundchannel']; ?></div>
                            	<div class="status">
                                    <span class="<?php if (in_array ( $this->_var['record']['status'] , array ( 'CLOSED' ) )): ?>gray<?php elseif (! in_array ( $this->_var['record']['status'] , array ( 'SUCCESS' ) )): ?>f60<?php endif; ?>"><?php echo $this->_var['record']['status_label']; ?></span>
                                </div>
								<div class="handle">
                                	<a href="<?php echo url('app=deposit&act=record&tradeNo=' . $this->_var['record']['tradeNo']. ''); ?>">查看</a>
                                </div>
                        	</li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    	</ul>
                        <?php if (! $this->_var['records']['list']): ?>
                        <div class="notice-word mt10"><p>没有符合条件的记录 <a href="<?php echo url('app=deposit&act=recharge'); ?>">马上充值</a></p></div>
                        <?php endif; ?>
                    </div>
                    <?php echo $this->fetch('member.page.bottom.html'); ?>
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
