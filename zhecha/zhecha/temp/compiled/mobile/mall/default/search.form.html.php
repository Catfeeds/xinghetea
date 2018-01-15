<?php echo $this->fetch('header.html'); ?> 
<style type="text/css">
#header{display:none;}
</style>

<div class="pageSearchBox">
    <div class="true-search-box">
        <div class="top-bar">
            <h2>请输入你要搜索的商品名称</h2>
            <span class="close"></span>
        </div>
        <div class="search-form padding10">
            <div class="input-wraper">
               <form method="get">
                   <input type="hidden" name="app" value="search" />
                   <p class="input-child-wraper"><input class="text-input" name="keyword" placeholder="请输入你要搜索的商品名称"  /></p>
                   <input type="submit" class="true-submit" value="" />
               </form>
            </div>
        </div>
        <div class="hot-search padding10 clearfix">
            <?php $_from = $this->_var['keyword']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hot_search');if (count($_from)):
    foreach ($_from AS $this->_var['hot_search']):
?>
            <a href="<?php echo url('app=search&keyword=' . urlencode($this->_var['hot_search']). ''); ?>"><?php echo htmlspecialchars($this->_var['hot_search']); ?></a>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
</div>

<?php echo $this->fetch('footer.html'); ?> 