<?php echo $this->fetch('top.html'); ?> 
<script type="text/javascript">
$(function(){
    $('#login_form').validate({
        errorPlacement: function(error, element){
           var error_td = element.parent('dd');
            error_td.find('label').hide();
			error_td.find('i').hide();
            error_td.append(error);
        },
        success       : function(label){
            label.siblings('i').show().addClass('ok');
            label.remove();
        },
        onkeyup : false,
        rules : {
            user_name : {
                required : true
            },
            password : {
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
                required : '您必须提供一个用户名'
            },
            password  : {
                required : '您必须提供一个密码'
            },
            captcha : {
                required : '输入验证码',
                remote   : '验证码错误'
            }
        }
    });
	$('input[name="user_name"], input[name="password"], input[name="captcha"]').click(function(){
		$(this).parent().find('label').hide();
	});
});
</script>
<style>
.w{width:1000px;}
</style>
<div id="main" class="w-full">
	<div id="page-login" class="w page-auth mt20 mb20">
		<div class="w logo mb10"> <a href="<?php echo $this->_var['site_url']; ?>" title="<?php echo $this->_var['site_title']; ?>"><img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>" /></a> </div>
		<div class="w clearfix">
			<div class="col-main">
				<div class="login-edit-field" area="login_left" widget_type="area"> 
					<?php $this->display_widgets(array('page'=>'login','area'=>'login_left')); ?> 
				</div>
			</div>
			<div class="col-sub">
				<div class="form">
					<div class="title">用户登录 </div>
					<div class="content">
						<form method="post" id="login_form">
							<dl class="clearfix">
								<dt>用户名</dt>
								<dd>
									<input class="input" type="text" name="user_name"  id="user_name" title="请填写您的用户名"/>
									<i class="i-name"></i>
								</dd>
							</dl>
							<dl class="clearfix">
								<dt>密&nbsp;&nbsp;&nbsp;码</dt>
								<dd>
									<input class="input" type="password" name="password"  id="password" title="请填写您的登录密码" />
									<i class="i-psw"></i>
								</dd>
							</dl>
							
							<?php if ($this->_var['captcha']): ?>
							<dl class="clearfix">
								<dt>验证码</dt>
								<dd class="captcha clearfix">
									<input type="text" class="input float-left" name="captcha" id="captcha1" title="请输入验证码，不区分大小写"/>
									 <a href="javascript:change_captcha($('#captcha'));" class="float-left"><img height="26" id="captcha" src="index.php?app=captcha&amp;<?php echo $this->_var['random_number']; ?>" class="float-left" /></a>
								</dd>
							</dl>
							<?php endif; ?>
							<dl class="clearfix">
								<dt>&nbsp;</dt>
								<dd class="clearfix">
									<input type="submit" class="login-submit" name="Submit" value="登录" title="登录" />
									<input type="hidden" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
								</dd>
							</dl>
							
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>