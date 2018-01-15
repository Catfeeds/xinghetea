<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#account_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
        onkeyup    : false,
        rules : {
            account : {
                required : true,
				email : true,
                byteRange: [6,40,'<?php echo $this->_var['charset']; ?>'],
                remote   : {
                    url :'index.php?app=deposit&act=check_account',
                    type:'get',
                    data:{
                        account : function(){
                            return $('#account').val();
                        },
                        id : '<?php echo $this->_var['account']['account_id']; ?>'
                    }
                }
            },
            password: {
                maxlength: 40,
                minlength: 6
            },
            real_name   : {
                required : true
            }
        },
        messages : {
            account : {
                required : '账户不能为空',
				email    : '请您填写有效的电子邮箱',
                byteRange: '账户名的长度应在6-40个字符之间',
                remote   : '该账户已经存在'
            },
            password : {
                maxlength: '密码长度应在6-40个字符之间',
                minlength: '密码长度应在6-40个字符之间'
            },
            real_name  : {
                required : '真实姓名不能为空'
            }
        }
    });
});
</script>
<div id="rightTop">
  <p>账户</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=deposit">管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=tradelist">交易记录</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=drawlist">提现管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=rechargelist">充值管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=setting">系统设置</a></li>
    <li><span>编辑</span></li>
  </ul>
</div>
<div class="info">
  <form method="post" enctype="multipart/form-data" id="account_form">
    <table class="infoTable">
      <tr>
        <th class="paddingT15"> 账户名:</th>
        <td class="paddingT15 wordSpacing5">
          <input class="infoTableInput2" id="account" type="text" name="account" value="<?php echo htmlspecialchars($this->_var['account']['account']); ?>" />
          <label class="field_notice">账户名必须是正确的电子邮件地址</label>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 支付密码:</th>
        <td class="paddingT15 wordSpacing5"><input class="infoTableInput2" name="password" type="text" id="password" />
          <label class="field_notice">留空表示不修改密码</label>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 真实姓名:</th>
        <td class="paddingT15 wordSpacing5">
        	<input class="infoTableInput2" name="real_name" type="text" id="real_name" value="<?php echo htmlspecialchars($this->_var['account']['real_name']); ?>" />
       		<label class="field_notice">真实姓名不能为空</label>
        </td>
      </tr>
      <tr>
        <th class="paddingT15"> 开启余额支付:</th>
        <td class="paddingT15">
          <span class="onoff">
            <label class="cb-enable <?php if ($this->_var['account']['pay_status'] == 'ON' || ! $this->_var['account']): ?>selected<?php endif; ?>">是</label>
            <label class="cb-disable <?php if ($this->_var['account']['pay_status'] == 'OFF'): ?>selected<?php endif; ?>">否</label>
            <input name="pay_status" value="ON" type="radio" <?php if ($this->_var['account']['pay_status'] == 'ON' || ! $this->_var['account']): ?>checked<?php endif; ?>>
            <input name="pay_status" value="OFF" type="radio" <?php if ($this->_var['account']['pay_status'] == 'OFF'): ?>checked<?php endif; ?>>
          </span>
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