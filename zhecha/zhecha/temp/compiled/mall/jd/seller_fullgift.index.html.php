<?php echo $this->fetch('member.header.html'); ?>
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
							<?php if (! $this->_var['hasGift']): ?>
							<div class="notice-word">
								<p>您还没有添加赠品，<a href="index.php?app=seller_fullgift&act=itemadd">现在添加</a></p>
							</div>
							<?php endif; ?>
							<div class="promotool-list fullgift">
								<div class="lst-meal">
									<div class="data-list-item">
										<div class="hd traderates-index-col">
											<ul class="clearfix">
												<li class="col-1"><span class="pl5">订单金额满</span></li>
												<li class="col-2">赠品列表</li>
												<li class="col-3 center">状态</li>
												<li class="col-4 center">操作</li>
											</ul>
										</div>
										<div class="bd traderates-index-content">
											<div class="item-list traderates-index-col"> 
												<?php $_from = $this->_var['fullgift_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?> 
												
												<ul class="clearfix">
													<li class="col-1"><span class="pl5 fs14 price f60"><?php echo price_format($this->_var['list']['rules']['amount']); ?></span></li>
													<li class="col-2"> 
														<?php $_from = $this->_var['list']['rules']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
														<p>
															<a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank">
																<?php echo $this->_var['goods']['goods_name']; ?>
																<?php if (! $this->_var['goods']['if_show']): ?>
																<span class="f60">[已失效]</span>
																<?php endif; ?>
															</a>
														</p>
														<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
													</li>
													<li class="col-3 center"><?php if ($this->_var['list']['status'] == 1): ?>有效<?php else: ?><span class="f60">无效</span><?php endif; ?></li>
													<li class="col-4 center"> <a href="<?php echo url('app=seller_fullgift&act=edit&id=' . $this->_var['list']['psid']. '&ret_page=' . $this->_var['page_info']['curr_page']. ''); ?>">编辑</a> <a href="javascript:;" confirm="您确定要删除它吗？" class="J_Del" uri="<?php echo url('app=seller_fullgift&act=drop&id=' . $this->_var['list']['psid']. ''); ?>">删除</a> </li>
												</ul>
												
												<?php endforeach; else: ?>
												<div class="notice-word mt10">
													<p>没有符合条件的记录</p>
												</div>
												<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
											</div>
										</div>
									</div>
									<div class="mt20 clearfix"><?php echo $this->fetch('member.page.bottom.html'); ?></div>
								</div>
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