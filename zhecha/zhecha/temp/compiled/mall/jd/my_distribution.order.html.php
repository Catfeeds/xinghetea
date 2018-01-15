<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
<style type="text/css">
.tradelist li{text-align:center; width:130px;}
</style>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right">
  		<?php echo $this->fetch('member.curlocal.html'); ?>
    	<?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
         	<div class="public_index table">
                <table>
                	<tr class="line_bold">
                        <th colspan="8">
                            <div class="search_order clearfix">
                				<form method="get" class="clearfix">
               						<div class="float-left">
                						<span class="title">订单号:</span>
                						<input class="text_normal" type="text" name="order_sn" value="<?php echo htmlspecialchars($this->_var['query']['order_sn']); ?>" />
                						<span class="title">加入时间:</span>
                						<input class="text_normal width2" type="text" name="add_time_from" id="add_time_from" value="<?php echo $this->_var['query']['add_time_from']; ?>" /> &#8211; <input class="text_normal width2" id="add_time_to" type="text" name="add_time_to" value="<?php echo $this->_var['query']['add_time_to']; ?>" />
                						<span class="title">买家:</span>
                						<input class="text_normal" type="text" name="buyer_name" value="<?php echo htmlspecialchars($this->_var['query']['buyer_name']); ?>" />
                						<input type="hidden" name="app" value="my_distribution" />
                						<input type="hidden" name="act" value="order" />
                						<input type="submit" class="btn" value="搜索" />
                					</div>
                					<?php if ($this->_var['query']['buyer_name'] || $this->_var['query']['add_time_from'] || $this->_var['query']['add_time_to'] || $this->_var['query']['order_sn']): ?>
                    				<a class="detlink" href="<?php echo url('app=my_distribution&act=order'); ?>">取消检索</a>
                					<?php endif; ?>
								</form>
        					</div>
                        </th>
                    </tr>
                	<tr class="sep-row" height="20"><td colspan="8"></td></tr>
                    <tr class="line gray">
                        <th>商品名称</th>
                        <th>价格</th>
                        <th>数量</th>
                        <th>买家</th>
                        <th>订单总价</th>
                        <th>订单状态</th>
                        <th>分销商</th>
                        <th>分销支出</th>
                    </tr>
                    
                    <?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order');$this->_foreach['fe_order'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_order']['total'] > 0):
    foreach ($_from AS $this->_var['order']):
        $this->_foreach['fe_order']['iteration']++;
