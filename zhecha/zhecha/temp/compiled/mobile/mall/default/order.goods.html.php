<script type="text/javascript">
$(function(){
	
	$('.J_UseCouponSelect').change(function(){
		if($(this).val()=='') couponPrice = 0;
		else {
			couponPrice = number_format(parseFloat($(this).find('option:selected').attr('price')),2);
		}
		$(this).parent().parent().find('.J_CouponPrice').html(couponPrice);
		fill_order_amount();
	});
	
	$('.J_IconRemark').hover(function(){
		$(this).children().css('display','inline-block');
	}, function(){
		$(this).children().hide();
	});
	
	$('.J_Postscript').click(function(){
		$(this).addClass('max-textarea');
	});
	$('.J_Postscript').blur(function(){
		$(this).removeClass('max-textarea');
	});

});	

</script>

<div class="cartbox mt20">
  <div class="content"> 
    <?php $_from = $this->_var['goods_info']['orderList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('store_id', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['store_id'] => $this->_var['order']):
?>
    <div class="store-each J_Store" store_id="<?php echo $this->_var['store_id']; ?>">
      <div class="store-name clearfix mb10"> <span class="col-desc"> <a href="<?php echo url('app=store&id=' . $this->_var['order']['store_id']. ''); ?>"><ins class="psmb-icon-font fs18 mr5">&#xe676;</ins><?php echo htmlspecialchars($this->_var['order']['store_name']); ?></a> 
        <?php if ($this->_var['order']['im_qq']): ?> 
        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($this->_var['order']['im_qq']); ?>&site=<?php echo htmlspecialchars($this->_var['order']['store_name']); ?>&menu=yes" target="_blank"><img align="absmiddle" src="http://wpa.qq.com/pa?p=1:<?php echo htmlspecialchars($this->_var['order']['im_qq']); ?>:4" alt="qq"></a> 
        <?php endif; ?> 
        </span>
      </div>
      <div class="order-goods">
      <?php $_from = $this->_var['order']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
      <dl class="goods-each clearfix" <?php if (! ($this->_foreach['fe_goods']['iteration'] <= 1)): ?> style="border-top:0;"<?php endif; ?>>
        <dd class="pic"><a class="block" href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" width="48" height="48" /></a></dd>
        <dd class="desc">
          <p><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></p>
          <p class="f66"><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></p>
        </dd>
	
        <dd class="price"><?php echo price_format($this->_var['goods']['price']); ?>+<?php echo $this->_var['goods']['max_exchange']; ?>积分 <span class="quantity">x<?php echo $this->_var['goods']['quantity']; ?></span></dd>
      	<dd class="hidden subtotal fs14 J_Subtotal-<?php echo $this->_var['store_id']; ?>" price="<?php echo $this->_var['goods']['subtotal']; ?>" exchange="<?php echo $this->_var['goods']['subtotal_exchange']; ?>"><?php echo price_format($this->_var['goods']['subtotal']); ?>+<?php echo $this->_var['goods']['subtotal_exchange']; ?>积分</dd>
	  </dl>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
      </div>
      <?php if ($this->_var['order']['growbuy_list']): ?>
      <div class="order-growbuy"> 
        <?php $_from = $this->_var['order']['growbuy_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'growbuy');$this->_foreach['fe_growbuy'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_growbuy']['total'] > 0):
    foreach ($_from AS $this->_var['growbuy']):
        $this->_foreach['fe_growbuy']['iteration']++;
