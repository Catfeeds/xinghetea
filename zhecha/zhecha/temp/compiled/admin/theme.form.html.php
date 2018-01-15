<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
<p>
	<b><?php echo $this->_var['form_data']['text']; ?></b>
    <a href="javascript:window.history.back();" class="btn1" style="margin-left:10px;display:inline-block;">返回上一级</a>
</p>

</div>
<ul id="rightCon">
<div class="eject_con">
    <div class="info_table_wrap">
        <form method="post" action="index.php?app=theme&amp;act=edit&amp;type=<?php echo $_GET['type']; ?>&template=<?php echo $_GET['template']; ?>&style=<?php echo $_GET['style']; ?>&model=<?php echo $_GET['model']; ?>"  enctype="multipart/form-data">
        <ul class="info_table edit-theme">
        	<?php $_from = $this->_var['form_data']['config']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('conf', 'info');if (count($_from)):
    foreach ($_from AS $this->_var['conf'] => $this->_var['info']):
?>
            <?php if ($this->_var['info']['type'] == 'file'): ?>
            <li>
            	<div class="img">
                    <?php if ($this->_var['info']['text'] || $this->_var['info']['desc']): ?>
					<h3>
						<?php echo $this->_var['info']['text']; ?><span><?php echo $this->_var['info']['desc']; ?></span>
					</h3>
                    <?php endif; ?>
					<input type="file" name="file[<?php echo $this->_var['conf']; ?>]" id="ctrl_<?php echo $this->_var['conf']; ?>" size="<?php echo $this->_var['info']['size']; ?>" onfocus="<?php echo $this->_var['info']['onfocus']; ?>" onchange="<?php echo $this->_var['info']['onchange']; ?>" onblur="<?php echo $this->_var['info']['onblur']; ?>" />
					<span>
						<img  style="border:1px #f1f1f1 solid" width="<?php echo $this->_var['info']['width']; ?>" src="<?php if ($this->_var['info']['value']): ?><?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['info']['value']; ?><?php endif; ?>" />
					</span>
				</div>
            </li>
            <?php endif; ?>
            <li class="text-model">
            	<?php if ($this->_var['info']['type'] == 'text'): ?>
				<h3>
					<?php echo $this->_var['info']['text']; ?><span><?php echo $this->_var['info']['desc']; ?></span>
				</h3>
				<input type="text" name="config[<?php echo $this->_var['conf']; ?>]" id="ctrl_<?php echo $this->_var['conf']; ?>" value="<?php echo $this->_var['info']['value']; ?>" size="<?php echo $this->_var['info']['size']; ?>" onfocus="<?php echo $this->_var['info']['onfocus']; ?>" onchange="<?php echo $this->_var['info']['onchange']; ?>" onblur="<?php echo $this->_var['info']['onblur']; ?>" class="text width9" />
				<?php elseif ($this->_var['info']['type'] == 'select'): ?>
                <h3>
					<?php echo $this->_var['info']['text']; ?><span><?php echo $this->_var['info']['desc']; ?></span>
				</h3>
				<select name="config[<?php echo $this->_var['conf']; ?>]" id="ctrl_<?php echo $this->_var['conf']; ?>" onchange="<?php echo $this->_var['info']['onchange']; ?>" class="width8 padding4"><?php echo $this->html_options(array('options'=>$this->_var['info']['items'],'selected'=>$this->_var['info']['value'])); ?></select>
			   <?php elseif ($this->_var['info']['type'] == 'textarea'): ?>
               <h3>
					<?php echo $this->_var['info']['text']; ?>
				</h3>
			   <textarea cols="<?php echo $this->_var['info']['cols']; ?>" rows="<?php echo $this->_var['info']['rows']; ?>" name="config[<?php echo $this->_var['conf']; ?>]" id="ctrl_<?php echo $this->_var['conf']; ?>" onfocus="<?php echo $this->_var['info']['onfocus']; ?>" onchange="<?php echo $this->_var['info']['onchange']; ?>" onblur="<?php echo $this->_var['info']['onblur']; ?>" style="width:532px;height:100px;" class="text"><?php echo $this->_var['info']['value']; ?></textarea><span style="color:#999">(<?php echo $this->_var['info']['desc']; ?>)</span>
			   <?php elseif ($this->_var['info']['type'] == 'radio'): ?>
			   <?php echo $this->html_radios(array('options'=>$this->_var['info']['items'],'checked'=>$this->_var['info']['value'],'name'=>$this->_var['info']['name'])); ?>
			   <?php elseif ($this->_var['info']['type'] == 'checkbox'): ?> 
			   <?php echo $this->html_checkbox(array('options'=>$this->_var['info']['items'],'checked'=>$this->_var['info']['value'],'name'=>$this->_var['info']['name'])); ?>
			   <?php endif; ?>
               <div class="clear"></div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <div class="submit" style="padding:20px 0px;"><input type="submit" class="formbtn J_FormSubmit" value="提交" /><div class="clear"></div></div>
        </form>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?>