<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){	
	$('.ratyItem').each(function(){
		
		var iconId = $(this).find('.ratyIcon').attr('id');
		var targetId = $(this).find('.ratyTarget').attr('id');
		var scoreName = $(this).attr('scoreName');
		
		$('#'+iconId).raty({
			score: 5,
            target : '#'+targetId,
			cancel    : false,
			targetKeep: true,
			scoreName: scoreName
       });
	   
	})
})
</script>
<div class="main" class="w-full">
<div id="page-cart" class="w auto clearfix">
   <div class="step step1000 step5 mt10 clearfix">
      <span class="fs14 f60">1.查看购物车</span>
      <span class="fs14 f60">2.确认订单信息</span>
      <span class="fs14 f60">3.付款</span>
      <span class="fs14 f60">4.确认收货</span>
      <span class="fs14 fff">5.评价</span>
   </div>
   <div class="particular mt10">
        <div class="particular_wrap">
            <form method="POST">
            <h2>订单评价</h2>
            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
            <div class="evaluate_obj">
                <dl class="info">
                    <dt>评价商品</dt>
                    <dd>店铺名: <a href="<?php echo url('app=store&id=' . $this->_var['order']['seller_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['order']['seller_name']); ?></a></dd>
                </dl>

                <div class="ware_line">
                    <div class="ware">
                        <div class="ware_list">
                            <div class="ware_pic"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" width="50" height="50"  /></div>
                            <div class="ware_text">
                                <div class="ware_text4">
                                    <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a><br />
                                    <span><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></span>
                                </div>
                                <div class="ware_text3">
                                    <span>数量&nbsp;:&nbsp;<strong><?php echo $this->_var['goods']['quantity']; ?></strong></span>
                                    <span>价格&nbsp;:&nbsp;<strong><?php echo price_format($this->_var['goods']['price']); ?></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="evaluate_wrap">
                    <div class="my_evaluate">
                        <div class="w-full clearfix">
                            <div class="fill_in">
                                <h4>我的评价</h4>
                                <div>
                                    <b><label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op1"><input id="g<?php echo $this->_var['goods']['rec_id']; ?>_op1" type="radio" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="3" checked  />好评<span>(加一分)</span></label></b>
                                    <b><label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op2"><input id="g<?php echo $this->_var['goods']['rec_id']; ?>_op2" type="radio" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="2" /> 中评<span>(不加分)</span></label></b>
                                    <b><label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op3"><input id="g<?php echo $this->_var['goods']['rec_id']; ?>_op3" type="radio" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="1" /> 差评<span>(扣一分)</span></label></b>
                                </div>
                                <div class="textarea"><textarea name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][comment]"></textarea></div>
                            </div>
                            <dl>
                                <dt>注意&nbsp;:&nbsp;</dt>
                                <dd>
                                     请您根据本次交易，给予真实、客观、仔细地评价。<br />
                您的评价将是其他会员的参考，也将影响卖家的信用。 <br />
                累积信用和计分规则： <br />
                中评不计分，但会影响卖家的好评率，请慎重给予。
                                </dd>
                            </dl>
                        </div>
                        <div style="width:800px;" class="fill_in">
                                <h4>店铺动态评分</h4>
                                <ul class="raty pb10">
                                    <li class="mb10 w-full clearfix ratyItem"  scoreName="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][goods_evaluation]"> <span  class="float-left mr10 t">商品评分：</span> <span id="gIcon<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left mr10 ratyIcon"></span> <span id="gTarget<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left ratyTarget hint"></span>
                                    </li>
                                    <li class="mb10 w-full clearfix ratyItem"  scoreName="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][service_evaluation]"> <span class="float-left mr10 t">服务评分：</span> <span id="svIcon<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left mr10 ratyIcon"></span> <span id="svTarget<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left ratyTarget  hint"></span>
                                    </li>
                                    <li class="mb10 w-full clearfix ratyItem"  scoreName="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][shipped_evaluation]"> <span class="float-left mr10 t">发货评分：</span> <span id="shIcon<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left mr10 ratyIcon"></span> <span id="shTarget<?php echo $this->_foreach['fe_goods']['iteration']; ?>" class="float-left ratyTarget  hint" ></span>
                                    </li>
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <div class="evaluate_footer mt10">
                <input type="submit" value="提交" class="btn1" />
                <input type="button" onclick="window.location.href='/index.php?app=buyer_order';" value="以后再评" class="btn2" />
            </div>
            <div class="particular_bottom"></div>
            </form>
        </div>

        <div class="clear"></div>
        <div class="adorn_right1"></div>
        <div class="adorn_right2"></div>
        <div class="adorn_right3"></div>
        <div class="adorn_right4"></div>
    </div>
</div>
</div>
<?php echo $this->fetch('footer.html'); ?>