?>
        <div class="growbuy-each">
          <p class="padding5" style=" background:#ddd;">
            <label class="subtit" for="growbuy-<?php echo $this->_var['store_id']; ?>_<?php echo $this->_var['growbuy']['psid']; ?>">
              <input class="J_GrowBuy J_GrowBuy-<?php echo $this->_var['store_id']; ?>" price="<?php echo $this->_var['growbuy']['money']; ?>" type="checkbox" name="growbuy[<?php echo $this->_var['store_id']; ?>][]" id="growbuy-<?php echo $this->_var['store_id']; ?>_<?php echo $this->_var['growbuy']['psid']; ?>" value="<?php echo $this->_var['growbuy']['psid']; ?>" />
              <?php echo sprintf('加价 &yen;<b class="f60">%s</b> 元可购买以下商品', $this->_var['growbuy']['money']); ?> </label>
          </p>
          <?php $_from = $this->_var['growbuy']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
          <dl class="goods-each clearfix" <?php if (! ($this->_foreach['fe_goods']['iteration'] <= 1)): ?> style="border-top:0;"<?php endif; ?>>
            <dd class="pic"><a class="block" href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" width="48" height="48" /></a></dd>
            <dd class="desc">
              <p><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></p>
            </dd>
            <dd class="price"><?php echo price_format($this->_var['goods']['price']); ?><span class="quantity">x<?php echo ($this->_var['goods']['quantity'] == '') ? '1' : $this->_var['goods']['quantity']; ?></span></dd>

          </dl>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
      </div>
      <?php endif; ?> 
      
      <?php if ($this->_var['order']['fullgift']): ?>
      <div class="order-fullgift">
        <p class="padding5" style=" background:#ddd;">
          <label class="subtit" for="fullgift-<?php echo $this->_var['store_id']; ?>_<?php echo $this->_var['order']['fullgift']['psid']; ?>">
            <input type="checkbox" name="fullgift" id="fullgift-<?php echo $this->_var['store_id']; ?>_<?php echo $this->_var['order']['fullgift']['psid']; ?>" value="<?php echo $this->_var['order']['fullgift']['psid']; ?>" checked="checked" disabled="disabled" />
            <?php echo sprintf('卖家赠送赠品（购物满 &yen;<b class="f60">%s</b> 元）', $this->_var['order']['fullgift']['rules']['amount']); ?> </label>
        </p>
        <?php $_from = $this->_var['order']['fullgift']['rules']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
        <dl class="goods-each clearfix" <?php if (! ($this->_foreach['fe_goods']['iteration'] <= 1)): ?> style="border-top:0;"<?php endif; ?>>
          <dd class="pic"><a class="block" href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" width="48" height="48" /></a></dd>
          <dd class="desc">
            <p><a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></p>
          </dd>
          <dd class="price"><?php echo price_format($this->_var['goods']['price']); ?> <span class="quantity">x<?php echo ($this->_var['goods']['quantity'] == '') ? '1' : $this->_var['goods']['quantity']; ?></span></dd>
        </dl>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
      </div>
      <?php endif; ?>
      <div class="order-confirm-extra clearfix">
        <div  class="postscript clearfix">
          <div class="hidden">给卖家的附言：</div>
          <div class="subcontent">
            <textarea class="f66 J_Postscript" name="postscript[<?php echo $this->_var['store_id']; ?>]" placeholder="附注：选填，可以告诉卖家您对商品的特殊需求，如颜色、尺码等"></textarea>
          </div>
        </div>
        <div class="confirm-extra-info">
          <div class="extra-list ship-method clearfix">
            <div class="subtitle float-left">运送方式：</div>
            <div class="subcontent float-left">
              <select name="delivery_type[<?php echo $this->_var['store_id']; ?>]" class="J_LogistFeesSelect J_LogistFeesSelect-<?php echo $this->_var['store_id']; ?>">
                <?php $_from = $this->_var['shipping_methods'][$this->_var['store_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'logist');$this->_foreach['fe_logist'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_logist']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['logist']):
        $this->_foreach['fe_logist']['iteration']++;
?>
                <option value="<?php echo $this->_var['key']; ?>" price="<?php echo $this->_var['logist']['logist_fees']; ?>"><?php echo $this->_var['logist']['name']; ?>：<?php echo ($this->_var['logist']['logist_fees'] == '') ? '0.00' : $this->_var['logist']['logist_fees']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
              </select>
            </div>
            <div class="fee float-right f66">+<em class="J_LogistFees-<?php echo $this->_var['store_id']; ?>"><?php echo $this->_var['logist']['logist_fees']; ?></em></div>
          </div>
          <div class="extra-list use-coupon clearfix">
            <div class="subtitle float-left">店铺优惠：</div>
            <div class="subcontent float-left">
              <select class="J_UseCouponSelect J_UseCouponSelect-<?php echo $this->_var['store_id']; ?>" name="coupon_sn[<?php echo $this->_var['store_id']; ?>]" <?php if (! $this->_var['order']['coupon_list']): ?> disabled="disabled"<?php endif; ?>>
                <option value="">请选择优惠券</option>
                <?php $_from = $this->_var['order']['coupon_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'coupon');if (count($_from)):
    foreach ($_from AS $this->_var['coupon']):
?>
                <option value="<?php echo $this->_var['coupon']['coupon_sn']; ?>" price="<?php echo $this->_var['coupon']['coupon_value']; ?>">SN:<?php echo $this->_var['coupon']['coupon_sn']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
              </select>
            </div>
            <div class="fee float-right f66">-<em class="J_CouponPrice J_CouponPrice-<?php echo $this->_var['store_id']; ?>">0.00</em></div>
          </div>
          
          <?php if ($this->_var['order']['mealprefer']): ?>
          <div class="extra-list use-mealprefer clearfix">
            <div class="subtitle float-left">搭配套餐：</div>
            <div class="subcontent float-left"> <span><?php echo $this->_var['order']['mealprefer']['text']; ?></span> </div>
            <div class="fee float-right f66">-<em class="J_MealPreferPrice J_MealPreferPrice-<?php echo $this->_var['store_id']; ?>"><?php echo $this->_var['order']['mealprefer']['price']; ?></em></div>
          </div>
          <?php endif; ?> 
          
          <?php if ($this->_var['order']['fullprefer']): ?>
          <div class="extra-list use-fullprefer clearfix">
            <div class="subtitle float-left">满折满减：</div>
            <div class="subcontent float-left"> <span><?php echo $this->_var['order']['fullprefer']['text']; ?></span> </div>
            <div class="fee float-right f66">-<em class="J_FullPreferPrice J_FullPreferPrice-<?php echo $this->_var['store_id']; ?>"><?php echo $this->_var['order']['fullprefer']['price']; ?></em></div>
          </div>
          <?php endif; ?> 
          
        </div>
        <div class="confirm-extra-bottom clearfix mt10"> <span class="price fs14 store-amount-fields">店铺合计（含运费）：&nbsp;&nbsp; <i class="float-right">&yen;<em class="J_OrderAmount-<?php echo $this->_var['store_id']; ?>" style="font-size:16px"><?php echo $this->_var['order']['amount']; ?>+<?php echo $this->_var['order']['amount_exchange']; ?>积分</em></i> </span> </div>
      </div>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
  </div>
</div>
