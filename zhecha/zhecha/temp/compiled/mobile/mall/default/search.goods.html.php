<?php echo $this->fetch('top.html'); ?> 
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/search_goods.js'; ?>" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$("img.lazyload").lazyLoad();
	var order = '<?php echo $_GET['order']; ?>';
	var	order_arr = order.split(' ');
	$('#'+order_arr[0]).addClass('current');
	var type = order_arr[0];
	var char;
	switch(type)
	{
		case 'price':
		char = '价格';
		break;
		case 'sales':
		char = '销量';
		break;
		case 'add_time':
		char = '新品';
		break;
	}
	if(type !=="") $('#'+order_arr[0]+' span').html(order_arr[1]=="desc" ? char+"↓" : char+"↑" );
	$(".display-order li").click(function(){
		if(this.id==''){
			dropParam('order');// default order
			return false;
		}
		else
		{
			dd = " desc";
			if(order != '') {
				if(order_arr[0]==this.id && order_arr[1]=="desc")
					dd = " asc";
				else dd = " desc";
			}
			replaceParam('order', this.id+dd);
			return false;
		}
	});
});
</script>
<div id="main"> 
<div id="page-search-goods" class="page J_page"> 
	
	<div class="bar-wrap">
		<div style="line-height:30px;" class="top-bar"> <a href="javascript:pageBack();" class="pageback"><span></span></a>
			<div class="search-box">
				<form id="search-form" method="get">
					<input type="hidden" name="app" value="search" />
					<input name="keyword"  type="text" class="kw" placeholder="请输入关键词" value="<?php echo $_GET['keyword']; ?>"/>
					<input type="submit" class="submit" value="">
				</form>
			</div>
			<a href="javascript:;" onclick="PsmobanShowMenu();" class="filter">筛选</a> </div>
	</div>
	
	<div class="search-goods page-body"> 
		<?php if ($this->_var['goods_list']): ?>
		<div class="attr">
			<div class="cons">
				<ul class="display-order clearfix">
					<li <?php if ($_GET['order'] == ''): ?>class="current"<?php endif; ?>><span>综合</span></li>
					<li id="add_time"><span>新品</span></li>
					<li id="sales"><span>销量</span></li>
					<li id="price"><span>价格</span></li>
				</ul>
			</div>
		</div>
		<ul class="<?php echo $this->_var['display_mode']; ?> " ectype="current_display_mode">
			<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
			<li class="clearfix" <?php if (($this->_foreach['fe_goods']['iteration'] == $this->_foreach['fe_goods']['total'])): ?>style="border-bottom:0px;"<?php endif; ?>> <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=2'); ?>">
				<div class="pic"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" /></div>
				<div class="info">
					<h2><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h2>
						<div class="price mt5">
							<?php echo price_format($this->_var['goods']['price']); ?>
							<?php if ($this->_var['goods']['pro_type']): ?><i class="icon-promo"><?php echo $this->_var['lang'][$this->_var['goods']['pro_type']]; ?></i><?php endif; ?>
						</div>
					<div class="sales mt5">已售<?php echo ($this->_var['goods']['sales'] == '') ? '0' : $this->_var['goods']['sales']; ?> 件 , <a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&store_id=2'); ?>"><?php echo ($this->_var['goods']['comments'] == '') ? '0' : $this->_var['goods']['comments']; ?> 评论</a></div>
				</div>
				</a> </li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="pageinfo pt10"><?php echo $this->fetch('page.bottom.html'); ?></div>
		<?php else: ?>
		<div class="pb20 pt20 center">很抱歉！没有找到相关商品</div>
		<?php endif; ?> 
	</div>
