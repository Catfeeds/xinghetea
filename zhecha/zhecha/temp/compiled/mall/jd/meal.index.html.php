<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
	var  holder = $(".J_FixedButtonBottom"),
		oTop = holder.offset().top;

    $(window).unbind('scroll').bind('scroll', function(){
		var dTop = $(document).scrollTop(),
    		wHeight = $(window).height();
    	if (dTop < (oTop - wHeight + 74)) {
    		holder.addClass('fixed');
    	} else {
    		holder.removeClass('fixed');
    	}
	});
});
</script>
<div id="page-meal" class="w-full">
	<div class="w clearfix">
		<div class="mt10">
    		<?php echo $this->fetch('curlocal.html'); ?>
		</div>
    	<div class="mealinfo">
        	<div class="title title_top clearfix">
				<div class="price clearfix">
					<p><span class="txt">优惠套餐：</span><span>原价：<del><?php echo price_format($this->_var['meal']['price_old_total']['min']); ?>~<?php echo price_format($this->_var['meal']['price_old_total']['max']); ?></del></span></p>
                	<p><strong><?php echo price_format($this->_var['meal']['price']); ?></strong></p>
                </div>
                <div class="sale"><b class="J_TotalSave"> <?php echo $this->_var['meal']['default_save']; ?></b></div>
                <div class="desc">说明：1、套餐商品有规格需选择规格；2、套餐商品无规格不用选择，自动获取默认规格。</div>
            </div>
            <div class="content clearfix">
				<form name="meal_form">
            	<div class="box clearfix">
					<h3>请选择商品规格：</h3>
                    <?php $_from = $this->_var['meal']['meal_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
                    <dl class="goodsbox <?php if (($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?> goodsbox-last<?php endif; ?> clearfix">
      					<dt class="pic float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img width="160" height="160" src="<?php echo $this->_var['goods']['default_image']; ?>" /></a></dt>
						<dd class="desc float-left">
     						<h2><strong>[套装商品<?php echo $this->_foreach['fe_goods']['iteration']; ?>]</strong><a class="fs14 f66" href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></h2>
   							<div class="rate"><span>价格：</span><b class="price J_SpecPrice" price="<?php echo $this->_var['goods']['price']; ?>"><?php echo price_format($this->_var['goods']['price']); ?></b></div>
							<div class="handle">
								<?php if ($this->_var['goods']['spec_qty'] > 0): ?>
								<ul class="clearfix">
									<li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_1']); ?>： </li>
									<?php $_from = $this->_var['goods']['spec_1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('id', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['id'] => $this->_var['spec']):
?>
                                	<li onclick="selectSpec(1, this,<?php echo $this->_var['goods']['spec_qty']; ?>,<?php echo $this->_var['goods']['goods_id']; ?>,<?php echo $this->_var['meal']['price']; ?>)" class="<?php if ($this->_var['id'] == $this->_var['goods']['default_spec']): ?>solid<?php else: ?>dotted<?php endif; ?>"><a href="javascript:;"><span><?php echo $this->_var['spec']; ?></span></a></li>
                                	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                           	 	</ul>
                           	 	<?php endif; ?>
                            	<?php if ($this->_var['goods']['spec_qty'] > 1): ?>
                            	<ul class="clearfix">
                            		<li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_2']); ?>：</li>
                                	<?php $_from = $this->_var['goods']['spec_2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('id', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['id'] => $this->_var['spec']):
?>
                                	<li onclick="selectSpec(2, this,<?php echo $this->_var['goods']['spec_qty']; ?>,<?php echo $this->_var['goods']['goods_id']; ?>,<?php echo $this->_var['meal']['price']; ?>)" class="<?php if ($this->_var['id'] == $this->_var['goods']['default_spec']): ?>solid<?php else: ?>dotted<?php endif; ?>"><a href="javascript:;"><span><?php echo $this->_var['spec']; ?></span></a></li>
                                	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            	</ul>
                            	<?php endif; ?>
							</div>
							<input type="hidden" name="specs[]" value="<?php echo $this->_var['goods']['default_spec']; ?>" />
						</dd>
					</dl>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
				<div class="title btitle J_FixedButtonBottom clearfix">
					<div class="price clearfix">
                		<p><span class="txt">优惠套餐：</span><span>原价：<del><?php echo price_format($this->_var['meal']['price_old_total']['min']); ?>~<?php echo price_format($this->_var['meal']['price_old_total']['max']); ?></del></span></p>
                		<p><strong><?php echo price_format($this->_var['meal']['price']); ?></strong> <span class="sale">立省：<b class="J_TotalSave"> <?php echo $this->_var['meal']['default_save']; ?></b></p>
					</div>
                	<div class="buy"><input type="button" class="btn_c1 J_SubmitMealOrder" value="" meal_id="<?php echo $this->_var['meal']['meal_id']; ?>" /></div>
            	</div>
				</form>
            </div>
        </div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>
