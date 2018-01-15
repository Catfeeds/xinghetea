<?php echo $this->fetch('top.html'); ?> 
<script>
$(function() {
	if($('#slides').children('a').length>1){
		$('#slides').slidesjs({
			width: 320,
			height: 120,
			navigation: false,
			play: {
				auto: true
			}
		});
	}
});
</script>
<div id="page-store" class="page-store page J_page"> 
	
	<div class="bar-wrap">
		<div class="top-bar"><a href="javascript:pageBack();" class="pageback"><span></span></a>
			<div class="search-box">
				<form id="search-form" method="get">
					<input type="hidden" name="app" value="store" />
                    <input type="hidden" name="act" value="search" />
					<input type="hidden" name="id" value="<?php echo $this->_var['store']['store_id']; ?>" />
					<input name="keyword"  type="text" class="kw" placeholder="请输入你要搜索的商品名称" value="<?php echo $_GET['keyword']; ?>" />
					<input type="submit" class="submit" value="">
				</form>
			</div>
			<a class="page-store-more" onclick="PsmobanShowMenu();"><span></span></a> </div>
	</div>
	
	<div class="store-info">
		<?php if ($this->_var['ad1']['ad_image_url']): ?>
        <div class="full-banner"> <a href="<?php echo $this->_var['ad1']['ad_link_url']; ?>"><img  src="<?php echo $this->_var['ad1']['ad_image_url']; ?>"/></a> </div>
        <?php endif; ?> 
		<div class="d-info clearfix">
			<h3 class="float-left"><a href="<?php if ($this->_var['my_store']): ?><?php echo url('app=dcenter&act=edit&did=' . $_GET['did']. ''); ?><?php else: ?>javascript:;<?php endif; ?>"><img src="<?php echo $this->_var['store']['store_logo']; ?>" /></a></h3>
			<div class="name-and-credit float-left">
				<p class="mt10 name"><a href="<?php if ($this->_var['my_store']): ?><?php echo url('app=dcenter&act=edit&did=' . $_GET['did']. ''); ?><?php else: ?>javascript:;<?php endif; ?>"><?php echo $this->_var['store']['store_name']; ?></a></p>
				<p><?php if ($this->_var['store']['credit_value'] >= 0): ?><img src="<?php echo $this->_var['store']['credit_image']; ?>" alt="" /><?php endif; ?></p>
			</div>
			<p class="collect-btn J_AjaxRequest" action="<?php echo url('app=my_favorite&act=add&type=store&item_id=' . $this->_var['store']['store_id']. '&ajax=1'); ?>"></p>
		</div>
	</div>
	<ul class="store-statistic clearfix mb5">
		<li>
			<dl>
				<dt><b><?php echo $this->_var['store']['goods_count']; ?></b></dt>
				<dd><span>商品</span></dd>
			</dl>
		</li>
        <?php if ($_GET['did']): ?>
		<li>
        	<a href="<?php echo url('act=code&did=' . $_GET['did']. ''); ?>">
			<dl>
            	<dt><b class="psmb-icon-font">&#xe6b0;</b></dt>
				<dd><span>二维码</span></dd>
			</dl>
            </a>
		</li>
        <?php else: ?>
        <li>
			<dl>
				<dt><b><?php echo $this->_var['store_static']['new_count']; ?></b></dt>
				<dd><span>上新</span></dd>
			</dl>
		</li>
        <?php endif; ?>
		<li>
			<dl>
				<dt><b><?php echo $this->_var['store_static']['order_count']; ?></b></dt>
				<dd><span>成交</span></dd>
			</dl>
		</li>
		<li>
			<dl style="border:0px;">
				<dt><b><?php echo $this->_var['store']['praise_rate']; ?>%</b></dt>
				<dd><span>好评率</span></dd>
			</dl>
		</li>
	</ul>
    <div class="store-index-slide">
			<div id="slides" class="scroller"> 
				<?php $_from = $this->_var['slides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'slide');$this->_foreach['fe_slide'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_slide']['total'] > 0):
    foreach ($_from AS $this->_var['slide']):
        $this->_foreach['fe_slide']['iteration']++;
?> 
				<?php if ($this->_var['slide']['ad_image_url'] && $this->_var['slide']['ad_link_url']): ?>
				<a href="<?php echo $this->_var['slide']['ad_link_url']; ?>" class="store-banner"><img src="<?php echo $this->_var['slide']['ad_image_url']; ?>" /></a> 
				<?php endif; ?> 
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</div>
		</div>
	<?php if ($this->_var['recommended_goods']): ?>
	<div class="rec-goods goods-model-si">
		<h3>推荐商品</h3>
		<ul class="clearfix">
			<?php $_from = $this->_var['recommended_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rg');$this->_foreach['fe_rg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_rg']['total'] > 0):
    foreach ($_from AS $this->_var['rg']):
        $this->_foreach['fe_rg']['iteration']++;
?>
			<li class="clearfix"> 
				<?php $_from = $this->_var['rg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rgoods');$this->_foreach['fe_rgoods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_rgoods']['total'] > 0):
    foreach ($_from AS $this->_var['rgoods']):
        $this->_foreach['fe_rgoods']['iteration']++;
