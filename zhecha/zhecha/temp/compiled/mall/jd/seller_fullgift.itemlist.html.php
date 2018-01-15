<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
    var t = new EditableTable($('#seller_fullgift'));
});
</script>
<div class="content">
	<div class="totline"></div>
	<div class="botline"></div>
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<div class="public_select table">
            	<?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
				<div class="notice-word"><p><?php echo $this->_var['appAvailable']['msg']; ?></p></div>
				<?php else: ?>
				<table id="seller_fullgift"  server="<?php echo $this->_var['site_url']; ?>/index.php?app=seller_fullgift&amp;act=ajax_col" >
					<tr class="line_bold">
						<th colspan="5"> <div class="select_div clearfix">
								<form id="seller_fullgift_form" method="get" class="float-left">
									<input type="hidden" name="app" value="seller_fullgift" />
									<input type="hidden" name="act" value="itemlist" />
									<input type="text" name="goods_name" value="<?php echo htmlspecialchars($_GET['goods_name']); ?>" class="width_normal" />
									<input type="submit" class="btn" value="搜索" />
                                    <?php if ($this->_var['filtered']): ?> 
									<a class="detlink" href="<?php echo url('app=seller_fullgift&act=itemlist'); ?>" style="display:inline-block">取消检索</a> 
									<?php endif; ?>
								</form>
							</div>
						</th>
					</tr>
					<tr class="sep-row" height="20">
						<td colspan="5"></td>
					</tr>
					<?php if ($this->_var['goods_list']): ?>
					<tr class="gray"  ectype="table_header">
						<th width="60"></th>
						<th width="210" coltype="editable" column="goods_name" checker="check_required" inputwidth="90%" title="排序" ><span>标题</span></th>
						<th width="100" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">价格</span></th>
						<th width="40" coltype="switchable" column="if_show" onclass="right_ico" offclass="wrong_ico" title="排序"  class="cursor_pointer"><span ectype="order_by">是否显示</span></th>
						<th width="70">操作</th>
					</tr>
					<tr class="sep-row">
						<td colspan="5"></td>
					</tr>
					<tr class="operations">
						<th colspan="5"> <p class="position1 clearfix">
								<input type="checkbox" id="all" class="checkall"/>
								<label for="all">全选</label>
								<a href="javascript:void(0);" class="delete" ectype="batchbutton" uri="index.php?app=seller_fullgift&act=itemdrop" name="id" presubmit="confirm('您确定要删除它吗？')">删除</a> </p>
							<p class="position2 clearfix"> <?php echo $this->fetch('member.page.top.html'); ?> </p>
						</th>
					</tr>
					<?php endif; ?> 
					<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['_goods_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_goods_f']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['_goods_f']['iteration']++;
?>
					<tr class="sep-row">
						<td colspan="5"></td>
					</tr>
					<tr class="line-hd">
						<th colspan="5" align="left"> <p>
								<input id="checkbox_<?php echo $this->_var['goods']['goods_id']; ?>" type="checkbox" class="checkitem" value="<?php echo $this->_var['goods']['goods_id']; ?>" align="absmiddle" />
								<label for="checkbox_<?php echo $this->_var['goods']['goods_id']; ?>">选中</label>
							</p>
						</th>
					</tr>
					<tr class="line line-blue<?php if (($this->_foreach['_goods_f']['iteration'] == $this->_foreach['_goods_f']['total'])): ?> last_line<?php endif; ?>" ectype="table_item" idvalue="<?php echo $this->_var['goods']['goods_id']; ?>">
						<td width="60" class="align2 first"><a href="<?php echo url('app=gift&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50" /></a></td>
						<td width="110" class="align1"><p class="ware_text" style="width:250px;"><span class="color2" ectype="editobj"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span></p></td>
						<td width="60" class="align2"><span class="color2" ectype="editobj"><?php echo $this->_var['goods']['price']; ?></span></td>
						<td width="40" class="align2"><span style="margin-left:15px;" ectype="editobj" <?php if ($this->_var['goods']['if_show']): ?>class="right_ico" status="on"<?php else: ?>class="wrong_ico" stauts="off"<?php endif; ?>></span></td>
						<td width="70" class="last"><a href="<?php echo url('app=seller_fullgift&act=itemedit&id=' . $this->_var['goods']['goods_id']. '&ret_page=' . $this->_var['page_info']['curr_page']. ''); ?>" class="edit">编辑</a> <a href="javascript:drop_confirm('您确定要删除它吗？', 'index.php?app=seller_fullgift&amp;act=itemdrop&id=<?php echo $this->_var['goods']['goods_id']; ?>');" class="delete">删除</a></td>
					</tr>
					<?php endforeach; else: ?>
					<tr>
						<td colspan="10"><div class="notice-word">
								<p><?php echo $this->_var['lang'][$_GET['act']]; ?>没有符合条件的记录</p>
							</div></td>
					</tr>
					<?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
					<?php if ($this->_var['goods_list']): ?>
					<tr class="sep-row">
						<td colspan="5"></td>
					</tr>
					<tr class="operations">
						<th colspan="5"> <p class="position1 clearfix">
								<input type="checkbox" id="all" class="checkall"/>
								<label for="all">全选</label>
								<a href="javascript:void(0);" class="delete" ectype="batchbutton" uri="index.php?app=seller_fullgift&act=itemdrop" name="id" presubmit="confirm('您确定要删除它吗？')">删除</a> </p>
							<p class="position2 clearfix"> <?php echo $this->fetch('member.page.bottom.html'); ?> </p>
						</th>
					</tr>
					<?php endif; ?>
				</table>
                <?php endif; ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>