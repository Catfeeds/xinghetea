<?php echo $this->fetch('top.html'); ?> 
<script type="text/javascript">
$(function(){
    $('#find_password_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
			error_td.find('i').removeClass('ok');
            error_td.find('label').hide();
            error_td.append(error);
        },
        success       : function(label){
			label.siblings('i').addClass('ok');
            label.remove();
        },
		submitHandler: function(form) {
			if($('input[name="codeType"]').val() != '' && $('input[name="code"]').val() != '') {
				form.submit();
			} else {
				$('.J_Step li:first').removeClass('current').addClass('done');
				$('.J_Step li:first').next('li').removeClass('next').addClass('current');
				$('.J_Step li:first').next('li').next('li').addClass('next');
				var user_name = $.trim($('input[name="user_name"]').val());
   				ajax_form('find_password', '验证码', SITE_URL + '/index.php?app=gselector&act=captcha&dialog=1&title=验证码&id=find_password&user_name='+user_name, '500', 'simple-blue', '0.05');
        		return false;
			}
 		},
        onkeyup: false,
        rules : {
            user_name : {
                required : true
            },
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?app=captcha&act=check_captcha',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha1').val();
                        }
                    }
                }
            } 
        },
        messages : {
            user_name : {
                required : '用户名不能为空'
            },
            captcha : {
                required : '验证码不能为空',
                remote   : '验证码错误'
            }
        }
    });
});
function gs_callback(id, codeType, code)
{
	$('input[name="codeType"]').val(codeType);
	$('input[name="code"]').val(code);
	DialogManager.close(id);
	$('#find_password_form').submit();
}
</script>
<style>
.w{width:1000px;}
</style>
<div id="main" class="w-full">
	<div id="page-find-password" class="w-full page-auth mt20 mb20">
		<div class="wrap">
			<div class="w logo mb10">
				<p><a href="<?php echo $this->_var['site_url']; ?>" title="<?php echo $this->_var['site_title']; ?>"><img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>" /></a></p>
			</div>
			<div class="form clearfix w">
				<div class="password_box">
					<div class="flowsteps">
						<ol class="num4 J_Step">
							<li class="current"><span class="first">1.输入账户名</span></li>
							<li class="next"><span>2.验证身份</span></li>
							<li><span>3.重置密码</span></li>
							<li><span class="last">4.完成</span></li>
						</ol>
					</div>
				</div>
				<form id="find_password_form" method="post">
					<div class="w">
						<dl class="clearfix">
							<dt>登录账号</dt>
							<dd class="clearfix">
								<input type="text" class="input" name="user_name" />
								<i class="i-name"></i> </dd>
						</dl>
						<dl class="clearfix">
							<dt>验证码</dt>
							<dd class="captcha clearfix">
								<input type="text" class="input float-left" name="captcha"  id="captcha1" />
								<a href="javascript:change_captcha($('#captcha'));" class="float-left mt5" style="margin-right:44px;"><img height="26" id="captcha" src="index.php?app=captcha&amp;<?php echo $this->_var['random_number']; ?>" /></a> </dd>
						</dl>
						<dl class="clearfix">
							<dt>&nbsp;</dt>
							<dd>
								<input type="hidden" name="codeType" value="" />
								<input type="hidden" name="code" value="" />
								<input type="submit" value="提交" class="fp-submit" title="找回密码" />
							</dd>
						</dl>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>