</div>
</div>
<div class="masker J_masker" onclick="PsmobanShowMenu();"></div>
<div class="menus J_menus">
	<div class="attrs" style="overflow: auto;height: 100%"> 
		<?php if ($this->_var['filters'] || $_GET['cate_id']): ?>
		<div class="attr attr-extra">
			<div class="attrk">您已选择：</div>
			<div class="attrv">
				<ul class="clearfix selected-attr">
					<?php if ($this->_var['selected_cate']): ?>
					<li><a <?php if ($this->_var['selected_cate']['parent_id'] > 0): ?>href="<?php echo url('app=search&cate_id=' . $this->_var['selected_cate']['parent_id']. ''); ?>"<?php else: ?>href="javascript:void(0);" id="cate_id" class="each-filter"<?php endif; ?>><span>商品分类：<?php echo $this->_var['selected_cate']['cate_name']; ?></span></a></li>
					<?php endif; ?> 
					<?php $_from = $this->_var['filters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'filter');if (count($_from)):
    foreach ($_from AS $this->_var['filter']):
?>
					<li><a href="javascript:void(0);" id="<?php echo $this->_var['filter']['key']; ?>" class="each-filter"><span><?php echo $this->_var['filter']['name']; ?>：<?php echo $this->_var['filter']['value']; ?></span></a></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<div class="attr"> 
			<?php if ($this->_var['categories']): ?>
			<div class="attrk">商品分类</div>
			<div class="attrv">
				<ul class="clearfix"  ectype="ul_cate">
					<?php $_from = $this->_var['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gcategory');$this->_foreach['fe_gcategory'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_gcategory']['total'] > 0):
    foreach ($_from AS $this->_var['gcategory']):
        $this->_foreach['fe_gcategory']['iteration']++;
?>
					<li <?php if ($this->_foreach['fe_gcategory']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="javascript:void(0);" id="<?php echo $this->_var['gcategory']['cate_id']; ?>"><span><?php echo $this->_var['gcategory']['cate_name']; ?>(<?php echo $this->_var['gcategory']['count']; ?>)</span></a></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php if ($this->_var['category_count'] > 4): ?>
				<div class="options"> <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a> </div>
				<?php endif; ?> 
			</div>
			<?php endif; ?> 
			<?php if ($this->_var['brands'] && ! $this->_var['filters']['brand']): ?>
			<div class="attrk">品牌</div>
			<div class="attrv">
				<ul  ectype="ul_brand" class="clearfix">
					<?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');$this->_foreach['fe_row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_row']['total'] > 0):
    foreach ($_from AS $this->_var['row']):
        $this->_foreach['fe_row']['iteration']++;
?>
					<li <?php if ($this->_foreach['fe_row']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="javascript:void(0);" id="<?php echo htmlspecialchars($this->_var['row']['brand']); ?>"><span><?php echo htmlspecialchars($this->_var['row']['brand']); ?>(<?php echo $this->_var['row']['count']; ?>)</span></a></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php if ($this->_var['brand_count'] > 4): ?>
				<div class="options"> <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a> </div>
				<?php endif; ?> 
			</div>
			<?php endif; ?> 
			
			<?php $_from = $this->_var['props']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'prop');$this->_foreach['fe_prop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_prop']['total'] > 0):
    foreach ($_from AS $this->_var['prop']):
        $this->_foreach['fe_prop']['iteration']++;
?>
			<div class="attrk"><?php echo $this->_var['prop']['name']; ?></div>
			<div class="attrv">
				<ul  ectype="ul_prop" class="clearfix">
					<?php $_from = $this->_var['prop']['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');$this->_foreach['fe_row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_row']['total'] > 0):
    foreach ($_from AS $this->_var['row']):
        $this->_foreach['fe_row']['iteration']++;
?>
					<li <?php if ($this->_foreach['fe_row']['iteration'] > 4): ?>class="hidden"<?php endif; ?>> <a href="javascript:void(0);" id="<?php echo $this->_var['row']['pid']; ?>:<?php echo $this->_var['row']['vid']; ?>" selected_props="<?php echo $this->_var['props_selected']; ?>"> <span> 
						<?php if ($this->_var['prop']['is_color_prop']): ?> 
						<i <?php if ($this->_var['row']['color_value']): ?> class="color" style="background:<?php echo $this->_var['row']['color_value']; ?>"<?php else: ?>class="color duocai"<?php endif; ?> title="<?php echo $this->_var['row']['prop_value']; ?>"><?php echo htmlspecialchars($this->_var['row']['prop_value']); ?></i> 
						<?php else: ?> 
						<?php echo htmlspecialchars($this->_var['row']['prop_value']); ?> 
						<?php endif; ?> 
						</span> </a> </li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php if ($this->_var['prop']['prop_count'] > 4): ?>
				<div class="options"> <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a> </div>
				<?php endif; ?> 
			</div>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			
			<?php if ($this->_var['price_intervals'] && ! $this->_var['filters']['price']): ?>
			<div class="attrk">价格</div>
			<div class="attrv">
				<ul class="clearfix" ectype="ul_price">
					<?php $_from = $this->_var['price_intervals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');$this->_foreach['fe_row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_row']['total'] > 0):
    foreach ($_from AS $this->_var['row']):
        $this->_foreach['fe_row']['iteration']++;
?>
					<li <?php if ($this->_foreach['fe_row']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="javascript:void(0);" id="<?php echo $this->_var['row']['min']; ?> - <?php echo $this->_var['row']['max']; ?>"><span><?php echo price_format($this->_var['row']['min']); ?> - <?php echo price_format($this->_var['row']['max']); ?></span></a></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php if ($this->_var['price_count'] > 4): ?>
				<div class="options"> <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a> </div>
				<?php endif; ?> 
			</div>
			<?php endif; ?> 
			<?php if ($this->_var['regions'] && ! $this->_var['filters']['region_id']): ?>
			<div class="attrk">所在地区</div>
			<div class="attrv">
				<ul class="clearfix" ectype="ul_region">
					
					<?php $_from = $this->_var['regions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'row');$this->_foreach['fe_row'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_row']['total'] > 0):
    foreach ($_from AS $this->_var['row']):
        $this->_foreach['fe_row']['iteration']++;
?>
					<li <?php if ($this->_foreach['fe_row']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="javascript:void(0);" id="<?php echo $this->_var['row']['region_id']; ?>"><span><?php echo htmlspecialchars($this->_var['row']['region_name']); ?></span></a></li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				<?php if ($this->_var['region_count'] > 4): ?>
				<div class="options"> <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a> </div>
				<?php endif; ?> 
			</div>
			<?php endif; ?> 
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>