<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>账户</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=deposit">管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=tradelist">交易记录</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=rechargelist">充值管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=setting">系统设置</a></li>
    <li><span>充值</span></li>
  </ul>
</div>
<div class="info">
  <form method="post" id="account_form">
    <table class="infoTable">
      <tr>
        <th class="paddingT15"> 账户名:</th>
        <td class="paddingT15 wordSpacing5"><?php echo $this->_var['account']['real_name']; ?> ( <?php echo $this->_var['account']['account']; ?> 余额:<?php echo $this->_var['account']['money']; ?> )</td>
      </tr>
      <tr>
        <th class="paddingT15"> 账户余额:</th>
        <td class="paddingT15 wordSpacing5">
        	<select name="money_change">
            	<option value="increase">增加</option>
                <option value="reduce">减少</option>
            </select>
            <input class="infoTableInput2" name="money" type="text" id="money" /> 元
          	<label class="field_notice">增加表示充值，减少表示扣费</label>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 备注:</th>
        <td class="paddingT15 wordSpacing5">
        	<textarea name="remark"></textarea>
        </td>
      </tr>
      <tr>
        <th></th>
        <td class="ptb20">
        	<input class="formbtn J_FormSubmit" type="submit" name="Submit" value="提交" />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php echo $this->fetch('footer.html'); ?>