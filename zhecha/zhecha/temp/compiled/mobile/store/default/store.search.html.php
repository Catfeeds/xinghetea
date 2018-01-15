<?php echo $this->fetch('top.html'); ?>
<div id="page-store" class="page-store page J_page" style="margin-bottom:10px; "> 
	
	<div class="bar-wrap">
		<div class="top-bar">
        	<a href="javascript:pageBack();" class="pageback"><span></span></a>
		    <div class="search-box">
				<form id="search-form" method="get">
					<input type="hidden" name="app" value="store" />
                    <input type="hidden" name="act" value="search" />
					<input type="hidden" name="id" value="<?php echo $this->_var['store']['store_id']; ?>" />
					<input name="keyword"  type="text" class="kw" placeholder="请输入你要搜索的商品名称" value="<?php echo $_GET['keyword']; ?>" />
					<input type="submit" class="submit" value="">
				</form>
			</div>
			<a  class="page-store-more" onclick="PsmobanShowMenu();"><span></span></a> 
        </div>
	</div>
	 
	<?php if ($this->_var['searched_goods']): ?>
	<div style="border-top:0px;" class="search-goods goods-model-si">
		<h3><?php echo $this->_var['search_name']; ?></h3>
		<ul class="clearfix">
			<?php $_from = $this->_var['searched_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ng');$this->_foreach['fe_ng'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_ng']['total'] > 0):
    foreach ($_from AS $this->_var['ng']):
        $this->_foreach['fe_ng']['iteration']++;
?>
			<li> 
				<?php $_from = $this->_var['ng']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ngoods');$this->_foreach['fe_ngoods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_ngoods']['total'] > 0):
    foreach ($_from AS $this->_var['ngoods']):
        $this->_foreach['fe_ngoods']['iteration']++;
?>
				<div class="item pb10">
					<div <?php if ($this->_foreach['fe_ngoods']['iteration'] % 2 == 0): ?>style="margin-left:5px;margin-right:0rem;"<?php endif; ?> class="wrap">
						<div class="wrap2">
							<div class="pic"><a href="<?php echo url('app=goods&id=' . $this->_var['ngoods']['goods_id']. ''); ?>"><img src="<?php echo $this->_var['ngoods']['default_image']; ?>" /></a></div>
							<h3><a href="<?php echo url('app=goods&id=' . $this->_var['ngoods']['goods_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['ngoods']['goods_name']); ?></a></h3>
							<p>￥<?php echo $this->_var['ngoods']['price']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<div class="line-background2"></div>
	<?php echo $this->fetch('page.bottom.html'); ?>
	<div class="line-background2"></div>
	<?php else: ?>
	<div style="text-align:center;line-height:100px;font-size:14px;" class="no-goods page-body"> 很抱歉! 没有找到相关商品 </div>
	<?php endif; ?> 
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