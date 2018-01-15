<div class="commnent-detail mb10">
	<ul>
    	<?php $_from = $this->_var['goods_comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');if (count($_from)):
    foreach ($_from AS $this->_var['comment']):
?>
		<li>
        	<p>
            <?php if ($this->_var['comment']['evaluation'] > 0): ?><img src="<?php echo $this->res_base . "/" . 'images/bit.gif'; ?>" /><?php endif; ?>
            <?php if ($this->_var['comment']['evaluation'] > 1): ?><img src="<?php echo $this->res_base . "/" . 'images/bit.gif'; ?>" /><?php endif; ?>
            <?php if ($this->_var['comment']['evaluation'] > 2): ?><img src="<?php echo $this->res_base . "/" . 'images/bit.gif'; ?>" /><?php endif; ?>
            <?php if ($this->_var['comment']['evaluation'] < 3): ?><img src="<?php echo $this->res_base . "/" . 'images/bit2.gif'; ?>" /><?php endif; ?>
            <?php if ($this->_var['comment']['evaluation'] < 2): ?><img src="<?php echo $this->res_base . "/" . 'images/bit2.gif'; ?>" /><?php endif; ?>
            <?php if ($this->_var['comment']['evaluation'] < 1): ?><img src="<?php echo $this->res_base . "/" . 'images/bit2.gif'; ?>" /><?php endif; ?>
            </p>
        	<p><?php echo nl2br(htmlspecialchars($this->_var['comment']['comment'])); ?></p>
            <div>
            	<span><?php echo local_date("Y-m-d H:i:s",$this->_var['comment']['evaluation_time']); ?></span>
            	<em><?php if ($this->_var['comment']['anonymous']): ?>***<?php else: ?><?php echo htmlspecialchars($this->_var['comment']['buyer_name']); ?><?php endif; ?></em>
            </div>
        </li>
        <?php endforeach; else: ?>
        <li style="border:0px;">
            没有符合条件的记录
        </li>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>  
	</ul>
    <?php echo $this->fetch('page.bottom.html'); ?>
</div>


