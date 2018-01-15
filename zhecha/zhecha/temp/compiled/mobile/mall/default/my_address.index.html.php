<?php echo $this->fetch('member.header.html'); ?>
<div id="page-my-address" class="mb10">
	<div class="page-body my-address pb20">
		<div class="add-address clearfix mt10 mr5">
			<a href="<?php echo url('app=my_address&act=add&ret_url=' . urlencode($_GET['ret_url']). ''); ?>" class="float-right btn">新增地址</a>
		</div>
		<?php $_from = $this->_var['addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'address');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['address']):
        $this->_foreach['v']['iteration']++;
?>
		<dl style="margin-bottom:15px;" class="inclusive-box f66">
			<dd><label>收货人姓名 :</label><span>  <?php echo htmlspecialchars($this->_var['address']['consignee']); ?></span></dd>
			<dd><label>所在地区 :</label><span> <?php echo htmlspecialchars($this->_var['address']['region_name']); ?></span></dd>
			<dd><label>详细地址 :</label><span>  <?php echo htmlspecialchars($this->_var['address']['address']); ?></span></dd>
			<dd><label>邮政编码 :</label><span> <?php echo htmlspecialchars($this->_var['address']['zipcode']); ?></span></dd>
            <dd><label>电话/手机 :</label><span> <?php echo $this->_var['address']['phone_tel']; ?> / <?php echo $this->_var['address']['phone_mob']; ?></span></dd>
		</dl> 
		<div class="btn-box">
		   <a href="<?php echo url('app=my_address&act=edit&addr_id=' . $this->_var['address']['addr_id']. ''); ?>">编辑</a>
           <a href="javascript:;" confirm="您确定要删除它吗？" action="<?php echo url('app=my_address&act=drop&addr_id=' . $this->_var['address']['addr_id']. ''); ?>" class="J_AjaxRequest" >删除</a>
		</div>   
		<?php endforeach; else: ?>
        <div class="no-record">您没有添加收货地址</div>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</div>
    <?php if ($this->_var['addresses']): ?>
    <?php echo $this->fetch('page.bottom.html'); ?>
    <?php endif; ?>
</div>  
<?php echo $this->fetch('footer.html'); ?>
