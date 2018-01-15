<?php echo $this->fetch('member.header.html'); ?> 
<script type="text/javascript">
$(function(){
    $('#email_form').validate({
        errorPlacement: function(error, element){
            $(element).next('label').remove();
            $(element).after(error);
        },
        success       : function(label){
			label.removeClass('error');
            label.addClass('validate_right').text('OK!');
        },
        rules : {
            orig_password : {
                required : true
            },
           email : {
                required : true,
                email    : true,
				remote   : {
                    url :'index.php?app=member&act=check_email_info&ajax=1',
                    type:'get',
                    data:{
                        email : function(){
                            return $('#email').val();
                        }
                    },
                    beforeSend:function(){
                        var _checking = $('#checking_email');
                        _checking.prev('.field_notice').hide();
                        _checking.next('label').hide();
                        $(_checking).show();
                    },
                    complete :function(){
                        $('#checking_email').hide();
                    }
                }
            }
        },
        messages : {
            orig_password : {
                required : '原始密码不能为空'
            },
            email : {
                required   : '您必须提供您的电子邮箱',
                email    : '这不是一个有效的电子邮箱',
				remote   : '此邮箱已经被注册'
            }
        }
    });
});
</script>
<style>
.borline td {padding:10px 0px;}
.ware_list th {text-align:left;}
.bgwhite {background: #FFFFFF;}
</style>
<div class="content">
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
		<div class="eject_con bgwhite">
			<div class="add">
				<form method="post" id="email_form">
					<ul>
						<li>
							<h3>您的密码:</h3>
							<p>
								<input class="text width_normal" type="password" name="orig_password" />
							</p>
						</li>
						<li>
							<h3>电子邮箱:</h3>
							<p>
								<input class="text width_normal" type="text" name="email" id="email" />
							</p>
						</li>
					</ul>
					<div class="submit">
						<input class="btn" type="submit" value="提交" />
					</div>
				</form>
			</div>
		</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?> 