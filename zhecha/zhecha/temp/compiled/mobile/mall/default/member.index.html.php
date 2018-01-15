<?php echo $this->fetch('member.header.html'); ?>
<style type="text/css">
.bar-wrap{display:none;}
</style>
<div id="page-member">
	<div class="main">
    	<div class="relative top-info">
            <h3 class="member-banner"></h3>
            <div class="right-top-po clearfix">
            	<a class="set-btn" href="<?php echo url('app=member&act=setting'); ?>">设置</a>
                <a href="<?php echo url('app=message&act=newpm'); ?>" class="new-message"><ins><?php echo $this->_var['new_message']; ?></ins></a>
            </div>
            <div class="user-photo"><img src="<?php echo $this->_var['user']['portrait']; ?>" width="70" height="70"/></div>
            <div class="user-name"><em> <?php echo htmlspecialchars($this->_var['user']['user_name']); ?> </em> </div>
        </div>
        <div class="top-menu mb10">
       		<ul class="clearfix">
            	<li>
                
                </li>
            	<li>
                   <a href="<?php echo url('app=my_favorite&type=store'); ?>">
                       <span><?php echo $this->_var['user']['count_collect_store']; ?></span><br />
                       <span>收藏的店铺</span>
                   </a>
                </li>
                <li>
                   <a href="<?php echo url('app=my_favorite'); ?>">
                      <span><?php echo $this->_var['user']['count_collect_goods']; ?></span><br />
                      <span>收藏的宝贝</span>
                   </a>
                </li>
                <li>
                   <a href="<?php echo url('app=my_integral'); ?>">
                      <span><?php echo ($this->_var['user']['integral'] == '') ? '0' : $this->_var['user']['integral']; ?></span><br />
                      <span>我的积分</span>
                   </a>
               </li>
            </ul>
        </div>
        <div class="fun-list">
        	<?php if ($this->_var['member_role'] == 'buyer_admin'): ?>
        	<ul class="clearfix">
            	<li class="order-status-1">
                	<a href="<?php echo url('app=buyer_order&type=pending'); ?>">
                    	<?php if ($this->_var['buyer_stat']['pending']): ?><ins><?php echo $this->_var['buyer_stat']['pending']; ?></ins><?php endif; ?>
                    	<i>&#xe673;</i>
                        <span>待付款</span>
                    </a>
                </li>
                <li class="order-status-2">
                	<a href="<?php echo url('app=buyer_order&type=accepted'); ?>">
                    	<?php if ($this->_var['buyer_stat']['accepted']): ?><ins><?php echo $this->_var['buyer_stat']['accepted']; ?></ins><?php endif; ?>
                    	<i>&#xe675;</i>
                        <span>待发货</span>
                    </a>
                </li>
                <li class="order-status-3">
                	<a href="<?php echo url('app=buyer_order&type=shipped'); ?>">
                    	<?php if ($this->_var['buyer_stat']['shipped']): ?><ins><?php echo $this->_var['buyer_stat']['shipped']; ?></ins><?php endif; ?>
                    	<i>&#xe671;</i>
                        <span>待收货</span>
                    </a>
                </li>
                <li class="order-status-4">
                	<a href="<?php echo url('app=buyer_order&type=finished'); ?>">
                    	<?php if ($this->_var['buyer_stat']['finished']): ?><ins><?php echo $this->_var['buyer_stat']['finished']; ?></ins><?php endif; ?>
                    	<i>&#xe672;</i>
                        <span>待评价</span>
                    </a>
                </li>
                <li class="order-status-5">
                	<a href="<?php echo url('app=refund&status=on'); ?>">
                    	<?php if ($this->_var['buyer_stat']['refund']): ?><ins><?php echo $this->_var['buyer_stat']['refund']; ?></ins><?php endif; ?>
                    	<i>&#xe6ac;</i>
                        <span>退款</span>
                    </a>
                </li>
            </ul>
            <?php endif; ?>
            <?php if ($this->_var['member_role'] == 'seller_admin'): ?>
        	<ul class="clearfix">
            	<li class="order-status-1">
                	<a href="<?php echo url('app=seller_order&type=pending'); ?>">
                    	<?php if ($this->_var['seller_stat']['pending']): ?><ins><?php echo $this->_var['seller_stat']['pending']; ?></ins><?php endif; ?>
                    	<i>&#xe673;</i>
                        <span>待付款</span>
                    </a>
                </li>
                <li class="order-status-2">
                	<a href="<?php echo url('app=seller_order&type=accepted'); ?>">
                    	<?php if ($this->_var['seller_stat']['accepted']): ?><ins><?php echo $this->_var['seller_stat']['accepted']; ?></ins><?php endif; ?>
                    	<i>&#xe675;</i>
                        <span>待发货</span>
                    </a>
                </li>
                <li class="order-status-3">
                	<a href="<?php echo url('app=seller_order&type=shipped'); ?>">
                    	<?php if ($this->_var['seller_stat']['shipped']): ?><ins><?php echo $this->_var['seller_stat']['shipped']; ?></ins><?php endif; ?>
                    	<i>&#xe671;</i>
                        <span>待收货</span>
                    </a>
                </li>
                <li class="order-status-4">
                	<a href="<?php echo url('app=seller_order&type=finished'); ?>">
                    	<?php if ($this->_var['seller_stat']['finished']): ?><ins><?php echo $this->_var['seller_stat']['finished']; ?></ins><?php endif; ?>
                    	<i>&#xe672;</i>
                        <span>待评价</span>
                    </a>
                </li>
                <li class="order-status-5">
                	<a href="<?php echo url('app=refund&act=receive&status=on'); ?>">
                    	<?php if ($this->_var['seller_stat']['refund']): ?><ins><?php echo $this->_var['seller_stat']['refund']; ?></ins><?php endif; ?>
                    	<i>&#xe6ac;</i>
                        <span>退款</span>
                    </a>
                </li>
            </ul>
            <?php endif; ?>
            <?php $_from = $this->_var['_member_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?>
            <?php $_from = $this->_var['item']['submenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'subitem');if (count($_from)):
    foreach ($_from AS $this->_var['subitem']):
?>
            <div class="fun-row <?php echo $this->_var['subitem']['margin']; ?>">
            	<a href="<?php echo $this->_var['subitem']['url']; ?>" class="clearfix block">
                    <p class="float-left title <?php echo $this->_var['subitem']['name']; ?>"><i></i><?php echo $this->_var['subitem']['text']; ?></p>
                    <p class="float-right view mr10"><?php echo $this->_var['subitem']['sub_text']; ?></p>  
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php if ($this->_var['_member_menu']['overview']): ?>
            <div class="fun-row <?php echo $this->_var['subitem']['margin']; ?>">
            	<a href="<?php echo $this->_var['_member_menu']['overview']['url']; ?>" class="clearfix block">
                    <p class="float-left title <?php echo $this->_var['_member_menu']['overview']['name']; ?>"><i></i><?php echo $this->_var['_member_menu']['overview']['text']; ?></p>
                    <p class="float-right view mr10"></p>  
                </a>
            </div>
            <?php endif; ?>
            <?php if ($this->_var['member_role'] == 'seller_admin'): ?>
            <div class="fun-row <?php echo $this->_var['subitem']['margin']; ?>">
            	<a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>" class="clearfix block">
                    <p class="float-left title im_seller"><i></i>我的店铺</p>
                    <p class="float-right view mr10">到我的店铺看看</p>  
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?>