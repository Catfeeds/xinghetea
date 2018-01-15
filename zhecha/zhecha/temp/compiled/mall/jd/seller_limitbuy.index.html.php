<?php echo $this->fetch('member.header.html'); ?>
<div class="content"> <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
    <?php echo $this->fetch('member.submenu.html'); ?>
    <div class="wrap">
      <div class="public_select table">
        <div class="promotool">
          <div class="bundle bundle-list"> 
            <?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
            <div class="notice-word">
              <p><?php echo $this->_var['appAvailable']['msg']; ?></p>
            </div>
            <?php else: ?>
            <div class="notice-word">
              <p class="yellow-big">温馨提示: 可设置某个商品某个时间段的折扣或减价价格。</p>
            </div>
            <table>
              <tr class="line_bold">
                <th colspan="9"> <div class="select_div" >
                    <form method="get" class="clearfix">
                      <div class="float-left">
                        <input type="hidden" name="app" value="seller_limitbuy" />
                        <input type="hidden" name="act" value="index" />
                        <input type="text" name="pro_name" value="<?php echo htmlspecialchars($_GET['pro_name']); ?>"/>
                        <input type="submit" class="btn" value="搜索" />
                      </div>
                      <?php if ($this->_var['filtered']): ?> 
                      <a class="detlink" href="<?php echo url('app=seller_limitbuy'); ?>">取消检索</a> 
                      <?php endif; ?>
                      <div class="clear"></div>
                    </form>
                  </div>
                </th>
              </tr>
              <?php if ($this->_var['limitbuy_list']): ?>
              <tr class="gray">
                <th width="50">商品图片</th>
                <th width="140">商品名称</th>
                <th width="60"><span>促销名称</span></th>
                <th width="100"><span>开始时间</span></th>
                <th width="100"><span>结束时间</span></th>
                <th width="80"><span>原价</span></th>
                <th width="80"><span>促销价</span></th>
                <th ><span>状态</span></th>
                <th width="100"><span>操作</span></th>
              </tr>
              <?php endif; ?> 
              <?php $_from = $this->_var['limitbuy_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'limitbuy');$this->_foreach['_limitbuy_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_limitbuy_f']['total'] > 0):
    foreach ($_from AS $this->_var['limitbuy']):
        $this->_foreach['_limitbuy_f']['iteration']++;
?>
              <tr class="line<?php if (($this->_foreach['_limitbuy_f']['iteration'] == $this->_foreach['_limitbuy_f']['total'])): ?> last_line<?php endif; ?>">
                <td width="50" class="align2"><a href="<?php echo url('app=goods&id=' . $this->_var['limitbuy']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['limitbuy']['default_image']; ?>" width="50" height="50"  /></a></td>
                <td width="140"><a href="<?php echo url('app=goods&id=' . $this->_var['limitbuy']['goods_id']. ''); ?>" target="_blank"><?php echo sub_str(htmlspecialchars($this->_var['limitbuy']['goods_name']),30); ?></a></td>
                <td width="60" align="align2"><a target="_blank" href="<?php echo url('app=goods&id=' . $this->_var['limitbuy']['goods_id']. ''); ?>"><?php echo htmlspecialchars($this->_var['limitbuy']['pro_name']); ?></a></td>
                <td width="100" class="align2"><?php echo local_date("Y-m-d H:i:s",$this->_var['limitbuy']['start_time']); ?></td>
                <td width="100" class="align2"><?php echo local_date("Y-m-d H:i:s",$this->_var['limitbuy']['end_time']); ?></td>
                <td width="80" class="align2"><?php echo $this->_var['limitbuy']['price']; ?></td>
                <td width="80" class="align2"><?php echo $this->_var['limitbuy']['pro_price']; ?></td>
                <td width="80" class="align2"><?php echo $this->_var['limitbuy']['status']; ?></td>
                <td width="100" class="align2 last"><div><a href="<?php echo url('app=seller_limitbuy&act=edit&id=' . $this->_var['limitbuy']['pro_id']. ''); ?>">编辑</a> | <a href="<?php echo url('app=seller_limitbuy&act=drop&id=' . $this->_var['limitbuy']['pro_id']. ''); ?>">删除</a> </div></td>
              </tr>
              <?php endforeach; else: ?>
              <tr>
                <td class="align2" colspan="9"><div class="notice-word">
                    <p>没有符合条件的记录</p>
                  </div></td>
              </tr>
              <?php endif; unset($_from); ?><?php $this->pop_vars();; ?> 
              <?php if ($this->_var['limitbuy_list']): ?>
              <tr class="line_bold line_bold_bottom">
                <td colspan="9"></td>
              </tr>
              <tr>
                <th colspan="9"> <p class="position2"> <?php echo $this->fetch('member.page.bottom.html'); ?> </p>
                </th>
              </tr>
              <?php endif; ?>
            </table>
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