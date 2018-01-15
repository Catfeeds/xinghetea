<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    var t = new EditableTable($('#my_goods'));
});
</script>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right">
  		<?php echo $this->fetch('member.curlocal.html'); ?>
    	<?php echo $this->fetch('member.submenu.html'); ?>
        
        <div class="wrap">
            <div class="public_select table">
                <table id="my_goods"  server="<?php echo $this->_var['site_url']; ?>/index.php?app=my_goods&amp;act=ajax_col" >
                    <tr class="line_bold">
                        <th colspan="10">
                            <div class="select_div clearfix">
                            	<form id="my_goods_form" method="get" class="float-left">
                            		<input type="hidden" name="app" value="my_goods">
                            		<select class="select1" name='sgcate_id'>
                                		<option value="0">本店分类</option>
                                		<?php echo $this->html_options(array('options'=>$this->_var['sgcategories'],'selected'=>$_GET['sgcate_id'])); ?>
                            		</select>
                            		<select class="select2" name="character">
                                		<option value="0">状态</option>
                                		<?php echo $this->html_options(array('options'=>$this->_var['lang']['character_array'],'selected'=>$_GET['character'])); ?>
                            		</select>
                            		<input type="text" name="keyword" value="<?php echo htmlspecialchars($_GET['keyword']); ?>"/>
                            		<input type="submit" class="btn" value="搜索" />
                            	</form>
                            	<?php if ($this->_var['filtered']): ?>
                            	<a class="detlink" href="<?php echo url('app=my_goods'); ?>">取消检索</a>
                            	<?php endif; ?>
                            </div>
                        </th>
                    </tr>
                    <tr class="sep-row" height="20"><td colspan="<?php echo $this->_var['vip_count']; ?>"></td></tr>
                    <?php if ($this->_var['goods_list']): ?>
                    <tr class="gray"  ectype="table_header">
                        <th width="60"></th>
                        <th width="110" coltype="editable" column="goods_name" checker="check_required" inputwidth="90%" title="排序"  class="cursor_pointer"><span ectype="order_by">商品名称</span></th>
                        <th width="90" column="cate_id" title="排序"  class="cursor_pointer"><span ectype="order_by">商品分类</span></th>
                        <th width="50" coltype="editable" column="brand" checker="check_required" inputwidth="55px" title="排序"  class="cursor_pointer"><span ectype="order_by">品牌</span></th>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">价格</span></th>
                        
                        <?php if (! $this->_var['vip_price_status_off']): ?>
                        <?php if ($this->_var['price_1']): ?>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">会员价1</span></th>
                        <?php endif; ?>
						<?php if ($this->_var['price_2']): ?>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">会员价2</span></th>
                        <?php endif; ?>
						<?php if ($this->_var['price_3']): ?>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">会员价3</span></th>
                        <?php endif; ?>
						<?php if ($this->_var['price_4']): ?>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">会员价4</span></th>
                        <?php endif; ?>
						<?php if ($this->_var['price_5']): ?>
                        <th width="50" class="cursor_pointer" coltype="editable" column="price" checker="check_number" inputwidth="50px" title="排序"><span ectype="order_by">会员价5</span></th>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <th width="50" class="cursor_pointer" coltype="editable" column="stock" checker="check_pint" inputwidth="50px" title="排序"><span ectype="order_by">库存</span></th>
                        <th width="40" coltype="switchable" column="if_show" onclass="right_ico" offclass="wrong_ico" title="排序"  class="cursor_pointer"><span ectype="order_by">上架</span></th>
                        <th width="40" coltype="switchable" column="recommended" onclass="right_ico" offclass="wrong_ico" title="排序"  class="cursor_pointer"><span ectype="order_by">推荐</span></th>
                        <th width="40" column="closed" title="排序" class="cursor_pointer"><span ectype="order_by">禁售</span></th>
                        <?php if ($this->_var['allow_handle']): ?>
                        <th width="120">操作</th>
                        <?php endif; ?>
                    </tr>
                    <tr class="sep-row"><td colspan="<?php echo $this->_var['vip_count']; ?>"></td></tr>
                    <tr class="operations">
                        <th colspan="<?php echo $this->_var['vip_count']; ?>">
                        <?php if ($this->_var['allow_handle']): ?>
                            <p class="position1 clearfix">
                            	<input type="checkbox" id="all" class="checkall"/>
                        		<label for="all">全选</label>
                            	<a href="javascript:void(0);" class="edit" ectype="batchbutton" uri="index.php?app=my_goods&act=batch_edit" name="id">编辑</a>
                            </p>
                            <?php endif; ?>
                            <p class="position2 clearfix">
                                <?php echo $this->fetch('member.page.top.html'); ?>
                            </p>
                        </th>
                    </tr>
                    <?php endif; ?>
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['_goods_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_goods_f']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['_goods_f']['iteration']++;
?>
                    <tr class="sep-row"><td colspan="<?php echo $this->_var['vip_count']; ?>"></td></tr>
                    <tr class="line-hd">
                    	<th colspan="<?php echo $this->_var['vip_count']; ?>" align="left">
                        	<p><input id="checkbox_<?php echo $this->_var['goods']['goods_id']; ?>" type="checkbox" class="checkitem" value="<?php echo $this->_var['goods']['goods_id']; ?>" align="absmiddle" /><label for="checkbox_<?php echo $this->_var['goods']['goods_id']; ?>">商家编码</label><?php echo $this->_var['goods']['specs']['0']['sku']; ?></p>
                        </th>
                    </tr>
                    <tr class="line line-blue<?php if (($this->_foreach['_goods_f']['iteration'] == $this->_foreach['_goods_f']['total'])): ?> last_line<?php endif; ?>" ectype="table_item" idvalue="<?php echo $this->_var['goods']['goods_id']; ?>">
                        <td width="60" class="align2 first"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. '&store_id=' . $this->_var['store']['store_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50" /></a>
                        </td>
                        <td width="110" class="align1">
                          <p class="ware_text"><span class="color2" ectype="editobj"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span></p>
                        </td>
                        <td width="90" class="align2"><span class="color2"><?php echo nl2br($this->_var['goods']['cate_name']); ?></span></td>
                        <td width="60" class="align2"><span class="color2" ectype="editobj"><?php echo htmlspecialchars($this->_var['goods']['brand']); ?></span></td>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price']; ?></span></td>
                        
                        <?php if (! $this->_var['vip_price_status_off']): ?>
                        <?php if ($this->_var['price_1']): ?>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price_1']; ?></span></td>
                        <?php endif; ?>
						<?php if ($this->_var['price_2']): ?>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price_2']; ?></span></td>
                        <?php endif; ?>
						<?php if ($this->_var['price_3']): ?>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price_3']; ?></span></td>
                        <?php endif; ?>
						<?php if ($this->_var['price_4']): ?>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price_4']; ?></span></td>
                        <?php endif; ?>
						<?php if ($this->_var['price_5']): ?>
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['price_5']; ?></span></td>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <td width="60" class="align2"><?php if ($this->_var['goods']['spec_qty']): ?><span ectype="dialog" dialog_width="430" uri="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" dialog_title="编辑价格和库存" dialog_id="my_goods_spec_edit" class="cursor_pointer"><?php else: ?><span class="color2" ectype="editobj"><?php endif; ?><?php echo $this->_var['goods']['stock']; ?></span></td>
                        <td width="40" class="align2"><span style="margin-left:15px;" ectype="editobj" <?php if ($this->_var['goods']['if_show']): ?>class="right_ico" status="on"<?php else: ?>class="wrong_ico" stauts="off"<?php endif; ?>></span></td>
                        <td width="40" class="align2"><span style="margin-left:15px;" ectype="editobj" <?php if ($this->_var['goods']['recommended']): ?>class="right_ico" status="on"<?php else: ?>class="wrong_ico" stauts="off"<?php endif; ?>></span></td>
                        <td width="40" class="align2"><span style="margin-left:15px;" <?php if ($this->_var['goods']['closed']): ?>class="no_ico"<?php else: ?>class="no_ico_disable"<?php endif; ?>></span></td>
                        <?php if ($this->_var['allow_handle']): ?>
                        <td width="120" class="last">
                        	<a href="javascirpt:;" ectype="dialog" dialog_id="export_ubbcode" dialog_title="导出UBB" dialog_width="380" uri="<?php echo url('app=my_goods&act=export_ubbcode&id=' . $this->_var['goods']['goods_id']. ''); ?>" class="export">导出UBB</a>
                        	<a href="<?php echo url('app=my_goods&act=edit&id=' . $this->_var['goods']['goods_id']. '&ret_page=' . $this->_var['page_info']['curr_page']. ''); ?>" class="edit">编辑</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="<?php echo $this->_var['vip_count']; ?>"><div class="notice-word"><p><?php echo $this->_var['lang'][$_GET['act']]; ?>没有符合条件的商品</p></div></td>
                    </tr>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['goods_list']): ?>
                    <tr class="sep-row">
                        <td colspan="<?php echo $this->_var['vip_count']; ?>"></td>
                    </tr>
                    <tr class="operations">
                        <th colspan="<?php echo $this->_var['vip_count']; ?>">
                        <?php if ($this->_var['allow_handle']): ?>
                            <p class="position1 clearfix">
                            	<input type="checkbox" id="all" class="checkall"/>
                        		<label for="all">全选</label>
                            	<a href="javascript:void(0);" class="edit" ectype="batchbutton" uri="index.php?app=my_goods&act=batch_edit" name="id">编辑</a>
                            </p>
                            <?php endif; ?>
                            <p class="position2 clearfix">
                                <?php echo $this->fetch('member.page.bottom.html'); ?>
                            </p>
                        </th>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>

<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>