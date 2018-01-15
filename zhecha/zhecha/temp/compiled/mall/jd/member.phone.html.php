<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#phone_form').validate({
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
           phone : {
                number   : true,
                maxlength: 11,
                minlength: 11,
				remote   : {
                    url :'index.php?app=member&act=check_phone_mob&ajax=1',
                    type:'get',
                    data:{
                        phone_mob : function(){
                            return $('#phone_mob').val();
                        }
                    },
                    beforeSend:function(){
                        var _checking = $('#checking_phone_mob');
                        _checking.prev('.field_notice').hide();
                        _checking.next('label').hide();
                        $(_checking).show();
                    },
                    complete :function(){
                        $('#checking_phone_mob').hide();
                    }
                }
		   }
        },
        messages : {
            orig_password : {
                required : '原始密码不能为空'
            },
            phone : {
                number   : '手机号码不能为空',
                maxlength: '手机号码错误！',
                minlength: '手机号码错误！',
				remote   : '此手机已经被注册'
            }
        }
    });
});
</script>
<div class="content">
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right">
    	<?php echo $this->fetch('member.curlocal.html'); ?>
        <?php echo $this->fetch('member.submenu.html'); ?>
        <div class="wrap">
        <div class="eject_con bgwhite">
            <div class="add">
                <form method="post" id="phone_form">
                    <ul>
                        <li><h3>您的密码:</h3>
                        <p><input class="text width_normal" type="password" name="orig_password" /></p>
                        </li>
                        <li><h3>手机:</h3>
                        <p><input class="text width_normal" type="text" name="phone" value="<?php echo $this->_var['phone_mob']; ?>" id="phone_mob"/></p>
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
