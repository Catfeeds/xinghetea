<?php 
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:您的订单已生成',
  'content' => '<p>
	尊敬的{$order.buyer_name}:
</p>
<p>
	您在{$site_name}上下的订单已生成，订单号{$order.order_sn}。
</p>
<p>
	查看订单详细信息请点击以下链接
</p>
<p>
	<a href="{$site_url}/index.php?app=buyer_order&act=view&order_id={$order.order_id}">{$site_url}/index.php?app=buyer_order&act=view&order_id={$order.order_id}</a> 
</p>
<p style="text-align:right;">
	{$site_name}
</p>
<p style="text-align:right;">
	{$mail_send_time}
</p>',
);
?>