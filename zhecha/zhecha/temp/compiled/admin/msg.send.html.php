<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
//<!CDATA[
$(function(){
    $('#msg_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
        rules : {
            to_mobile : {
                required : true,
            },
			msg_content : {
                required : true,
            },
        },
        messages : {
            to_mobile : {
                required : '内容不能为空！',
            },
			msg_content : {
                required : '内容不能为空！',
            },
        }
    });
});
//]]>
</script>
<div id="rightTop">
	<p>手机短信管理</p>
    <ul class="subnav" style="margin-left:0px;">
        <li><a class="btn1" href="index.php?app=msg">发送记录</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=user">短信用户</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=add">分配短信</a></li>
        <li><span>短信发送</span></li>
        <li><a class="btn1" href="index.php?app=msg&act=setting">设置</a></li>
    </ul>
</div>
<div class="info">
  <form method="post" enctype="multipart/form-data" id="msg_form">
  	<table class="infoTable">
            <tr>
                <th class="paddingT15">手机号码:</th>
                <td class="paddingT15 wordSpacing5">
				<input type="text" class="text width_normal" name="to_mobile" value="" style="width:260px;"/>
                <label class="field_notice">输入接收信息的手机号码,多个手机号请用半角逗号隔开</label>
				</td>
            </tr>
            <tr>
                <th class="paddingT15">内 容: </th>
              <td class="paddingT15 wordSpacing5">
				<textarea class="text width_long" name="msg_content" /></textarea>
                <label class="field_notice">短信内容不含空格</label>
				</td>
            </tr>
            <tr>
            <th></th>
            <td class="ptb20">
                <input class="formbtn J_FormSubmit" type="submit" name="Submit" value="确定发送" />
                <input class="formbtn" type="reset" name="Submit2" value="重置" />
            </td>
            </tr>
      </table>	
    </form>
</div>
<?php echo $this->fetch('footer.html'); ?>