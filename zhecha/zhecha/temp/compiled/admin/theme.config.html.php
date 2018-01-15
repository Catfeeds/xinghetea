<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>主题编辑</p>
  <ul class="subnav">
    <li><a class="btn1" href="<?php echo url('app=theme'); ?>">电脑版主题</a></li>
    <li><a class="btn1" href="<?php echo url('app=theme&type=mobile'); ?>">触屏版主题</a></li>
    <li><span>主题编辑</span></li>
  </ul>
</div>
<ul id="rightCon">
    <div class="model-theme">
        <?php $_from = $this->_var['form_data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'form');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['form']):
?>
        <a class="box" href="index.php?app=theme&amp;act=edit&model=<?php echo $this->_var['key']; ?>&style=<?php echo $_GET['style']; ?>&template=<?php echo $_GET['template']; ?>&type=<?php echo $_GET['type']; ?>">
            <div class="wrap-box">
                <h3>
                    <?php echo $this->_var['form']['text']; ?>
                </h3>
                <p>
                    <?php echo $this->_var['form']['desc']; ?>
                </p>
            </div>
        </a>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <div class="clear"></div>
    </div>
</ul>
<?php echo $this->fetch('footer.html'); ?>
