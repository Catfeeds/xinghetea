<?php echo $this->fetch('header.html'); ?>
<script language="javascript">
$(function(){
	<?php if ($this->_var['dangerous_apps']): ?>
	var dangerous_apps = '';
	<?php $_from = $this->_var['dangerous_apps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'da');if (count($_from)):
    foreach ($_from AS $this->_var['da']):
?>
	dangerous_apps += "<?php echo $this->_var['da']; ?>\r\n";
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	parent.layer.alert(dangerous_apps,{icon: 0});
	<?php endif; ?>
	
	$.getJSON(REAL_BACKEND_URL + '/index.php?app=license&act=license', '', function(data){
		if (data){
			//$('#license').html(data);
		}
	});
});


</script>
<style>
body{background:#eaedf1;margin:0;padding:0;}
#rightTop{padding:5px 5px 5px 20px;}
#rightCon{background:none;padding:0;margin-left:0;}
</style>

<div id="rightTop">
<p>
    您好，<span><?php echo $this->_var['admin']['user_name']; ?></span>，欢迎使用 ECMall。您上次登录的时间是 <?php echo local_date("Y-m-d H:i:s",$this->_var['admin']['last_login']); ?> ，IP 是 <?php echo $this->_var['admin']['last_ip']; ?>
	&nbsp;&nbsp;<span id="license" style="vertical-align:middle"></span>
</p>
</div>
<div id="rightCon" class="clearfix">

<?php if ($this->_var['dangerous_apps']): ?>
<div class="each clear">
<dl>
<dt>警告！！！</dt>
<dd>
    <ul style="color:#E4393C; font-weight:normal;;">
        <?php $_from = $this->_var['dangerous_apps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'da');if (count($_from)):
    foreach ($_from AS $this->_var['da']):
?>
        <li><?php echo $this->_var['da']; ?></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</dd>
</dl>
</div>
<?php endif; ?>

<?php if ($this->_var['remind_info']): ?>
<div class="each clear">
<dl>
<dt>站长提醒</dt>
<dd>
    <ul>
        <?php $_from = $this->_var['remind_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'remind');if (count($_from)):
    foreach ($_from AS $this->_var['remind']):
?>
        <li><?php echo $this->_var['remind']; ?></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</dd>
</dl>
</div>
<?php endif; ?>

<div class="each twocol">
<dl>
<dt>一周动态</dt>
<dd>
    <table>
        <tr>
            <th>新增会员数:</th>
            <td class="td"><?php echo $this->_var['news_in_a_week']['new_user_qty']; ?></td>
            <th>新增店铺数/申请数:</th>
            <td class="td"><?php echo $this->_var['news_in_a_week']['new_store_qty']; ?>/<?php echo $this->_var['news_in_a_week']['new_apply_qty']; ?></td>
        </tr>
        <tr>
            <th>新增商品数:</th>
            <td class="td"><?php echo $this->_var['news_in_a_week']['new_goods_qty']; ?></td>
            <th>新增订单数:</th>
            <td class="td"><?php echo $this->_var['news_in_a_week']['new_order_qty']; ?></td>
        </tr>
    </table>
</dd>
</dl>
</div>

<div class="each twocol">
<dl>
<dt>统计信息</dt>
<dd>
    <table>
        <tr>
            <th>会员总数:</th>
            <td class="td"><?php echo $this->_var['stats']['user_qty']; ?></td>
            <th>店铺总数/申请总数:</th>
            <td class="td"><?php echo $this->_var['stats']['store_qty']; ?>/<?php echo $this->_var['stats']['apply_qty']; ?></td>
        </tr>
        <tr>
            <th>商品总数:</th>
            <td class="td"><?php echo $this->_var['stats']['goods_qty']; ?></td>
            <th>订单总数:</th>
            <td class="td"><?php echo $this->_var['stats']['order_qty']; ?></td>
        </tr>
        <tr>
            <th>订单总金额:</th>
            <td class="td"><?php echo price_format($this->_var['stats']['order_amount']); ?></td>
            <th></th>
            <td class="td"></td>
        </tr>
    </table>
</dd>
</dl>
</div>

<div class="each clear">
<dl>
<dt>系统信息</dt>
<dd>
    <table>
        <tr>
            <th>服务器操作系统:</th>
            <td class="td"><?php echo $this->_var['sys_info']['server_os']; ?></td>
            <th>WEB 服务器:</th>
            <td class="td"><?php echo $this->_var['sys_info']['web_server']; ?></td>
        </tr>
        <tr>
            <th>PHP 版本:</th>
            <td class="td"><?php echo $this->_var['sys_info']['php_version']; ?></td>
            <th>MYSQL 版本:</th>
            <td class="td"><?php echo $this->_var['sys_info']['mysql_version']; ?></td>
        </tr>
        <tr>
            <th>ECMall 版本:</th>
            <td class="td"><?php echo $this->_var['sys_info']['ecmall_version']; ?></td>
            <th>安装日期:</th>
            <td class="td"><?php echo $this->_var['sys_info']['install_date']; ?></td>
        </tr>
    </table>
</dd>
</dl>
</div>

</div>
<?php echo $this->fetch('footer.html'); ?>
