<?php echo $this->fetch('member.header.html'); ?>
<style>
.member_no_records {border-top: 0px !important;}
.table td{padding-left: 5px;}
</style>
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
							<p class="yellow-big">温馨提示：请勿将赠品作为单独的商品创建搭配套餐，否则有可能会被平台判定为虚假交易。</p>
						</div>
						<div class="create-tips"> <span class="ui-btn-graygrade"><a href="<?php echo url('app=seller_meal&act=add'); ?>">创建搭配套餐</a></span> </div>
						<div class="lst-meal">
							<div class="data-list-item">
								<div class="hd traderates-index-col">
									<ul class="clearfix">
										<li class="col-1"><span class="pl5">名称</span></li>
										<li class="col-2 center">一口价(元)</li>
										<li class="col-3 center">当前状态</li>
										<li class="col-4 center">操作</li>
									</ul>
								</div>
								<div class="bd traderates-index-content">
									<div class="item-list traderates-index-col"> 
										<?php $_from = $this->_var['meal_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'meal');if (count($_from)):
    foreach ($_from AS $this->_var['meal']):
?>
										<ul class="clearfix">
											<li class="col-1"><span class="pl5"><?php echo $this->_var['meal']['title']; ?></span></li>
											<li class="col-2 center"><?php echo $this->_var['meal']['price']; ?></li>
											<li class="col-3 center"><?php if ($this->_var['meal']['status'] == 1): ?>有效<?php else: ?><span class="f60">无效</span><?php endif; ?></li>
											<li class="col-4 center"> <a href="<?php echo url('app=meal&id=' . $this->_var['meal']['meal_id']. ''); ?>" target="_blank">查看</a> <a href="<?php echo url('app=seller_meal&act=edit&id=' . $this->_var['meal']['meal_id']. '&ret_page=' . $this->_var['page_info']['curr_page']. ''); ?>">编辑</a> <a href="javascript:;" confirm="您确定要删除它吗？" class="J_Del" uri="<?php echo url('app=seller_meal&act=drop&id=' . $this->_var['meal']['meal_id']. ''); ?>">删除</a> </li>
										</ul>
										<?php endforeach; else: ?>
										<div class="notice-word mt10">
											<p>没有符合条件的记录</p>
										</div>
										<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
									</div>
								</div>
							</div>
							<?php if ($this->_var['meal_list']): ?><div class="mt20 clearfix"><?php echo $this->fetch('member.page.bottom.html'); ?></div><?php endif; ?>
						</div>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>