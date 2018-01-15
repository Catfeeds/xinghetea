<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
	$('#fullgift_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('li');
            error_td.find('label').hide();
            error_td.append(error);
        },
        success       : function(label){
            label.remove();
        },
		submitHandler:function(form) {
			if($('input[name="fullgift[amount]"]').val()=='') {
				alert('订单满金额不能为空');
				$(form).find('input[name="fullgift[amount]"]').focus().addClass('error');
				return;
			}
			if($('input[name="fullgift[selected_ids][]"').length > 10) {
				$('.J_RecordError').show();
				return;
			}
			if($('input[name="fullgift[selected_ids][]"').length < 1) {
				$('.J_RecordError').show();
				return;
			}
			else form.submit();
		},
        onkeyup: false,
        rules : {
			"fullgift[amount]" : {
				number     : true,
				min		   : 0.01
			}
        },
        messages : {
			"fullgift[amount]" : {
				number     : '订单满金额必须只数值',
				min        : '价格必须大于等于零'
			}
        }
    });	
});
</script>
<div id="page-promotool" class="page-promotool">
	<div class="content">
		<div class="totline"></div>
		<div class="botline"></div>
		<?php echo $this->fetch('member.menu.html'); ?>
		<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
			<?php echo $this->fetch('member.submenu.html'); ?>
			<div class="wrap">
				<div class="public_select">
					<div class="promotool">
						<div class="bundle bundle-list">
                        	<?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
                            <div class="notice-word"><p><?php echo $this->_var['appAvailable']['msg']; ?></p></div>
                        	<?php else: ?>
							<div class="notice-word">
								<p class="yellow-big">温馨提示: 可设置单笔订单商品金额满多少后附赠单件或多件礼品，当有多条记录时，以满足最新的记录为准。</p>
							</div>
							<?php if (! $this->_var['hasGift'] && $_GET['act'] == 'add'): ?>
							<div class="notice-word">
								<p>您还没有添加赠品，<a href="index.php?app=seller_fullgift&act=itemadd">现在添加</a></p>
							</div>
							<?php endif; ?>
							<div class="promotool-form fullgift">
								<form id="fullgift_form" method="post">
									<ul class="form">
										<li>
											<h3>订单金额满</h3>
										</li>
										<li>
											<input type="text" name="fullgift[amount]" id="fullgift[amount]" class="input" value="<?php echo $this->_var['fullgift']['rules']['amount']; ?>" />
											<span>元</span></li>
										<li>
											<h3>赠送礼品</h3>
										</li>
										<li>
											<div class="lst-products clearfix">
												<div class="th clearfix">
													<p class="cell-thumb float-left">赠品图片</p>
													<p class="cell-title float-left">赠品标题</p>
													<p class="cell-price float-left">市面价</p>
													<p class="cell-action float-left">操作</p>
												</div>
												<div class="entity">
													<ul ectype="goods_list">
														<?php $_from = $this->_var['fullgift']['rules']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
														<li class="clearfix">
															<p class="cell-input">
																<input type="hidden" name="fullgift[selected_ids][]" value="<?php echo $this->_var['goods']['goods_id']; ?>" />
															</p>
															<p class="cell-thumb float-left"> <a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50" /></a> </p>
															<p class="cell-title float-left"><a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></p>
															<p class="J_getPrice cell-price float-left" price="<?php echo $this->_var['goods']['price']; ?>"><?php echo $this->_var['goods']['price']; ?></p>
															<p class="cell-action float-left"><a class="J_MealDel" href="javascript:;">删除</a></p>
														</li>
														<?php endforeach; else: ?>
														<div class="pt5 pb5 align2 gray-color">您还没有添加赠品</div>
														<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
													</ul>
													
												</div>
												<p> <a href="javascript:;" gs_id="gselector-gift" gs_name="goods_name" gs_callback="gs_callback" gs_title="选择赠品..." gs_width="690"  gs_type="gift" gs_store_id="<?php echo $this->_var['store_id']; ?>" ectype="gselector" gs_opacity="0.05" gs_class="simple-blue" name="gselector-gift" id="gselector-gift" class="btn-add-product">选择赠品...</a> </p>
												<div class="notice-word mt10 hidden J_RecordError"><p>您至少添加一个赠品，且数量不能操作10个</p></div>
											</div>
										</li>
										<li>
											<h3>启用</h3>
										</li>
										<li class="slide-checkbox-radio clearfix">
											<input type="checkbox" name="status" value="1" id="check_1" class="slide-box slide-checkbox" <?php if ($this->_var['fullgift']['status'] || ! $this->_var['fullgift']): ?> checked="checked" <?php endif; ?>/>
											<label for="check_1" class="slide-trigger"></label>
										</li>
										<li>
											<input type="submit" value="提交" class="submit" />
										</li>
									</ul>
								</form>
							</div>
                            <?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?> 