?>
                    <tr class="sep-row"><td colspan="8"></td></tr>
                    <tr class="line-hd">
                    	<th colspan="8" class="clearfix">
                        	<p class="float-left">
                            	<label>订单号：</label><?php echo $this->_var['order']['order_sn']; ?>
                            	<label>成交时间：</label><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['add_time']); ?>
                            </p>
                        </th>
                    </tr>
                    <?php $_from = $this->_var['order']['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
                    <tr class="line<?php if (($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?> last_line<?php endif; ?>">
                        <td valign="top" class="first clearfix">
                        	<div class="pic-info float-left">
                            	<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="50" height="50" /></a>
                            </div>
                            <div class="txt-info float-left">
                            	<div class="txt">
                                	<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a>
                                </div>
                                <?php if ($this->_var['goods']['specification']): ?><p class="gray-color mt5"><?php echo $this->_var['goods']['specification']; ?></p><?php endif; ?>
                            </div>
                        </td>
                        <td valign="top" class="align2"><?php echo $this->_var['goods']['price']; ?></td>
                        <td valign="top" class="align2"><strong><?php echo $this->_var['goods']['quantity']; ?></strong></td>
                        <?php if (($this->_foreach['fe_goods']['iteration'] <= 1)): ?>
                        <td valign="top" class="align2 bottom-blue" rowspan="<?php echo $this->_var['order']['goods_quantities']; ?>">
                        	<a href="<?php echo url('app=message&act=send&to_id=' . $this->_var['order']['buyer_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['order']['buyer_name']); ?></a>
                            <br />
                            <?php if ($this->_var['order']['buyer_info']['real_name']): ?><?php echo sub_str(htmlspecialchars($this->_var['order']['buyer_info']['real_name']),14); ?><?php else: ?>----<?php endif; ?>
                            <br />
                            <?php if ($this->_var['order']['buyer_info']['im_qq']): ?>
                            <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($this->_var['order']['buyer_info']['im_qq']); ?>&site=<?php echo htmlspecialchars($this->_var['store']['store_name']); ?>&menu=yes" target="_blank"><img src="http://wpa.qq.com/pa?p=1:<?php echo htmlspecialchars($this->_var['order']['buyer_info']['im_qq']); ?>:5" alt="QQ"></a>
                            <?php elseif ($this->_var['order']['buyer_info']['im_aliww']): ?>
                            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo urlencode($this->_var['order']['buyer_info']['im_aliww']); ?>&site=cntaobao&s=2&charset=<?php echo $this->_var['charset']; ?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo urlencode($this->_var['order']['buyer_info']['im_aliww']); ?>&site=cntaobao&s=2&charset=<?php echo $this->_var['charset']; ?>" alt="Wang Wang" /></a>
                            <?php elseif ($this->_var['order']['buyer_info']['im_msn']): ?>
                            <a target="_blank" href="http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee=<?php echo htmlspecialchars($this->_var['order']['buyer_info']['im_msn']); ?>"><img src="http://messenger.services.live.com/users/<?php echo htmlspecialchars($this->_var['order']['buyer_info']['im_msn']); ?>/presenceimage/" alt="im_msn" /></a>
                            <?php else: ?>
                            <a target="_blank" href="<?php echo url('app=message&act=send&to_id=' . $this->_var['order']['buyer_id']. ''); ?>" class="email"></a>
                            <?php endif; ?>
                        </td>
                        <td valign="top" class="align2 bottom-blue" rowspan="<?php echo $this->_var['order']['goods_quantities']; ?>">
                        	<strong><?php echo $this->_var['order']['order_amount']; ?></strong><br />
                            <span class="gray-color">(含运费:<?php echo $this->_var['order']['shipping_fee']; ?>)</span>
                        </td>
                        <td valign="top"class="align2 bottom-blue" rowspan="<?php echo $this->_var['order']['goods_quantities']; ?>">
                        	<div class="btn-order-status">
                            	<p><span class="<?php if ($this->_var['order']['status'] == 0): ?>gray-color<?php else: ?>color4<?php endif; ?>"><?php echo call_user_func("order_status",$this->_var['order']['status']); ?></span></p>
                            	
                            	<a href="<?php echo url('app=seller_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>" target="_blank">查看订单</a>
                        	</div>
                        </td>
                        <td valign="top" class="align2 bottom-blue" rowspan="<?php echo $this->_var['order']['goods_quantities']; ?>"><a href="<?php echo url('app=my_distribution&real_name=' . $this->_var['order']['distributioner']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['order']['distributioner']); ?></a></td>
                        <td valign="top" width="54" class="align2 bottom-blue last" rowspan="<?php echo $this->_var['order']['goods_quantities']; ?>">
                      		<p><?php echo price_format($this->_var['order']['record_amount']); ?></p>     
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endforeach; else: ?>
                    <tr><td colspan="8"><div class="notice-word"><p>没有符合条件的记录</p></div></td></tr>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['orders']): ?>
                    <tr class="sep-row">
                        <td colspan="8"></td>
                    </tr>
                    <tr class="operations">
                        <th colspan="8">
                            <p class="position2 clearfix">
                                <?php echo $this->fetch('member.page.bottom.html'); ?>
                            </p>
                        </th>
                    </tr>
                    <?php endif; ?>
                    </table>
                    <iframe name="seller_order" style="display:none;"></iframe>
        </div>
    
    </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>