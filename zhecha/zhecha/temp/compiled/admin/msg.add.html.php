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
            user_name : {
                required : true,
            },
			num : {
                number : true,
            },
        },
        messages : {
            user_name : {
                required : '内容不能为空！',
            },
			num : {
                number : '只能使用数字'
            },
        }
    });
});
//]]>
</script>
<style type="text/css">
.explanation ul li strong{color:#E4393C; margin:0 3px;}
</style>
<div id="rightTop">
	<p>手机短信管理</p>
    <ul class="subnav" style="margin-left:0px;">
        <li><a class="btn1" href="index.php?app=msg">发送记录</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=user">短信用户</a></li>
        <li><span>分配短信</span></li>
        <li><a class="btn1" href="index.php?app=msg&act=send">短信发送</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=setting">设置</a></li>
    </ul>
</div>  

<div class="explanation" id="explanation">
  <div class="title" id="checkZoom">
  	<i class="fa fa-lightbulb-o"></i>
    <h4 title="操作提示">提示</h4>
    <span id="explanationZoom" title="收起提示"></span>
  </div>
  <ul>
    <li><i class="fa fa-angle-double-right"></i> 1. 您的账号剩余短信数量：<strong><?php echo ($this->_var['statist']['available'] == '') ? '0' : $this->_var['statist']['available']; ?></strong>条。</li>
    <li><i class="fa fa-angle-double-right"></i> 2. 成功发送短信数量：<strong><?php echo $this->_var['statist']['used']; ?></strong>条。</li>
    <li><i class="fa fa-angle-double-right"></i> 3. 分配给用户短信数量且未使用：<strong><?php echo $this->_var['statist']['allocated']; ?></strong>条。</li>
    <li><i class="fa fa-angle-double-right"></i> 4. 您目前最多能分配短信：<strong><?php echo $this->_var['statist']['distributable']; ?></strong>条。</li>
    <?php if ($this->_var['statist']['available'] <= 50): ?>
    <li><i class="fa fa-angle-double-right"></i> 5. 您目前的可用的短信数量已不足<strong>50</strong>条，请尽快到网建平台充值，以免影响网站交易。</li>
    <?php endif; ?>
  </ul>
</div>
<div class="info">
	<form method="post" enctype="multipart/form-data" id="msg_form">
  		<table class="infoTable">
            <tr>
                <th class="paddingT15">用户名:</th>
                <td class="paddingT15 wordSpacing5">
				<input name="user_name" type="text" value="<?php echo $this->_var['user']['user_name']; ?>" size="10">
                <label class="field_notice">输入用户名</label>
				</td>
            </tr>
            <tr>
                <th class="paddingT15">数 量:</th>
              <td class="paddingT15 wordSpacing5">
				<input name="num" type="text" size="10">
                <label class="field_notice">输入数量（正整数）</label>
				</td>
            </tr>
            <tr>
                <th class="paddingT15">操 作:</th>
           <td class="paddingT15 wordSpacing5">
               <input name="add_dec" type="radio" value="1" checked="CHECKED" /> 增加&nbsp;&nbsp;
               <input type="radio" name="add_dec" value="0"/> 减少
			  </td>
            </tr>
			<tr>
                <th class="paddingT15">操作记录:</th>		
                <td class="paddingT15 wordSpacing5">
				<textarea name="log_text"><?php echo $this->_var['visitor']['user_name']; ?>手工操作短信数量</textarea>
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