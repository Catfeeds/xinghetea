<?php echo $this->fetch('member.header.html'); ?>
<div id="page-message">
	<div class="tabs">
    	<ul class="tab-list clearfix">
        	<li <?php if ($_GET['act'] == 'newpm'): ?>class="active"<?php endif; ?>><a href="<?php echo url('app=message&act=newpm'); ?>">未读信息</a></li>
        	<li <?php if ($_GET['act'] == 'privatepm'): ?>class="active"<?php endif; ?>><a href="<?php echo url('app=message&act=privatepm'); ?>">私人信息</a></li>
            <li <?php if ($_GET['act'] == 'systempm'): ?>class="active"<?php endif; ?>><a href="<?php echo url('app=message&act=systempm'); ?>">系统信息</a></li>
        </ul>
    </div>
	<div class="pm-list">
    	<?php if ($this->_var['messages']): ?>
        <ul>
    		<?php $_from = $this->_var['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'message');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['message']):
        $this->_foreach['v']['iteration']++;
?>
    		<li>
            	<div class="send-user-logo">
                	<img width="50" height="50"  src="<?php echo $this->_var['message']['user_info']['portrait']; ?>" />
                </div>
            	<div class="detail" <?php if (($this->_foreach['v']['iteration'] == $this->_foreach['v']['total'])): ?>style="border:0px;"<?php endif; ?>>
                	<a href="<?php echo url('app=message&act=view&msg_id=' . $this->_var['message']['msg_id']. ''); ?>">
                        <p class="t"><span><?php echo $this->_var['message']['user_info']['user_name']; ?></span><em><?php echo local_date("Y-m-d H:i",$this->_var['message']['last_update']); ?></em></p>
                        <p class="d"><?php if ($this->_var['message']['new']): ?><span style="color:#FF2700;">[未读]</span><?php endif; ?><?php echo $this->_var['message']['content']; ?></p>
                    </a>
                </div>
            </li>
    		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    	</ul>
        <?php endif; ?>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?> 