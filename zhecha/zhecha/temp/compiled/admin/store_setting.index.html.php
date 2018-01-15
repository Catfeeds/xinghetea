<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>店铺功能设置</p>
    <ul class="subnav">
        <li><a class="btn1" href="index.php?app=store_setting&amp;act=base_setting">会员价设置</a></li>
        <li><a class="btn1" href="index.php?app=setting&amp;act=base_information">base_information</a></li>
        </ul>
</div>

<div class="info">
    <form method="post" enctype="multipart/form-data">
        <table class="infoTable">
            <tr>
                <th class="paddingT15">
                    store_allow:</th>
                <td class="paddingT15">
                    <span class="onoff">
                    <label class="cb-enable <?php if ($this->_var['setting']['store_allow']): ?>selected<?php endif; ?>">启用</label>
                    <label class="cb-disable <?php if (! $this->_var['setting']['store_allow']): ?>selected<?php endif; ?>">禁用</label>
                    <input name="store_allow" value="1" type="radio" <?php if ($this->_var['setting']['store_allow']): ?>checked<?php endif; ?>>
                    <input name="store_allow" value="0" type="radio" <?php if (! $this->_var['setting']['store_allow']): ?>checked<?php endif; ?>>
                  </span>
                  <span class="grey notice"></span>   
                </td>
            </tr>
            <tr>
            <th></th>
            <td class="ptb20">
                <input class="formbtn J_FormSubmit" type="submit" name="Submit" value="提交" />
                <input class="formbtn" type="reset" name="Submit2" value="重置" />
            </td>
        </tr>
        </table>
    </form>
</div>
<?php echo $this->fetch('footer.html'); ?>
