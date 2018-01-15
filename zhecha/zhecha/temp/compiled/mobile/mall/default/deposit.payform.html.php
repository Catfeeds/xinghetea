<?php if ($this->_var['payform']['params']['payment_code'] == 'wxnativepay'): ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $this->_var['charset']; ?>">	
	<title>微信扫码支付</title>
    <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery-1.11.1.js'; ?>" charset="utf-8"></script>
    <style>body{text-align:center; background:#333;padding:50px;}.w{margin:0 auto;}.hd{color:#fff;font-size:20px; line-height:32px;}.bd .qrcode{width:280px;height:280px;border:1px #E2E2E2 solid; margin-top:15px;}.bd .status{box-shadow:inset 0 5px 10px -5px #191919,0 1px 0 0 #444;border-radius:100px; background-color:#232323; padding:14px 14px;width:252px; margin:0 auto;margin-top: 15px;color:#fff;}</style>
<script type="text/javascript">
$(function(){
	$.ajaxSettings.async = false;
	var interval = setInterval(function(){
		// check in PC App
		var url = '<?php echo $this->_var['site_url']; ?>/index.php?app=deposit&act=wxCheckPayment';
    	$.getJSON(url, {'payTradeNo':"<?php echo $this->_var['payTradeNo']; ?>"}, function(result){
        	if(result != '-1'){
				clearInterval(interval);
				self.location = "<?php echo $this->_var['real_site_url']; ?>/index.php?app=paynotify&payTradeNo=<?php echo $this->_var['payTradeNo']; ?>";
			}
    	});
	}, 3000);
});
</script>
</head>
<body>
	<div class="w">
	<div class="hd">微信扫码支付</div>
    <div class="bd">
    	<p><img class="qrcode" alt="微信扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo $this->_var['payform']['params']['code_url']; ?>" /></p>
        <div class="status">请使用微信扫描二维码支付</div>
    </div>
    </div>
</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $this->_var['charset']; ?>">
<style>
.payform-submit {
	margin: 20px;
	border: 1px #40B3FF solid;
	padding: 20px;
	background: #E5F5FF;
	font-weight: normal;
	font-size: 16px;
}
</style>
</head>
<body>
<div class="payform-submit">正在跳转至支付网关, 请稍等...</div>
<form action="<?php echo $this->_var['payform']['gateway']; ?>" id="payform" method="<?php echo $this->_var['payform']['method']; ?>" style="display:none">
  <?php $_from = $this->_var['payform']['params']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('_k', 'value');if (count($_from)):
    foreach ($_from AS $this->_var['_k'] => $this->_var['value']):
?>
  <input type="hidden" name="<?php echo $this->_var['_k']; ?>" value="<?php echo $this->_var['value']; ?>" />
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</form>
<script type="text/javascript">
	document.getElementById('payform').submit();
</script>
</body>
</html>
<?php endif; ?>