?>
				<div class="item">
					<div <?php if ($this->_foreach['fe_rgoods']['iteration'] % 2 == 0): ?>style="margin-left:5px;margin-right:0rem;"<?php endif; ?> class="wrap">
						<div class="wrap2">
							<div class="pic"><a href="<?php echo url('app=goods&id=' . $this->_var['rgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['rgoods']['default_image']; ?>" /></a></div>
							<h3><a href="<?php echo url('app=goods&id=' . $this->_var['rgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['rgoods']['goods_name']); ?></a></h3>
							<p>￥<?php echo $this->_var['rgoods']['price']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<?php endif; ?> 
	<?php if ($this->_var['new_goods']): ?>
	<div class="new-goods goods-model-si">
		<h3>新品</h3>
		<ul class="clearfix">
			<?php $_from = $this->_var['new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ng');$this->_foreach['fe_ng'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_ng']['total'] > 0):
    foreach ($_from AS $this->_var['ng']):
        $this->_foreach['fe_ng']['iteration']++;
?>
			<li class="clearfix"> 
				<?php $_from = $this->_var['ng']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ngoods');$this->_foreach['fe_ngoods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_ngoods']['total'] > 0):
    foreach ($_from AS $this->_var['ngoods']):
        $this->_foreach['fe_ngoods']['iteration']++;
?>
				<div class="item">
					<div <?php if ($this->_foreach['fe_ngoods']['iteration'] % 2 == 0): ?>style="margin-left:5px;margin-right:0rem;"<?php endif; ?> class="wrap">
						<div class="wrap2">
							<div class="pic"><a href="<?php echo url('app=goods&id=' . $this->_var['ngoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['ngoods']['default_image']; ?>" /></a></div>
							<h3><a href="<?php echo url('app=goods&id=' . $this->_var['ngoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['ngoods']['goods_name']); ?></a></h3>
							<p>￥<?php echo $this->_var['ngoods']['price']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<?php endif; ?> 
	<?php if ($this->_var['sales_goods']): ?>
	<div class="hot-goods goods-model-si">
		<h3>热卖</h3>
		<ul class="clearfix">
			<?php $_from = $this->_var['sales_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sg');$this->_foreach['fe_sg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_sg']['total'] > 0):
    foreach ($_from AS $this->_var['sg']):
        $this->_foreach['fe_sg']['iteration']++;
?>
			<li class="clearfix"> 
				<?php $_from = $this->_var['sg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sgoods');$this->_foreach['fe_sgoods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_sgoods']['total'] > 0):
    foreach ($_from AS $this->_var['sgoods']):
        $this->_foreach['fe_sgoods']['iteration']++;
?>
				<div class="item">
					<div <?php if ($this->_foreach['fe_sgoods']['iteration'] % 2 == 0): ?>style="margin-left:5px;margin-right:0rem;"<?php endif; ?> class="wrap">
						<div class="wrap2">
							<div class="pic"><a href="<?php echo url('app=goods&id=' . $this->_var['sgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['sgoods']['default_image']; ?>" /></a></div>
							<h3><a href="<?php echo url('app=goods&id=' . $this->_var['sgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['sgoods']['goods_name']); ?></a></h3>
							<p>￥<?php echo $this->_var['sgoods']['price']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<?php endif; ?> 
	<?php if ($this->_var['hot_goods']): ?>
	<div class="new-goods goods-model-si">
		<h3>人气</h3>
		<ul class="clearfix">
			<?php $_from = $this->_var['hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hg');$this->_foreach['fe_hg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_hg']['total'] > 0):
    foreach ($_from AS $this->_var['hg']):
        $this->_foreach['fe_hg']['iteration']++;
?>
			<li class="clearfix"> 
				<?php $_from = $this->_var['hg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hgoods');$this->_foreach['fe_hgoods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_hgoods']['total'] > 0):
    foreach ($_from AS $this->_var['hgoods']):
        $this->_foreach['fe_hgoods']['iteration']++;
?>
				<div class="item">
					<div <?php if ($this->_foreach['fe_hgoods']['iteration'] % 2 == 0): ?>style="margin-left:5px;margin-right:0rem;"<?php endif; ?> class="wrap">
						<div class="wrap2">
							<div class="pic"><a href="<?php echo url('app=goods&id=' . $this->_var['hgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['hgoods']['default_image']; ?>" /></a></div>
							<h3><a href="<?php echo url('app=goods&id=' . $this->_var['hgoods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['hgoods']['goods_name']); ?></a></h3>
							<p>￥<?php echo $this->_var['hgoods']['price']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<?php endif; ?>
	<div class="view-all"> <a href="<?php echo url('app=store&act=search&id=' . $this->_var['store']['store_id']. ''); ?>"><i></i><span>全部商品</span></a> </div>
</div>
<div class="masker J_masker" onclick="PsmobanShowMenu();"></div>
<div class="menus J_menus">
    <div class="attrs" style="overflow: auto;height: 100%">
        <?php $_from = $this->_var['store']['store_gcates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gcategory');if (count($_from)):
    foreach ($_from AS $this->_var['gcategory']):
?>
        <div class="attr">
      <div class="attrk"><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. '&act=search&cate_id=' . $this->_var['gcategory']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['gcategory']['value']); ?></a></div>
          <div class="attrv">
            <ul class="clearfix">
              <?php $_from = $this->_var['gcategory']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_gcategory');$this->_foreach['fe_gcategory'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_gcategory']['total'] > 0):
    foreach ($_from AS $this->_var['child_gcategory']):
        $this->_foreach['fe_gcategory']['iteration']++;
?>
              <li <?php if ($this->_foreach['fe_gcategory']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. '&act=search&cate_id=' . $this->_var['child_gcategory']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['child_gcategory']['value']); ?></a></li>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php if ($this->_var['gcategory']['count'] > 4): ?>
            <div class="options">
              <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?> 