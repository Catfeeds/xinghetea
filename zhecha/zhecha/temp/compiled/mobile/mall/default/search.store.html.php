<?php echo $this->fetch('top.html'); ?>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/search_store.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	var order = '<?php echo $_GET['order']; ?>';
	var	order_arr = order.split(' ');
	$('#'+order_arr[0]).addClass('current');
	var type = order_arr[0];
	var char;
	switch(type)
	{
		case 'praise_rate':
		char = '好评率';
		break;
		case 'credit_value':
		char = '信誉度';
		break;
		case 'add_time':
		char = '添加时间';
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
<div id="page-search-store" class="page J_page">
	
    <div class="bar-wrap">
		<div class="top-bar"> 
			<a href="javascript:pageBack();" class="pageback"><span></span></a>
			<div class="search-box">
				<form id="search-form" method="get">
					<input type="hidden" name="app" value="search"/>
                	<input type="hidden" name="act" value="store"/>
					<input name="keyword"  type="text" class="kw" placeholder="请输入你要搜索的店铺名称" value="<?php echo $_GET['keyword']; ?>"/>
					<input type="submit" class="submit" value="">
				</form>
			</div>
			<a style="top:0px;" href="javascript:;" onclick="PsmobanShowMenu();" class="filter">筛选</a> 
		</div>
		
	</div>
    	
	<div class="search-store">
    	<div class="attr">
				<div class="cons">
					<ul class="display-order clearfix">
						<li <?php if ($_GET['order'] == ''): ?>class="current"<?php endif; ?>><span>综合</span></li>
						<li id="credit_value"><span>信用度</span></li>
						<li id="add_time"><span>上架</span></li>
						<li id="praise_rate"><span>好评率</span></li>
					</ul>
				</div>
			</div>
        <ul class="shop-list ml10 mr10">
        	<?php $_from = $this->_var['stores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
            <li class="mt10">
            	<dl>
                    <dt><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><img src="<?php echo $this->_var['store']['store_logo']; ?>" width="80" height="80" /></a></dt>
                    <dd>
                        <h3><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['store']['store_name']); ?></a></h3>
                        <p><?php echo htmlspecialchars($this->_var['store']['user_name']); ?></p>
                        <p><?php echo htmlspecialchars($this->_var['store']['region_name']); ?></p>
                        <p>
                            上架<?php echo $this->_var['store']['goods_count']; ?>件商品
                            <i> 
                                <?php if ($this->_var['store']['credit_value'] > 0): ?>
                                <img src="<?php echo $this->_var['store']['credit_image']; ?>" />
                                <?php endif; ?>
                            </i>
                        </p>
                    </dd>
                </dl>
            </li>
            <?php endforeach; else: ?>
            <div class="no-data mt20 center">搜索无结果，请重新搜搜</div>
            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>    
    </div>
    <?php echo $this->fetch('page.bottom.html'); ?>
</div>
<div class="masker J_masker" onclick="PsmobanShowMenu();"></div>
<div class="menus J_menus">
  <div class="attrs" style="overflow: auto;height: 100%">
    <div class="attr"> 
     <div class="attrk">所在地</div>
      <div class="attrv">
        <ul class="clearfix"  ectype="ul_cate">
          <?php $_from = $this->_var['regions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('region_id', 'region_name');$this->_foreach['fe_region'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_region']['total'] > 0):
    foreach ($_from AS $this->_var['region_id'] => $this->_var['region_name']):
        $this->_foreach['fe_region']['iteration']++;
?>
          <li <?php if ($this->_foreach['fe_region']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="<?php echo url('app=search&act=store&region_id=' . $this->_var['region_id']. ''); ?>"><span><?php echo htmlspecialchars($this->_var['region_name']); ?></span></a></li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php if ($this->_var['category_count'] > 4): ?>
        <div class="options">
          <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a>
        </div>
        <?php endif; ?>
      </div>
      <?php $_from = $this->_var['scategorys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'scategory');if (count($_from)):
    foreach ($_from AS $this->_var['scategory']):
?>
      <div class="attrk"><a href="<?php echo url('app=search&act=store&cate_id=' . $this->_var['scategory']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['scategory']['value']); ?></a></div>
      <div class="attrv">
        <ul class="clearfix"  ectype="ul_cate">
          <?php $_from = $this->_var['scategory']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');$this->_foreach['fe_child'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_child']['total'] > 0):
    foreach ($_from AS $this->_var['child']):
        $this->_foreach['fe_child']['iteration']++;
?>
          <li <?php if ($this->_foreach['fe_child']['iteration'] > 4): ?>class="hidden"<?php endif; ?>><a href="<?php echo url('app=search&act=store&cate_id=' . $this->_var['child']['id']. ''); ?>"><span><?php echo htmlspecialchars($this->_var['child']['value']); ?></span></a></li>
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php if ($this->_var['scategory']['count'] > 4): ?>
        <div class="options">
          <a class="more" href="javascript:void(0);"><span>查看更多</span><i></i></a>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </div>
</div>
</div>
<?php echo $this->fetch('footer.html'); ?>