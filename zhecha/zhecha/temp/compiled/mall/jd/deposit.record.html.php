<?php echo $this->fetch('member.header.html'); ?>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
            <div class="public table deposit">
            	<div class="record">
                	<?php if (! $this->_var['tradeInfo']): ?>
                    <div class="notice-word"><p class="yellow">没有该条交易信息</p></div>
                    <?php else: ?>
                	<div class="title"><h2>交易详情</h2></div>
                    <div class="content">
                    	<div class="status-info clearfix">
                        	<h3 class="float-left"><?php echo $this->_var['tradeInfo']['status_label']; ?></h3>
                            <div class="extra float-left ml10" style="line-height:20px;">
                            	<?php if ($this->_var['tradeInfo']['refundInfo']): ?>
                                	<?php if (in_array ( $this->_var['tradeInfo']['refundInfo']['status'] , array ( 'SUCCESS' ) )): ?>
                                	<span class="ml10 mr10">退款成功，退款金额已返还至您的预存款余额中</span>
                                    <?php elseif (! in_array ( $this->_var['tradeInfo']['refundInfo']['status'] , array ( 'CLOSED' ) )): ?>
                                    <span class="ml10 mr10"><?php echo $this->_var['tradeInfo']['refundInfo']['status_label']; ?></span>
                                	<?php endif; ?>
                                <a class="bglink ml10 mr10" href="<?php echo url('app=refund&act=view&refund_id=' . $this->_var['tradeInfo']['refundInfo']['refund_id']. ''); ?>">查看退款详情</a>
                                <?php elseif ($this->_var['tradeInfo']['status'] == 'PENDING'): ?>
                                	<?php if (in_array ( $this->_var['tradeInfo']['tradeCat'] , array ( 'RECHARGE' , 'SHOPPING' ) ) && ( $this->_var['tradeInfo']['buyer_id'] == $this->_var['visitor']['user_id'] )): ?>
                                	<span class="ml10 mr10">提交订单后，48小时内未支付，交易自动关闭</span>
                                    
                                    <a class="bglink ml10 mr10" href="<?php echo url('app=depopay&act=pay&orderId=' . $this->_var['tradeInfo']['tradeNo']. ''); ?>">立即付款</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        
                        <div class="trade-list clearfix">
                        	<ul class="ul-head clearfix">
                            	<li class="paytype">支付类型</li>
                                <li class="name">消费名称</li>
								<li class="amount">金额</li>
                                <?php if ($this->_var['tradeInfo']['refundInfo']): ?>
								<li class="amount">退款</li>
                                <li class="amount">应付总额</li>
                                <?php endif; ?>
                            </ul>
                            <ul class="clearfix">
                            	<li class="paytype"><?php echo $this->_var['lang'][$this->_var['tradeInfo']['payType']]; ?></li>
                                <li class="name">
                                	<?php echo sub_str($this->_var['tradeInfo']['title'],60); ?>
                                    <?php if ($this->_var['tradeInfo']['remark']): ?>
                                    <p class="gray"><?php echo sub_str($this->_var['tradeInfo']['remark'],100); ?></p>
                                    <?php endif; ?>
                                </li>
								<li class="amount"><strong class="price f60"><?php echo $this->_var['tradeInfo']['amount']; ?></strong></li>
                                <?php if ($this->_var['tradeInfo']['refundInfo']): ?>
								<li class="amount">-<?php echo ($this->_var['tradeInfo']['refundInfo']['refund_total_fee'] == '') ? '0.00' : $this->_var['tradeInfo']['refundInfo']['refund_total_fee']; ?></li>
                                <li class="amount"><?php echo $this->_var['tradeInfo']['tradeAmount']; ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <?php if ($this->_var['tradeInfo']['orderInfo']): ?>
                        <div class="trade-goods mt20">
                        	<ul class="ul-head clearfix">
                            	<li class="info">宝贝</li>
                                <li class="props">宝贝属性</li>
                                <li class="price">单价(元)</li>
                                <li class="quantity">数量(件)</li>
                                <li class="status">运费(元)</li>
                                <li class="amount">商品总价(元)</li>
                            </ul>
                            <?php $_from = $this->_var['tradeInfo']['orderInfo']['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
                            <ul class="ul-list clearfix">
                            	<li class="info clearfix">
                                	<div class="pic float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['tradeInfo']['orderInfo']['seller_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="40" height="40" /></a></div>
                                    <div class="desc float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['tradeInfo']['orderInfo']['seller_id']. ''); ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo sub_str(htmlspecialchars($this->_var['goods']['goods_name']),50); ?></a></div>
                                </li>
                                <li class="props"><?php echo $this->_var['goods']['specification']; ?>&nbsp;</li>
                                <li class="price"><?php echo $this->_var['goods']['price']; ?></li>
                                <li class="quantity"><?php echo $this->_var['goods']['quantity']; ?></li>
                                <li class="status" <?php if (! ($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?> style="border-bottom:1px #fff solid;"<?php endif; ?>><?php if (($this->_foreach['fe_goods']['iteration'] <= 1)): ?><p><?php echo $this->_var['tradeInfo']['orderInfo']['shipping_name']; ?>：<?php echo $this->_var['tradeInfo']['orderInfo']['shipping_fee']; ?></p><?php endif; ?></li>
                                <li class="amount" <?php if (! ($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?> style="border-bottom:1px #fff solid;"<?php endif; ?>>
                                	<?php if (($this->_foreach['fe_goods']['iteration'] <= 1)): ?><p><?php echo $this->_var['tradeInfo']['orderInfo']['order_amount']; ?></p><?php endif; ?>
                                </li>
                            </ul>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>

                        <div class="trade-slips">
                        	<dl class="clearfix">
                            	<dt>订单编号：</dt><dd>
                                <?php echo $this->_var['tradeInfo']['bizOrderId']; ?>
                                </dd>
                            </dl>
                            <dl class="clearfix">
                            	<dt>订单详情：</dt><dd>
                                	<?php if ($this->_var['tradeInfo']['buyer_id'] == $this->_var['visitor']['user_id']): ?>
                                    <a class="view" href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['tradeInfo']['orderInfo']['order_id']. ''); ?>" target="_blank">查看订单</a>
                                    <?php else: ?>
                                    <a class="view" href="<?php echo url('app=seller_order&act=view&order_id=' . $this->_var['tradeInfo']['orderInfo']['order_id']. ''); ?>" target="_blank">查看订单</a>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </div>
                        <?php endif; ?>
                        
                        <div class="trade-slips">
                        	
                        	<dl class="clearfix">
                            	<dt>对方信息：</dt>
                                <dd>
                                	<?php echo $this->_var['tradeInfo']['partyInfo']['name']; ?>
                                	<?php if ($this->_var['tradeInfo']['partyInfo']['account']): ?>&nbsp;&nbsp;<?php echo $this->_var['tradeInfo']['partyInfo']['account']; ?><?php endif; ?>
                                </dd>
                            </dl>
                            <dl class="clearfix time">
                            	<dt>时间报告：</dt>
                                <dd class="clearfix time-head">
                                	<div class="add-time">创建时间</div>
                                    <div class="pay-time">付款时间</div>
                                    <div class="end-time">结束时间</div>
                                </dd>
                                <dd class="clearfix">
                                	<div class="add-time"><?php echo local_date("Y.m.d H:i:s",$this->_var['tradeInfo']['add_time']); ?></div>
                                    <div class="pay-time"><?php echo local_date("Y.m.d H:i:s",$this->_var['tradeInfo']['pay_time']); ?></div>
                                    <div class="end-time"><?php echo local_date("Y.m.d H:i:s",$this->_var['tradeInfo']['end_time']); ?></div>
                                </dd>
                            </dl>
                        </div>
                        
                    </div>
                    <?php endif; ?>
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
