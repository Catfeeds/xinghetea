<?php echo $this->fetch('member.header.html'); ?> 
<script>
$(function(){
	$('.J_OrderDetail').click(function(){
		$(this).find('em').toggleClass('arrow-down');
		$('#depopay-order-detail').toggle();
	});
	
	// 是否需要输入支付密码（余额支付需要，其他支付不需要）
	var checkPassword = false;
	
	// 如果首次加载，默认选择的不是余额支付（如余额不够或余额支付未开启）
	if($.inArray($('.J_SelectPaymentMethod li').find('input[name="payment_code"]:checked').val(), ["deposit"]) > -1){
		checkPassword = true;
		$('.J_DepositPassword').show();
	}
	
	$('.J_SelectPaymentMethod li').click(function(){
		if($(this).find('input[name="payment_code"]').prop('disabled') != true) {
			$(this).parent().find('li').removeClass('current');
			$(this).addClass('current');
		
			$(this).find('input[name="payment_code"]').prop('checked', true);
			
			if($.inArray($(this).find('input[name="payment_code"]').val(), ["deposit"]) > -1) {
				checkPassword = true;
				$('.J_DepositPassword').show();
			}
			else
			{
				checkPassword = false;
				$('.J_DepositPassword').hide();
			}
		}
	});
	
	$('.J_Password').change(function(){
		$('.J_PasswordNotice').hide();
	});
	
	$('#depayform').validate({
        errorPlacement: function(error, element){
			$('#depayform').find('.J_PasswordNotice').html('<p class="yellow">'+error.text()+'</p>');
            $('#depayform').find('.J_PasswordNotice').css('display', 'inline-block');
        },
        success       : function(label){
            $('#depayform').find('.J_PasswordNotice').html('<p class="correct-nobg">&nbsp;</p>');
			return true;
        },
		submitHandler : function(form){
			form.submit();
		},
        onkeyup : false,
        rules : {
            password : {
				required : function(){
					return checkPassword;
				},
				remote   : {
                    url : 'index.php?app=deposit&act=check_deposit_password_ajax',
                    type: 'get',
                    data:{
                        password : function(){
                            return $.trim($('.J_Password').val());
                        }
                    }
                }
            }
        },
        messages : {
            password  : {
				required : '请输入支付密码',
				remote   : '支付密码错误'
            }
        }
    });
});
</script>
<div class="depopay">
  <div class="paynotice"><?php echo $this->_var['paynotice']; ?> <a href="<?php echo $this->_var['site_url']; ?>" target="_blank">[?]</a></div>
  <div class="content">
    <div class="order-info">
      <div class="order-base clearfix">
        <div class="explain"> <span></span>
          <p>您正在使用<?php echo $this->_var['lang'][$this->_var['orderInfo']['payType']]; ?>付款</p>
        </div>
        <div class="goods-message clearfix">
          <h3 class="float-left"><?php echo sub_str(htmlspecialchars($this->_var['orderInfo']['title']),20); ?></h3>
        </div>
        <div class="payAmount clearfix"> <strong class="float-left mr5"><?php echo $this->_var['orderInfo']['amount']; ?></strong> 元 <a href="javascript:window.location.reload();" class="f60">刷新</a> </div>
      </div>
      <div class="order-detail hidden" id="depopay-order-detail">
        <div class="clearfix <?php if ($this->_var['orderInfo']['mergePay']): ?>mergepay<?php else: ?>normalpay<?php endif; ?>">
          <ul class="hd clearfix">
            <li>订单编号<span>：</span></li>
            <li class="name">商品名称<span>：</span></li>
            <li>卖家店铺<span>：</span></li>
            <li>交易金额<span>：</span></li>
          </ul>
          <div class="bd"> 
            <?php $_from = $this->_var['orderInfo']['tradeList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tradeInfo');if (count($_from)):
    foreach ($_from AS $this->_var['tradeInfo']):
?>
            <ul class="clearfix">
              <li><?php echo $this->_var['tradeInfo']['bizOrderId']; ?></li>
              <li class="name"><?php echo $this->_var['tradeInfo']['name']; ?> </li>
              <li><?php echo $this->_var['tradeInfo']['seller']; ?> </li>
              <li><?php echo $this->_var['tradeInfo']['amount']; ?></li>
            </ul>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
          </div>
        </div>
      </div>
      <div class="detail-more J_OrderDetail"> <em class="arrow-down"></em> <a href="javascript:;">订单详情</a> </div>
    </div>
    <div  class="depay-form">
      <form method="post" id="depayform">
        <div class="bgf mb10 pb20" style="border:1px #CCD8F2 solid ">
          <div class="account-detail">
            <ul class="detail-line pb5 clearfix">
              <li>预存款账户</span>( <?php echo $this->_var['deposit_account']['account']; ?> )</li>
              <li>可支付余额：<strong class="price"><?php echo $this->_var['deposit_account']['money']; ?></strong> 元</li>
            </ul>
            <div class="notice-word mt10">
              <p>提示：如果订单金额较大，请先 <a href="<?php echo url('app=deposit&act=recharge'); ?>" target="_blank">充值</a> 后再支付订单。</p>
            </div>
          </div>
          <div class="netpay">
            <div class="netpay-title"><span>请选择付款方式</span></div>
            <div class="netpay-content clearfix">
              <div class="bank-list-line clearfix">
                <ul class="ui-list-icons clearfix J_SelectPaymentMethod" style="border-bottom:0;">
                  
                  <?php $_from = $this->_var['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');$this->_foreach['fe_payment'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_payment']['total'] > 0):
    foreach ($_from AS $this->_var['payment']):
        $this->_foreach['fe_payment']['iteration']++;
?>
                  <li class="<?php if ($this->_var['payment']['selected']): ?>current<?php endif; ?> clearfix">
                    <input class="float-left" <?php if ($this->_var['payment']['disabled']): ?>disabled="disabled" <?php elseif ($this->_var['payment']['selected']): ?> checked="checked"<?php endif; ?> type="radio" name="payment_code" id="payment_<?php echo $this->_var['payment']['payment_code']; ?>" value="<?php echo $this->_var['payment']['payment_code']; ?>" />
                    <label class="float-left  icon-box" for="payment_<?php echo $this->_var['payment']['payment_code']; ?>" > <span class="icon-cashier icon-cashier-<?php echo $this->_var['payment']['payment_code']; ?>">&nbsp;</span> </label>
                    <?php if (in_array ( $this->_var['payment']['payment_code'] , array ( 'deposit' ) )): ?> 
                    <em class="gray inline-block" style="margin-top:8px;"> 
                    <?php if ($this->_var['payment']['disabled']): ?> 
                    <?php echo $this->_var['payment']['disabled_desc']; ?> 
                    <?php else: ?> 
                    余额 <?php echo $this->_var['deposit_account']['money']; ?> 元 
                    <?php endif; ?> 
                    </em> 
                    <?php endif; ?> 
                  </li>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                  
                </ul>
              </div>
            </div>
          </div>
          <div class="paysubmit J_DepositPassword hidden" style="margin-left:25px;">
            <dl class="clearfix mt20 ">
              <dt>支付密码：</dt>
              <dd class="mt5">
                <input type="password" class="pay-password J_Password" name="password" />
                <a href="<?php echo url('app=deposit&act=config'); ?>" target="_blank">忘记密码？</a> </dd>
              <dd class="notice-word hidden J_PasswordNotice">&nbsp; </dd>
            </dl>
          </div>
        </div>
        <div class="mt10">
          <input type="submit" class="btn-alipay" value="确认付款" />
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $this->fetch('footer.html'); ?> 