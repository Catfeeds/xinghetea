<?php echo $this->fetch('header.html'); ?> 
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/cart.js'; ?>" charset="utf-8"></script> 
<div id="main">
  <div id="page-cart" class="page-cart page-body">
    <form method="post" class="J_Cart">
      <?php $_from = $this->_var['myCart']['carts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('store_id', 'cart');if (count($_from)):
    foreach ($_from AS $this->_var['store_id'] => $this->_var['cart']):
?>
      <div class="store-each cart-it mt10 J_Store-<?php echo $this->_var['store_id']; ?>">
        <div class="store-info">
          <div class="info clearfix">
            <h3>
              <input class="rebuild-checkbox J_SelectAll"  name="store_id" type="checkbox"  id="<?php echo $this->_var['store_id']; ?>" <?php if (! $this->_var['selectedByUser']): ?>checked="checked"<?php elseif (in_array ( $this->_var['store_id'] , $this->_var['selectedByUser']['storeAll'] )): ?> checked="checked"<?php endif; ?> value="<?php echo $this->_var['store_id']; ?>"/>
              <label for="<?php echo $this->_var['store_id']; ?>"><ins>&#xe676;</ins><?php echo htmlspecialchars($this->_var['cart']['store_name']); ?></label>
           	</h3>
            <p class="float-right edit pointer J_Edit" store_id="<?php echo $this->_var['store_id']; ?>">编辑</p>
          </div>
        </div>
        <ul class="padding10" style="padding-top:0;">
          <?php $_from = $this->_var['cart']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
          <li class="J_CartItem-<?php echo $this->_var['goods']['rec_id']; ?> J_GoodsEach pt10 pb10">
            <div class="it clearfix relative"> 
            	<div class="select float-left"><input type="checkbox" name="buy[<?php echo $this->_var['goods']['rec_id']; ?>]" store_id="<?php echo $this->_var['store_id']; ?>" value="<?php echo $this->_var['store_id']; ?>:<?php echo $this->_var['goods']['rec_id']; ?>:<?php echo $this->_var['goods']['goods_id']; ?>" class="J_SelectGoods" <?php if (! $this->_var['selectedByUser']): ?>checked="checked"<?php elseif (in_array ( $this->_var['goods']['rec_id'] , $this->_var['selectedByUser']['goodsList'] )): ?> checked="checked"<?php endif; ?> />
                 </div>
            	<div class="pic"> <a  href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['goods']['store_id']. ''); ?>"><img src="<?php echo $this->_var['goods']['goods_image']; ?>"  width="100" height="100" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" /></a></div>
              <div class="detail">
                <div class="attr"> <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['goods']['store_id']. ''); ?>" class="desc">
                  <h4><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h4>
                  </a>
                  <p class="props"><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></p>
                </div>
                <div class="price clearfix"> <em class="J_ItemPrice-<?php echo $this->_var['goods']['rec_id']; ?> float-left"><?php echo price_format($this->_var['goods']['price']); ?>+<?php echo $this->_var['goods']['max_exchange']; ?>积分</em> <ins class="float-right">x<i ectype="quantity"><?php echo $this->_var['goods']['quantity']; ?></i></ins>
                  <dd class="hidden J_ItemSubtotal-<?php echo $this->_var['goods']['rec_id']; ?> J_GetSubtotal" price="<?php echo $this->_var['goods']['subtotal']; ?>" exchange="<?php echo $this->_var['goods']['subtotal_exchange']; ?>"></dd>
                </div>
              </div>
              <div class="hidden detail hidden-part"> <span class="quantity-handle block"> 
              	<i onclick="decrease_quantity(<?php echo $this->_var['goods']['rec_id']; ?>);"  style="left:0px;"><ins class="pointer"></ins></i>
                <input id="input_item_<?php echo $this->_var['goods']['rec_id']; ?>" type="text" class="J_GetQuantity" value="<?php echo $this->_var['goods']['quantity']; ?>" orig="<?php echo $this->_var['goods']['quantity']; ?>" changed="<?php echo $this->_var['goods']['quantity']; ?>" onkeyup="change_quantity(<?php echo $this->_var['store_id']; ?>, <?php echo $this->_var['goods']['rec_id']; ?>, <?php echo $this->_var['goods']['spec_id']; ?>, this);" />
                <i onclick="add_quantity(<?php echo $this->_var['goods']['rec_id']; ?>);" style="right:44px;" class="add"><ins class="pointer"></ins></i> </span>
                <p class="mt10 ml10 props"><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></p>
                <a class="del" href="javascript:drop_cart_item(<?php echo $this->_var['store_id']; ?>, <?php echo $this->_var['goods']['rec_id']; ?>);" onclick="javascript:return confirm('您确定要删除它吗？');">删除</a> </div>
            </div>
          </li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
      </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
      <div class="go2order clearfix">
        <p class="float-left ml10"> <span>商品总价：</span> <strong class="price fs14 strong mr20 J_CartAllAmount"><?php echo price_format($this->_var['myCart']['allAmount']); ?>+<?php echo $this->_var['myCart']['allAmount_exchange']; ?>积分</strong> </p>
        <input type="submit" class="btn float-right border-0 pointer" value="去结算" />
      </div>
    </form>
  </div>
</div>
<?php echo $this->fetch('footer.html'); ?> 