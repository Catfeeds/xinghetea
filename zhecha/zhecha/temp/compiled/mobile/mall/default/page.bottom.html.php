<?php if ($this->_var['page_info']['page_count'] > 1): ?>
<div class="page-format">
	<div class="wrap">
		<a href="javascript:;" class="page-num"><?php echo $this->_var['page_info']['curr_page']; ?> / <?php echo $this->_var['page_info']['page_count']; ?><i></i></a>
		<?php if ($this->_var['page_info']['prev_link']): ?>
        <a class="former" href="<?php echo $this->_var['real_site_url']; ?>/<?php echo $this->_var['page_info']['prev_link']; ?>">上一页</a>
        <?php else: ?>
        <span class="former">上一页</span>
        <?php endif; ?>
		<?php if ($this->_var['page_info']['next_link']): ?>
        <a class="down" href="<?php echo $this->_var['real_site_url']; ?>/<?php echo $this->_var['page_info']['next_link']; ?>">下一页</a>
        <?php else: ?>
        <span class="down">下一页</span>
        <?php endif; ?>
	</div>
</div>
<?php endif; ?>