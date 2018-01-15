<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>账户</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=deposit">管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=tradelist">交易记录</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=drawlist">提现管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=rechargelist">充值管理</a></li>
    <li><span>系统设置</span></li>
  </ul>
</div>
<div class="info">
  <form method="post" id="account_form">
    <table class="infoTable">
      <tr>
        <th class="paddingT15"> 交易手续费:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="trade_rate" type="text" id="trade_rate"  value="<?php echo $this->_var['setting']['trade_rate']; ?>"/>
            <label class="field_notice">每笔交易扣除卖家的手续费，请填写小数，如：0.005，保留小数点后3位</label>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 转账手续费:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="transfer_rate" type="text" id="transfer_rate"  value="<?php echo $this->_var['setting']['transfer_rate']; ?>"/>
            <label class="field_notice">每笔转账收取的服务费，请填写小数，如：0.015，保留小数点后3位</label>
        </td>
      </tr>
	  <tr>
        <th class="paddingT15"> 自动创建预存款账户:</th>
        <td class="paddingT15">
          <span class="onoff">
            <label class="cb-enable <?php if ($this->_var['setting']['auto_create_account']): ?>selected<?php endif; ?>">是</label>
            <label class="cb-disable <?php if (! $this->_var['setting']['auto_create_account']): ?>selected<?php endif; ?>">否</label>
            <input name="auto_create_account" value="1" type="radio" <?php if ($this->_var['setting']['auto_create_account']): ?>checked<?php endif; ?>>
            <input name="auto_create_account" value="0" type="radio" <?php if (! $this->_var['setting']['auto_create_account']): ?>checked<?php endif; ?>>
          </span>
          <span class="grey notice">是否在注册用户时自动创建预存款账户, 预存款账户默认为注册邮箱，预存款支付密码默认为空</span>
        </td>
      </tr>	  
      <tr>
        <th></th>
        <td class="ptb20">
        	<input class="formbtn J_FormSubmit" type="submit" name="Submit" value="提交" />
            <input class="formbtn formbtn1" type="butten" name="back" onclick="javascript:window.history.go(-1);" value="返回" />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php echo $this->fetch('footer.html'); ?>