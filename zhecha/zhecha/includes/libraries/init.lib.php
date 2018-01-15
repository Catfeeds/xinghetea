<?php

define("CUSTOMERID","814605515");
define('LOCK_FILE', ROOT_PATH . '/data/init.lock');

if(!file_exists(LOCK_FILE)) 
{
	// 如果有手机版的，需要加上此项
	if(!defined('CHARSET')) {
		define('CHARSET', substr(LANG, 3));
	}
	
	Psmb_init()->create_table();
	
	/* 创建完表后，生成锁定文件 */
	file_put_contents(LOCK_FILE,1);
}

class Psmb_init 
{
	function create_table()
	{
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'uploaded_file');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('link_url', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'uploaded_file` ADD `link_url` VARCHAR( 100 ) NOT NULL ';
			db()->query($sql);
		}
		/* store  table */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'store');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('pic_slides', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `pic_slides` TEXT NOT NULL AFTER `im_msn`';
			db()->query($sql);
		}
		if(!in_array('business_scope', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `business_scope` VARCHAR( 50 ) NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('avg_goods_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `avg_goods_evaluation` decimal(8,2)  NOT NULL';
			db()->query($sql);
		}
		if(!in_array('avg_service_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `avg_service_evaluation` decimal(8,2) NOT NULL';
			db()->query($sql);
		}
		if(!in_array('avg_shipped_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `avg_shipped_evaluation` decimal(8,2) NOT NULL';
			db()->query($sql);
		}
		if(!in_array('latlng', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `latlng` varchar(100) NOT NULL';
			db()->query($sql);
		}
		
		/* 合作伙伴显示隐藏 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'partner');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('if_show', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'partner` ADD `if_show` int(1) NOT NULL';
			db()->query($sql);
		}
	
		/*店铺动态评分*/
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'order_goods');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('reply_content', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order_goods` ADD `reply_content` TEXT NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('reply_time', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order_goods` ADD `reply_time` INT(10) NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('shipped_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order_goods` ADD `shipped_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('service_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order_goods` ADD `service_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('goods_evaluation', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order_goods` ADD `goods_evaluation` decimal(4,2) NOT NULL ';
			db()->query($sql);
		}
	
		/* 团购表增加团购大图 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'groupbuy');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('group_image', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'groupbuy` ADD `group_image` VARCHAR( 255 ) NOT NULL AFTER `group_name` ';
			db()->query($sql);
		}
		
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'order');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('express_company', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `express_company` VARCHAR( 50 ) NOT NULL AFTER `invoice_no` ';
			db()->query($sql);
		}
		if(!in_array('adjust_amount', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `adjust_amount` decimal(10,2) NOT NULL default 0 AFTER `discount` ';
			db()->query($sql);
		}
		
		
		/* 卖家给订单备注 */
		if(!in_array('flag', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `flag` int( 1 ) NOT NULL ';
			db()->query($sql);
		}
		if(!in_array('memo', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `memo` varchar( 255 ) NOT NULL ';
			db()->query($sql);
		}
		
		
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "ultimate_store` (
	  		`ultimate_id` int(255) NOT NULL AUTO_INCREMENT,
	  		`brand_id` int(50) NOT NULL,
	  		`keyword` varchar(20) NOT NULL,
	  		`cate_id` int(50) NOT NULL,
	  		`store_id` int(50) NOT NULL,
	  		`status` tinyint(1) NOT NULL DEFAULT '0',
	  		`description` varchar(255) DEFAULT NULL,
	 		 PRIMARY KEY (`ultimate_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'limitbuy` (
  			`pro_id` int(11) NOT NULL auto_increment,
  			`goods_id` int(11) NOT NULL,
  			`pro_name` varchar(50) NOT NULL,
  			`pro_desc` varchar(255) NOT NULL,
  			`start_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			`store_id` int(11) NOT NULL,
  			`spec_price` text NOT NULL,
			`image` VARCHAR( 255 ) NOT NULL,
  			PRIMARY KEY  (`pro_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);	
		
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."member_bind` (
			`id` int(11) NOT NULL auto_increment,
			`unionid` varchar(255) NOT NULL,
  			`openid` varchar(255) NOT NULL,
  			`user_id` int(11) NOT NULL,
  			`app` varchar(50) NOT NULL,
			`token` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'cate_pvs` (
  			`cate_id` int(11) NOT NULL,
  			`pvs` text NOT NULL,
			PRIMARY KEY  (`cate_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
	
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."goods_prop` (
  			`pid` int(11) NOT NULL auto_increment,
  			`name` varchar(50) NOT NULL,
			`prop_type` VARCHAR( 20 ) NOT NULL DEFAULT 'select',
			`is_color_prop` INT NOT NULL DEFAULT '0',
  			`status` int(1) NOT NULL,
  			`sort_order` int(11) NOT NULL,
  			PRIMARY KEY  (`pid`)
		) ENGINE=MyISAM  DEFAULT CHARSET=".str_replace('-','',CHARSET).';';
		db()->query($sql);
	
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'goods_prop_value` (
  			`vid` int(11) NOT NULL auto_increment,
  			`pid` int(11) NOT NULL,
  			`prop_value` varchar(255) NOT NULL,
			`color_value` VARCHAR( 255 ) NOT NULL,
  			`status` int(1) NOT NULL,
  			`sort_order` int(11) NOT NULL,
  			PRIMARY KEY  (`vid`)
		) ENGINE=MyISAM  DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
	
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'goods_pvs` (
  			`goods_id` int(11) NOT NULL,
  			`pvs` text NOT NULL,
  			PRIMARY KEY  (`goods_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
		
		
		/* 虚拟币功能*/
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_account` (
  			`account_id` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`account` varchar(100) NOT NULL,
  			`password` varchar(255) NOT NULL,
  			`money` decimal(10,2) NOT NULL,
  			`frozen` decimal(10,2) NOT NULL,
  			`real_name` varchar(30) NOT NULL,
  			`pay_status` varchar(3) NOT NULL DEFAULT 'off',
  			`add_time` int(11) NOT NULL,
  			`last_update` int(11) NOT NULL,
  			PRIMARY KEY (`account_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_recharge` (
  			`recharge_id` int(11) NOT NULL AUTO_INCREMENT,
  			`orderId` varchar(30) NOT NULL,
  			`user_id` int(11) NOT NULL,
			`examine` varchar(100) NOT NULL,
  			`is_online` int(1) NOT NULL,
  			PRIMARY KEY (`recharge_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_trade` (
 			`trade_id` int(11) NOT NULL AUTO_INCREMENT,
  			`tradeNo` varchar(32) NOT NULL COMMENT '支付交易号',
			`outTradeNo` varchar(32) NOT NULL COMMENT '第三方支付接口的交易号',
			`payTradeNo` varchar(32) NOT NULL COMMENT '第三方支付接口的商户订单号',
			`merchantId` varchar(32) NOT NULL COMMENT '商户号',
  			`bizOrderId` varchar(32) NOT NULL COMMENT '商户订单号',
			`bizIdentity` varchar(20) NOT NULL COMMENT '商户交易类型识别号',
  			`buyer_id` int(11) NOT NULL COMMENT '交易买家',
			`seller_id` int(11) NOT NULL COMMENT '交易卖家',
  			`amount` decimal(10,2) NOT NULL COMMENT '交易金额',
			`status` varchar(30) NOT NULL,
			`payment_code` varchar(20) NOT NULL COMMENT '支付方式代号',
			`payment_bank` varchar(20) NOT NULL COMMENT '网银支付代号',
			`pay_alter` int(11) NOT NULL COMMENT '支付方式变更标记',
			`tradeCat` varchar(20) NOT NULL COMMENT '交易分类',
			`payType` varchar(20) NOT NULL COMMENT '支付类型(担保即时)',
			`flow` varchar(10) NOT NULL COMMENT '资金流向',
			`fundchannel` varchar(20) NOT NULL COMMENT '资金渠道',
			`payTerminal` varchar(10) NOT NULL COMMENT '支付终端',
			`title` varchar(100) NOT NULL COMMENT '交易标题',
  			`buyer_remark` varchar(255) NOT NULL COMMENT '买家备注',
			`seller_remark` varchar(255) NOT NULL COMMENT '卖家备注',
			`add_time` int(11) NOT NULL,
  			`pay_time` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`trade_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_record` (
 			`record_id` int(11) NOT NULL AUTO_INCREMENT,
  			`tradeNo` varchar(30) NOT NULL,
  			`user_id` int(11) NOT NULL,
			`amount` decimal(10,2) NOT NULL COMMENT '收支金额',
  			`balance` decimal(10,2) NOT NULL COMMENT '账户余额',
			`flow` varchar(10) NOT NULL COMMENT '收支',
			`tradeType` varchar(20) NOT NULL COMMENT '交易类型',
			`tradeTypeName` varchar(20) NOT NULL COMMENT '交易类型名称',
			`name` varchar(100) NOT NULL COMMENT '名称',
			`remark` varchar(255) NOT NULL COMMENT '备注',
  			PRIMARY KEY (`record_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_setting` (
  			`setting_id` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`trade_rate` decimal(10,3) NOT NULL COMMENT '交易手续费',
  			`transfer_rate` decimal(10,3) NOT NULL,
			`auto_create_account` int(1)  NOT NULL,			
  			PRIMARY KEY (`setting_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "deposit_withdraw` (
  			`withdraw_id` int(11) NOT NULL AUTO_INCREMENT,
  			`orderId` varchar(30) NOT NULL,
  			`user_id` int(11) NOT NULL,
  			`card_info` text NOT NULL,
  			PRIMARY KEY (`withdraw_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "bank` (
  			`bid` int(11) NOT NULL AUTO_INCREMENT,
  			`user_id` int(11) NOT NULL,
  			`bank_name` varchar(100) NOT NULL,
  			`short_name` varchar(20) NOT NULL,
  			`account_name` varchar(20) NOT NULL,
  			`open_bank` varchar(100) NOT NULL,
  			`type` varchar(10) NOT NULL,
  			`num` varchar(50) NOT NULL,
			PRIMARY KEY (`bid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		/* 订单商品的确认收货状态（虚拟币功能需要） */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'order_goods');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('status', $fields)){
			$sql = "ALTER TABLE `".DB_PREFIX."order_goods` ADD  `status` varchar(50) NOT NULL ";
			db()->query($sql);
		}

	
		/* 退款功能 */	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "refund` (
  			`refund_id` int(11) NOT NULL AUTO_INCREMENT,
			`tradeNo` varchar(30) NOT NULL,
  			`refund_sn` varchar(30) NOT NULL,
			`title` varchar(255) NOT NULL,
  			`refund_reason` varchar(50) NOT NULL,
  			`refund_desc` varchar(255) NOT NULL,
  			`total_fee` decimal(10,2) NOT NULL,
  			`goods_fee` decimal(10,2) NOT NULL,
  			`shipping_fee` decimal(10,2) NOT NULL,
			`refund_total_fee` decimal(10,2) NOT NULL,
 			`refund_goods_fee` decimal(10,2) NOT NULL,
  			`refund_shipping_fee` decimal(10,2) NOT NULL,
  			`buyer_id` int(10) NOT NULL,
  			`seller_id` int(10) NOT NULL,
  			`status` varchar(100) NOT NULL DEFAULT '',
  			`shipped` int(11) NOT NULL,
  			`ask_customer` int(1) NOT NULL DEFAULT '0',
  			`created` int(11) NOT NULL,
  			`end_time` int(11) NOT NULL,
  			PRIMARY KEY (`refund_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
		$sql ="CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "refund_message` (
  			`rm_id` int(11) NOT NULL AUTO_INCREMENT,
  			`owner_id` int(11) NOT NULL,
  			`owner_role` varchar(10) NOT NULL,
  			`refund_id` int(11) NOT NULL,
  			`content` varchar(255) DEFAULT NULL,
  			`pic_url` varchar(255) DEFAULT NULL,
  			`created` int(11) NOT NULL,
  			PRIMARY KEY (`rm_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'goods');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('delivery_template_id', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'goods` ADD `delivery_template_id` INT (11) NOT NULL ';
			db()->query($sql);
		}
		$sql = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "delivery_template` (
  			`template_id` int(11) NOT NULL AUTO_INCREMENT,
  			`name` varchar(50) NOT NULL,
  			`store_id` int(10) NOT NULL,
  			`template_types` text NOT NULL,
  			`template_dests` text NOT NULL,
  			`template_start_standards` text NOT NULL,
  			`template_start_fees` text NOT NULL,
  			`template_add_standards` text NOT NULL,
  			`template_add_fees` text NOT NULL,
  			`created` int(10) NOT NULL,
  			PRIMARY KEY (`template_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'payment');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('cod_regions', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'payment` ADD `cod_regions` TEXT NOT NULL ';
			db()->query($sql);
		}
		
		
		/*积分功能*/
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'goods_integral` (
  			`goods_id` int(11) NOT NULL,
  			`max_exchange` int(11) NOT NULL,
  			PRIMARY KEY  (`goods_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
		
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'integral` (
			`user_id` int(11) NOT NULL,
			`amount` decimal(10,2) NOT NULL,
			PRIMARY KEY  (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."integral_log` (
			`log_id` int(11) NOT NULL AUTO_INCREMENT,
			`user_id` int(10) NOT NULL,
			`order_id` int(10) NOT NULL DEFAULT '0',
			`order_sn` varchar(20) NOT NULL,
			`changes` decimal(25,2) NOT NULL,
			`balance` decimal(25,2) NOT NULL,
			`type` varchar(50) NOT NULL,
			`state` varchar(50) NOT NULL,
			`flag` varchar(255) NOT NULL ,
			`add_time` int(11) NOT NULL,
			PRIMARY KEY (`log_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=".str_replace("-",'',CHARSET).";";
		db()->query($sql);
		
		$sql = 'CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'order_integral` (
			`order_id` int(11) NOT NULL,
			`buyer_id` int(11) NOT NULL,
			`frozen_integral` decimal(10,2) NOT NULL,
			PRIMARY KEY  (`order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET='.str_replace('-','',CHARSET).';';
		db()->query($sql);
		
		//设置默认的送货地址
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'address');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('setdefault', $fields)){
			$sql = "ALTER TABLE `".DB_PREFIX."address` ADD  `setdefault` tinyint(3) NOT NULL DEFAULT '0' ";
			db()->query($sql);
		}
		
		/* 短信功能 */
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."msg` (
  			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  			`user_id` int(10) unsigned NOT NULL DEFAULT '0',
  			`num` int(10) unsigned NOT NULL DEFAULT '0',
  			`functions` varchar(255) DEFAULT NULL,
  			`state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  			PRIMARY KEY (id)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."msg_log` (
  			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  			`user_id` int(10) unsigned NOT NULL DEFAULT '0',
  			`to_mobile` varchar(100) DEFAULT NULL,
  			`content` text DEFAULT NULL,
 			`quantity` tinyint(3) unsigned NOT NULL DEFAULT '0',
  			`state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  			`result` varchar(50) DEFAULT NULL,
  			`type` int(10) unsigned NULL DEFAULT '0',
  			`time` int(10) unsigned DEFAULT NULL,
  			PRIMARY KEY (id)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."msg_setting` (
  			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`msg_pid` varchar(60) NOT NULL DEFAULT '0',
			`msg_key` varchar(50) NOT NULL DEFAULT '0',
			`msg_status` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."msg_statistics` (
  			`user_id` int(10) unsigned NOT NULL DEFAULT '0',
  			`available` int(10) unsigned NOT NULL DEFAULT '0',
  			`used` int(10) unsigned NOT NULL DEFAULT '0',
  			`allocated` int(10) unsigned NOT NULL DEFAULT '0',
  			PRIMARY KEY (`user_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 手机版 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'sgrade');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('wap_skins', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'sgrade` ADD `wap_skins` VARCHAR(255) NOT NULL  AFTER `skins`';
			db()->query($sql);
		}
		if(!in_array('wap_skin_limit', $fields)){
			$sql = "ALTER TABLE `".DB_PREFIX."sgrade` ADD `wap_skin_limit` INT(3) NOT NULL  AFTER `skin_limit`";
			db()->query($sql);
		}
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'store');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('wap_theme', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `wap_theme` VARCHAR(255) NOT NULL  AFTER `theme`';
			db()->query($sql);
		}
		
		/* 新增用户锁定住功能 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'member');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('locked', $fields)){
			$sql = "ALTER TABLE `".DB_PREFIX."member` ADD  `locked` int(1) NOT NULL default 0";
			db()->query($sql);
		}
		
		/* 搭配套餐 */
		$sql = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "meal` (
  			`meal_id` int(11) NOT NULL auto_increment,
  			`user_id` int(11) NOT NULL,
  			`title` varchar(255) NOT NULL,
  			`price` decimal(10,2) NOT NULL,
  			`description` text NOT NULL,
  			`status` int(1) NOT NULL,
  			PRIMARY KEY  (`meal_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "meal_goods` (
  			`mg_id` int(11) NOT NULL auto_increment,
  			`meal_id` int(11) NOT NULL,
  			`goods_id` int(11) NOT NULL,
  			`goods_name` varchar(255) NOT NULL,
  			`sort_order` int(3) NOT NULL,
  			PRIMARY KEY  (`mg_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 应用市场（站内购买后才能使用） */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "appmarket` (
	  		`aid` int(11) NOT NULL AUTO_INCREMENT,
			`appid` varchar(20) NOT NULL,
			`title` varchar(100) NOT NULL,
			`summary` varchar(255) NOT NULL,
			`category` int(11) NOT NULL,
			`description` TEXT NOT NULL,
			`logo` varchar(200) NOT NULL,
	  		`config` TEXT NOT NULL,
			`sales` int(11) NOT NULL DEFAULT '0',
			`views` int(11) NOT NULL DEFAULT '0',
	  		`status` tinyint(1) NOT NULL DEFAULT '0',
			`add_time` int(11) NOT NULL,
	 		 PRIMARY KEY (`aid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 用户购买应用记录表 */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "appbuylog` (
	  		`bid` int(11) NOT NULL AUTO_INCREMENT,
			`orderId` varchar(20) NOT NULL,
			`appid` varchar(20) NOT NULL,
			`user_id` int(11) NOT NULL,
			`period` int(11) NOT NULL,
			`amount` decimal(10,2) NOT NULL,
			`status` tinyint(3) NOT NULL,
			`add_time` int(11) NOT NULL,
			`pay_time` int(11) NOT NULL,
			`end_time` int(11) NOT NULL,
	 		 PRIMARY KEY (`bid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 用户购买应用续期表 */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "apprenewal` (
			`rid` int(11) NOT NULL AUTO_INCREMENT,
			`appid` varchar(20) NOT NULL,
			`user_id` int(11) NOT NULL,
			`add_time` int(11) NOT NULL,
			`expired` int(11) NOT NULL,
	 		 PRIMARY KEY (`rid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
						
		/* 营销工具（卖家设置信息表） */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "promotool_setting` (
	  		`psid` int(11) NOT NULL AUTO_INCREMENT,
			`appid` varchar(20) NOT NULL,
	  		`store_id` int(11) NOT NULL,
	  		`rules` TEXT NOT NULL,
	  		`status` tinyint(1) NOT NULL DEFAULT '0',
			`add_time` int(11) NOT NULL,
	 		 PRIMARY KEY (`psid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 营销工具（商品对应表） */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "promotool_item` (
	  		`piid` int(11) NOT NULL AUTO_INCREMENT,
			`goods_id` int(11) NOT NULL,
			`appid` varchar(20) NOT NULL,
	  		`store_id` int(11) NOT NULL,
	  		`config` TEXT NOT NULL,
	  		`status` int(1) NOT NULL,
			`add_time` int(11) NOT NULL,
	 		 PRIMARY KEY (`piid`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 营销工具（赠品表） */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "gift` (
	  		`goods_id` int(11) NOT NULL AUTO_INCREMENT,
			`goods_name` varchar(100) NOT NULL,
	  		`store_id` int(11) NOT NULL,
			`price` decimal(10,2) NOT NULL,
			`stock` int(11) NOT NULL,
			`default_image` varchar(255) NOT NULL,
	  		`description` TEXT NOT NULL,
	  		`if_show` tinyint(1) NOT NULL DEFAULT '0',
			`add_time` int(11) NOT NULL,
	 		 PRIMARY KEY (`goods_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 订单赠品信息表 */
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "order_gift` (
	  		`rec_id` int(10) NOT NULL AUTO_INCREMENT,
			`order_id` int(10) NOT NULL,
			`goods_id` int(10) NOT NULL,
			`goods_name` varchar(100) NOT NULL,
			`price` decimal(10,2) NOT NULL,
			`quantity` int(11) NOT NULL,
			`default_image` varchar(255) NOT NULL,
	 		 PRIMARY KEY (`rec_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		/* 分销功能 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'store');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}

		if(!in_array('enable_distribution', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `enable_distribution` tinyint(1) DEFAULT NULL';
			db()->query($sql);
		}
		if(!in_array('distribution_1', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `distribution_1` decimal(10,1) DEFAULT NULL';
			db()->query($sql);
		}
		if(!in_array('distribution_2', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `distribution_2` decimal(10,1) DEFAULT NULL';
			db()->query($sql);
		}
		if(!in_array('distribution_3', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'store` ADD `distribution_3` decimal(10,1) DEFAULT NULL';
			db()->query($sql);
		}
		
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "distribution` (
		  `dst_id` int(10) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) unsigned NOT NULL,
		  `parent_id` int(11) unsigned NOT NULL,
		  `store_id` int(11) NOT NULL,
		  `did` int(11) NOT NULL DEFAULT '0',
		  `real_name` varchar(255) DEFAULT NULL,
		  `phone_mob` varchar(20) DEFAULT NULL,
		  `logo` varchar(255) DEFAULT NULL,
		  `add_time` int(10) DEFAULT NULL,
		  PRIMARY KEY (`dst_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "distribution_statistics` (
		  `user_id` int(11) unsigned NOT NULL,
		  `amount` decimal(10,2) unsigned NOT NULL,
		  `layer1` decimal(10,2) DEFAULT NULL,
		  `layer2` decimal(10,2) DEFAULT NULL,
		  `layer3` decimal(10,2) DEFAULT NULL,
		  PRIMARY KEY (`user_id`)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'order');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('did', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `did` int(11) DEFAULT NULL';
			db()->query($sql);
		}
		if(!in_array('distribution_rate', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'order` ADD `distribution_rate` TEXT';
			db()->query($sql);
		}
		/* 分销END */
		
		/* 合并付款 */
		$result = db()->getAll('SHOW COLUMNS FROM '. DB_PREFIX . 'cart');
		$fields = array();
		foreach($result as $v) {
			$fields[] = $v['Field'];
		}
		if(!in_array('selected', $fields)){
			$sql = 'ALTER TABLE `'.DB_PREFIX.'cart` ADD `selected` tinyint(1) UNSIGNED NOT NULL default 0';
			db()->query($sql);
		}
		// 微商城配置
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "weixin_config` (
	  		id int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `name` varchar(100) DEFAULT NULL,
			  `token` varchar(255) NOT NULL,
			  `appid` varchar(255) DEFAULT NULL,
			  `appsecret` varchar(255) DEFAULT NULL,
			  `if_valid` tinyint(1) unsigned DEFAULT '0',
			  PRIMARY KEY (id)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "weixin_menu` (
	  		id int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(10) unsigned DEFAULT NULL,
			  `parent_id` int(10) DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  `type` varchar(20) DEFAULT NULL,
			  `add_time` int(10) DEFAULT NULL,
			  `sort_order` tinyint(3) unsigned DEFAULT NULL,
			  `link` varchar(255) DEFAULT NULL,
			  `reply_id` int(10) DEFAULT NULL,
			  PRIMARY KEY (id)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
		$sql =" CREATE TABLE IF NOT EXISTS `". DB_PREFIX . "weixin_reply` (
	  		`reply_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		    `user_id` int(11) NOT NULL,
		  `type` tinyint(1) unsigned NOT NULL COMMENT '回复类型0文字1图文',
		  `action` varchar(20) DEFAULT NULL COMMENT '回复命令 关注、消息、关键字',
		  `title` varchar(255) DEFAULT NULL,
		  `link` varchar(50) DEFAULT NULL,
		  `image` varchar(100) DEFAULT NULL,
		  `rule_name` varchar(255) DEFAULT NULL,
		  `keywords` varchar(255) DEFAULT NULL,
		  `content` text,
		  `add_time` int(10) DEFAULT NULL,
		  PRIMARY KEY (reply_id)
		) ENGINE = MYISAM DEFAULT CHARSET=".str_replace('-','',CHARSET).";";
		db()->query($sql);
	
	}
	
	function check_view_device($path = 'mobile', $redirect = TRUE)
	{
		if (defined('IN_BACKEND') && IN_BACKEND === true) {
			return;
		}
		
		$result = FALSE;
		
		if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			$result = TRUE;
		}
		if(isset ($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
			//找不到为flase,否则为true
			$result = TRUE;
		}
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			//此数组有待完善
			$clientkeywords = array (
			'nokia',
			'sony',
			'ericsson',
			'mot',
			'samsung',
			'htc',
			'sgh',
			'lg',
			'sharp',
			'sie-',
			'philips',
			'panasonic',
			'alcatel',
			'lenovo',
			'iphone',
			'ipod',
			'blackberry',
			'meizu',
			'android',
			'netfront',
			'symbian',
			'ucweb',
			'windowsce',
			'palm',
			'operamini',
			'operamobi',
			'openwave',
			'nexusone',
			'cldc',
			'midp',
			'wap',
			'mobile'
			);
			if(preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				$result = TRUE;
			}
	 
		}
	 
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
				$result = TRUE;
			}
		}
		
		//  如果是判断客户端后执行跳转
		if($redirect === TRUE)
		{
			$query_string = '';
			if(!empty($_SERVER['QUERY_STRING']))
			{
				$queryArray = explode('&', $_SERVER['QUERY_STRING']);
				foreach($queryArray as $key => $val)
				{
					if(in_array(strtolower($val), array('device=pc', 'device=wap'))) {
						unset($queryArray[$key]);
					}
				}
				$queryArray && $query_string = '?'. implode('&', $queryArray);
			}
			
			$redirect_uri = SITE_URL . "/" . $path;
			if($query_string){
				$redirect_uri .= "/index.php" . $query_string;
			}
		
			//  是手机端
			if($result === TRUE)
			{
				// 如果是手动从手机端跳转到PC端
				if(strtoupper(trim($_GET['device'])) == 'PC' || (strtoupper(trim($_GET['device'])) != 'WAP' && $_SESSION['device'] == 'PC'))
				{
					$_SESSION['device'] = 'PC';
					if(strripos($_SERVER['REQUEST_URI'], '/'.$path) !== FALSE) {
						//header("Location:" . str_replace('/mobile', '', $redirect_uri));
						//exit;
					}
				}
				elseif(strtoupper(trim($_GET['device'])) == 'WAP' || (strtoupper(trim($_GET['device'])) != 'PC' && $_SESSION['device'] == 'WAP'))
				{
					$_SESSION['device'] = 'WAP';
					if(strripos($_SERVER['REQUEST_URI'], '/'.$path) === FALSE) {
						header("Location:" . $redirect_uri);
						exit;
					}
				}
				//  如果没有手动跳转，也没有执行过手动跳转的操作，则自动跳转到手机端
				else
				{
					if(strripos($_SERVER['REQUEST_URI'], '/'.$path) === FALSE) {
						header("Location:" . $redirect_uri);
						exit;
					}
				}
			}
			// 是PC端
			else
			{
				// 如果是手动从PC端跳转到手机端
				if(isset($_GET['device']) && (strtoupper(trim($_GET['device'])) == 'WAP' || (strtoupper(trim($_GET['device'])) != 'PC' && ($_SESSION['device'] == 'WAP'))))
				{
					$_SESSION['device'] = 'WAP';
					if(strripos($_SERVER['REQUEST_URI'], '/'.$path) === FALSE) {
						header("Location:" . $redirect_uri);
						exit;
					}
				}
			}
		}
		//  如果不跳转，仅仅是判断是否为手机端
		else
		{
			return $result;
		}
	}
	
	//判断该店铺模板是否可以编辑。不可编辑则不显示编辑按钮
	function check_template_editable($themes,$mobile=false)
	{
		$type = $mobile ? '/mobile' : '';
		foreach($themes as $key=>$theme)
		{
			$file = ROOT_PATH.$type.'/themes/store/'.$theme['template_name'].'/form.info.php';
			if(is_file($file) && file_exists($file))
			{
				$themes[$key]['editable'] = 1;
			}
		}
		return $themes;
	}
	
	// 判断后台是否启用快递跟踪插件 psmb
	function _check_express_plugin()
	{
		$plugin_inc_file = ROOT_PATH . '/data/plugins.inc.php';
        if (is_file($plugin_inc_file))
        {
            $plugins =  include($plugin_inc_file);
			return isset($plugins['on_query_express']['kuaidi100']);
        }

        return false;
	}
	
	function deal_config_data($model,$data)
	{
		$result = array();
		switch($model)
		{
			case 'image':
			$count = count($data)/2;
			for($i=1;$i<=$count;$i++)
			{
				$result[$i]['ad_image_url'] = $data['ad'.$i.'_image_url'];
				$result[$i]['ad_link_url'] = $data['ad'.$i.'_link_url'];
			}
			if($count == 1) $result = current($result);
			break;
			
			case 'im':
			$im = array();
			$im1 = explode('@',current($data));
			foreach($im1 as $key=>$val)
			{
				$im2 = explode(' ',$val);
				foreach($im2 as $k=>$v)
				{
					$im3 = explode(',',$v);
					$im[$key][$k]['number'] = $im3[0];
					$im[$key][$k]['name'] = $im3[1];
				}
			}
			$result['qq'] = $im[0];
			$result['wangwang'] = $im[1];
			break;
			
			case 'keywords':
			$result = explode(' ',current($data));
			break;
			
			case 'floor':
			$result['model_name'] = $data['model_name'];
			$title = explode(' ',$data['keywords']);
			$link = explode(' ',$data['link']);
			if(count($title) > 0)
			{
				for($k=0;$k<count($title);$k++)
				{
					$result['keywords'][$k] = array('title'=>$title[$k],'link'=>$link[$k]);
				}
			}
			for($i=1;$i<=3;$i++)
			{
				$result['ads'][$i]['ad_image_url'] = $data['ad'.$i.'_image_url'];
				$result['ads'][$i]['ad_link_url'] = $data['ad'.$i.'_link_url'];
			}
			if($data['time'])
			{
				$time = strtotime($data['time']);
				$result['lefttime'] = $this->lefttime($time);
			}
			break;
			
			case 'goods_list':
			$amount = $data['amount']?$data['amount']:10;
			$recom_mod =& m('recommend');
            $goods_list = $recom_mod->get_recommended_goods($data['recommand_id'],$amount, true, '');
			$result = array_chunk($goods_list,2);
			break;
			
			default:
			$result = current($data);
		}
		return $result;
	}
	
	/**
     *    以购物车为单位获取购物车列表及商品项
     */
	function get_carts_top($sess_id, $user_id = 0)
    {
		$where_user_id = $user_id ? " AND user_id={$user_id}" : '';
		
        $cart_items = array();
		$total_count=0;
		$total_amount=0;
		
        $cart_model =& m('cart');
        $cart_items = $cart_model->find(array(
            'conditions'    => 'session_id = ' . "'"  .$sess_id . "'" . $where_user_id,
			'fields'        => '',
        ));
		
		foreach($cart_items as $key=>$val){
			$total_count += $val['quantity'];
			$total_amount += round($val['price'] * $val['quantity'],2);
		}
		
        return array('cart_items' => $cart_items, 'total_count' => $total_count, 'total_amount' => $total_amount);
    }
	
	/* 所有商品类目，头部通用 psmb */
	function get_header_gcategories($amount, $position, $brand_is_recommend=1)
	{
		$gcategory_mod =& bm('gcategory', array('_store_id' => 0));
		$gcategories = array();
		if(!$amount)
		{
			$gcategories = $gcategory_mod->get_list(-1, true);
		}
		else
		{
			$gcategory = $gcategory_mod->get_list(0, true);
			$gcategories = $gcategory;
			foreach ($gcategory as $val)
			{
				$result = $gcategory_mod->get_list($val['cate_id'], true);
				$result = array_slice($result, 0, $amount);
				$gcategories = array_merge($gcategories, $result);
			}
		}

		import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
		$gcategory_list = $tree->getArrayList(0);
		
		$i=0;
		$brand_mod=&m('brand');
		$uploadedfile_mod = &m('uploadedfile');	
		foreach($gcategory_list as $k => $v) {
			$gcategory_list[$k]['top']  =  isset($position[$i]) ? $position[$i] : '0px';
			$i++;
			
			$gcategory_list[$k]['brands'] = $brand_mod->find(array(
				'conditions'=>"tag = '".$v['value']."' AND recommended=".$brand_is_recommend, 
				'order'=>'sort_order asc,brand_id desc'
			));
			$gcategory_list[$k]['gads'] = $uploadedfile_mod->find(array(
					'conditions' => 'store_id = 0 AND belong = ' . BELONG_GCATEGORY . ' AND item_id=' . $v['id'],
					'fields' => 'this.file_id, this.file_name, this.file_path,this.link_url',
					'order' => 'add_time DESC'
			));
		}
		
		return array('gcategories'=>$gcategory_list);
	}
	/* 屏蔽掉当前筛选的品牌 */
	function get_group_by_info_by_brands($by_brands=array(),$param)
	{
		if(!empty($param["brand"])) {
			unset($by_brands[$param['brand']]);
		}
		$brand_mod = &m('brand');
		foreach($by_brands as $key => $val)
		{
			$brand = $brand_mod->get(array('conditions'=>"brand_name='" . addslashes_deep($val['brand']) . "'",'fields'=>'brand_logo'));	
			$by_brands[$key]['brand_logo'] = $brand['brand_logo'];
		}
		return $by_brands;
	}
	/* 屏蔽掉当前筛选的地区 */
	function get_group_by_info_by_region($sql,$param)
	{
		$goods_mod = &m('goods');
		$by_regions = $goods_mod->getAll($sql);
		if(!empty($param["region_id"])){
			foreach($by_regions as $k => $v){
				if($v["region_id"]==$param["region_id"]){
					unset($by_regions[$k]);
				}
			}
		}
		return $by_regions;
	} 
	
	function get_ultimate_store($conditions, $brand)
	{
		$store = array();
		$us_mod = &m('ultimate_store');
		$store_mod = &m('store');
		
		$ultimate_store = $us_mod->get(array('conditions'=>'status=1 ' . $conditions,'fields'=>'store_id,description'));

		if($ultimate_store)
		{
			$store = $store_mod->get(array('conditions'=>'store_id='.$ultimate_store['store_id'],'fields'=>'store_logo,store_name'));
			empty($store['store_logo']) && $store['store_logo'] = Conf::get('default_store_logo');
			
			if($brand && !empty($brand['brand_logo'])) {
				$store['store_logo'] = $brand['brand_logo'];
			}
			$store = array(array_merge($ultimate_store,$store));	
		}

		return $store;
	}
	function get_available_coupon($order = array(), $user_id = 0)
	{
		$time = gmtime();
		$coupon = db()->getAll("SELECT *FROM ".DB_PREFIX."coupon_sn couponsn ".
			"LEFT JOIN ".DB_PREFIX."coupon coupon ON couponsn.coupon_id=coupon.coupon_id ".
			"LEFT JOIN ".DB_PREFIX."user_coupon user_coupon ON user_coupon.coupon_sn=couponsn.coupon_sn ".
			"WHERE coupon.store_id = ".$order['store_id'] ." AND couponsn.remain_times >=1 ".
			"AND user_coupon.user_id=".$user_id." ".
			"AND coupon.start_time <= ".$time ." AND coupon.end_time >= ".$time ." AND coupon.min_amount <= ".$order['amount']	
		);
		return $coupon;
	}
	/**
	***by psmoban.com 获取行业的平均值
	**/
	function get_industry_avg_evaluation($store_id)
	{
		$store_mod=&m('store');
		$store_data=$store_mod->get(array(
			'conditions'=>'s.store_id='.$store_id,
			'join'      =>'has_scategory',
		));
		if($store_data['cate_id'] > 0)
		{
			$scategory_mod =& m('scategory');
			$condition=" AND cate_id  ".db_create_in($scategory_mod->get_descendant($store_data['cate_id']))." ";
		}
		$data=$store_mod->find(array(
            'conditions'=> "state = 1 AND avg_shipped_evaluation > 0 AND avg_service_evaluation > 0 AND avg_goods_evaluation > 0 ".$condition,
			'join'      => 'has_scategory',
            'fields'    => 'avg_goods_evaluation,avg_service_evaluation,avg_shipped_evaluation',
        ));
		$result= array();
		$result['total_count'] = $result['total_avg_gevaluation'] = $result['total_avg_shevaluation'] = $result['total_avg_sevaluation'] = 0;
		if(!empty($data))
		{
			$result['total_count'] = count($data);
			foreach($data as $key=>$val)
			{
				$result['total_avg_gevaluation'] = $result['total_avg_gevaluation']+$val['avg_goods_evaluation'];
				$result['total_avg_shevaluation'] = $result['total_avg_shevaluation']+$val['avg_shipped_evaluation'];
				$result['total_avg_sevaluation'] = $result['total_avg_sevaluation']+$val['avg_service_evaluation'];
			}
		}
		return $this->calculate_evaluation($result,$store_data);
	}
	function calculate_evaluation($industy_data,$store_data)
	{
		$industy_avgs=array();
		if($industy_data['total_count'] > 0)
		{
			//行业均值
			$industy_avgs_goods=$industy_data['total_avg_gevaluation']/$industy_data['total_count'];
			$industy_avgs_service=$industy_data['total_avg_sevaluation']/$industy_data['total_count'];
			$industy_avgs_shipped=$industy_data['total_avg_shevaluation']/$industy_data['total_count'];
			
			//本店与行业均值比较
			$goods_compare=round(($store_data['avg_goods_evaluation']-$industy_avgs_goods)/$industy_avgs_goods,4)*100;
			$service_compare=round(($store_data['avg_service_evaluation']-$industy_avgs_service)/$industy_avgs_service,4)*100;
			$shipped_compare=round(($store_data['avg_shipped_evaluation']-$industy_avgs_shipped)/$industy_avgs_shipped,4)*100;
		}
		$industy_avgs['goods_compare']=$this->attribute_class($goods_compare);
		$industy_avgs['service_compare']=$this->attribute_class($service_compare);
		$industy_avgs['shipped_compare']=$this->attribute_class($shipped_compare);
		return $industy_avgs;
	}
	function attribute_class($value)
	{
		$class='';
		$name='';
		if($value > 0)
		{
			$class='high';
			$name=Lang::get('high');
		}
		elseif($value < 0)
		{
			$class='low';
			$value=abs($value);
			$name=Lang::get('low');
		}
		else
		{
			$class='equal';	
			$name=Lang::get('equal');
		}
		return array('value'=>$value,'class'=>$class,'name'=>$name);
	}
	/*更新店铺动态评分值 by psmoban.com*/
	function update_dynamic_evaluation($type = 'goods_evaluation',$store_id)
	{
        $ordergoods_mod =& m('ordergoods'); 
        $info  = $ordergoods_mod->find(array(
            'join'          => 'belongs_to_order',
            'conditions'    => "seller_id={$store_id} AND evaluation_status=1 AND is_valid=1",
            'fields'        => $type,
        ));
		$order_count = count($info);
		$total_evaluation = 0;
		if(!empty($info))
		{
			foreach($info as $key=>$val)
			{
				$total_evaluation = $total_evaluation + $val[$type];
			}
		}
		$order_count > 0 && $avg_evaluation=round($total_evaluation/$order_count,2);
		
		return $avg_evaluation ? $avg_evaluation : 0;
	}
	function get_order_relative_info($goods_id,$condition,$count=false,$limit='')
	{
		$order_mod=&m('order');
		$member_mod=&m('member');
		$ordergoods_mod=&m('ordergoods');
		if($limit)
		{
			$lm=" LIMIT ".$limit;
			
		}
		$comments=$ordergoods_mod->getAll("SELECT buyer_id, buyer_name, anonymous, evaluation_time, comment, evaluation,goods_evaluation,reply_content,reply_time,portrait FROM {$ordergoods_mod->table} AS og LEFT JOIN {$order_mod->table} AS ord ON og.order_id=ord.order_id LEFT JOIN {$member_mod->table} AS m ON ord.buyer_id=m.user_id WHERE goods_id = '$goods_id' AND evaluation_status = '1'".$condition." ORDER BY evaluation_time desc ".$lm);
		if($count)
		{
			return count($comments);
		}
		else
		{
			return $comments;
		}
	}
	function Jd_widget_get_goods_list($options,$num = 1,$amount = 10)
	{
		$goods_list=array();
		$recom_mod = &m('recommend');
		for($i=1;$i <= $num;$i++)
		{
			$goods_list[$i] = $recom_mod->get_recommended_goods($options['img_recom_id_'.$i],$amount, true, $options['img_cate_id_'.$i],array(),$options['sort_by_'.$i]);
		}
		return $goods_list;
	}
	function Jd_widget_get_tabs_goods($tabs=array(),$num = 10)
	{
		if(empty($tabs))
		{
			return;
		}
		$goods_list=array();
		$recom_mod = &m('recommend');
		foreach($tabs as $key => $tab)
		{
			$goods_list[$key]['tab_name'] = $tab['tab_name'];
			$goods_list[$key]['goods'] = $recom_mod->get_recommended_goods($tab['img_recom_id'],$num,true,$tab['img_cate_id'],array(),$tab['sort_by']);
		}
		return $goods_list;
	}
	
	function Jd_widget_get_ads($options,$num=6)
	{
		$ads = array();
		$slides_pos = $options['slides_pos'] && in_array($options['slides_pos'],array(1,2,3,4))?$options['slides_pos']:2;
		for($i=1;$i<=$num;$i++)
		{
			$ads[$i]['ad_image_url']=$options['ad'.$i.'_image_url'];
			$ads[$i]['ad_link_url']=$options['ad'.$i.'_link_url'];
			if($slides_pos == $i || $slides_pos+3 == $i)
			{
				$ads[$i]['pos'] = 1;
			}
		}
		return $ads;
	}
	
	function Jd_widget_get_words($words_str='')
	{
		if(empty($words_str))
		{
			return;
		}
		$data =array();
		$words = explode(';',str_replace('；',';',$words_str));
		foreach($words as $key => $word)
		{
			$temp = explode('|',$word);
			$data[$key] = array('name'=>$temp[0],'link'=>$temp[1]);
		}
		return $data;
	}

	function Jd_widget_get_brand_list($tag,$amount = 10)
	{
		$amount = !empty($amount) ? intval($amount):10;
		$brand_list=array();
		$brand_mod=&m('brand');
		$tag && $conditions="tag= '".$tag."' AND ";
		$brand_list=$brand_mod->find(array('conditions'=>$conditions.'  if_show = 1 AND recommended= 1 ','limit'=>$amount));
		return $brand_list;
	}
	function Jd_article_get_data($options)
	{
		$acategory_mod = &m('acategory');
		$cate_ids = $acategory_mod->get_descendant($options['cate_id']);
		if($cate_ids){
			$conditions = ' AND cate_id ' . db_create_in($cate_ids);
		} else {
			$conditions = '';
		}
		return $conditions;
	}
	function Jd_share_get_comment()
	{
		$order_mod = &m('order');
		$ordergoods=&m('ordergoods');
		$goods_list=$ordergoods->find(array(
			'conditions'=>"comment != '' ",
			'limit'     =>10,
			'order'     =>'order_id desc',
			'fields' => 'order_id,goods_id,goods_name,comment,goods_image'
		));
		if($goods_list)
		{
			foreach($goods_list as $key=>$val)
			{	
				empty($val['goods_image']) && $goods_list[$key]['goods_image'] = Conf::get('default_goods_image');
				$order_info = $order_mod->get(array(
					'conditions' => $val['order_id'],
					'join' => 'belongs_to_user',
					'fields' => 'buyer_id,buyer_name,portrait', 
				));
				$goods_list[$key]['buyer_name'] = $order_info['buyer_name'];
				$goods_list[$key]['portrait'] = portrait($val['buyer_id'], $order_info['portrait'], 'middle');;
			}
		}		
		return $goods_list;
	}
	
	function dpt($flow, $type, $params = array(), $is_new = false)
	{
		static $depopay_type = array();
    	$hash = md5($flow . $type . var_export($params, true));
    	if ($is_new || empty($depopay_type) || !isset($depopay_type[$hash]))
    	{
			/* 加载预存款支付基础类 */
			$base_file = ROOT_PATH . '/includes/depopay.base.php';
			$flow_file = ROOT_PATH . '/includes/depopaytypes/'. $flow .'.depopay.php';
			$type_file = ROOT_PATH . '/includes/depopaytypes/'.$type . '.'.$flow.'.php';
			if(!is_file($base_file) || !is_file($flow_file) || !is_file($type_file)) {
				return false;
			}
		
			include_once($base_file);
			include_once($flow_file);
			include_once($type_file);
		
			$class_name = ucfirst($type).ucfirst($flow);
		
			$depopay_type[$hash] =  new $class_name($params);
		}

		return $depopay_type[$hash];
	}

	function get_order_adjust_rate($order_info)
	{
		$goods_amount_after_adjust = $order_info['goods_amount']; // 订单表里面的商品总额是已经调价后的总额
		$goods_amount_before_adjust = $adjust_fee = 0;
		
		$ordergoods_mod = &m('ordergoods');
		$ordergoods = $ordergoods_mod->find(array('conditions'=>"order_id=".$order_info['order_id'],'fields'=>'price,quantity'));
		foreach($ordergoods as $goods){
			$goods_amount_before_adjust += $goods['price'] * $goods['quantity'];
		}
		$adjust_fee = $goods_amount_before_adjust - $goods_amount_after_adjust; //  调高为负值，调低为正值

		if($adjust_fee !=0){ // 如果不相等，则说明卖家在买家付款前，调整过价格
			if($goods_amount_before_adjust >0) { 
				$adjust_rate = 1 - round($adjust_fee / $goods_amount_before_adjust, 6); // 小数点不能为 2，影响精度
			}
			else $adjust_rate = -1;
		} 
		else {
			$adjust_rate = 1;
		}
		
		return $adjust_rate;
	}
	
	/* 退款后的积分返还 */
	function _handle_order_integral_return($order_info, $refund)
	{
		$integral_mod = &m('integral');
		$order_integral_mod = &m('order_integral');
		
		/* 查看本次订单是否使用了积分抵扣 */
		if($order_integral = $order_integral_mod->get($order_info['order_id'])) {
			if($order_integral['frozen_integral'] > 0) {
					
				/* 如果是商品全额退款（按订单全额退款来判断的话，感觉有弊端）， 那么积分全部退回给买家, 不增加积分收入记录，只是解除积分冻结 */
				if($refund['goods_fee'] == $refund['refund_goods_fee']) {
						
					$integral_mod->return_integral($order_info);
				}
				else
				{	
					/* 如果不是商品全额退款，那么积分全部付给卖家，并增加买家积分支出记录和变更卖家积分总额及增加卖家积分收入记录 */
					$integral_mod->distribute_integral($order_info);
				}
			}
		}
	}
	
	function DepositApp_downloadbill($month)
	{
		/* 获取指定日期的开始时间戳 */
		$month_times = gmstr2time($month);
		
		/* 指定的月份有多少天 */
		$monthdays 	= local_date("t",$month_times);
		
		/* 请求的日期是该月的第几天 */
		$dayInMonth = local_date("j", $month_times);
		
		$begin_month	= $month_times - ($dayInMonth-1) * 24 * 3600;
		$end_month		= $month_times + ($monthdays-$dayInMonth) * 24 * 3600;
		
		return array($begin_month, $end_month);
	}
	
	function Delivery_templateModel_format_template($region_mod, $delivery_template,$need_dest_ids=false)
	{
		if(!is_array($delivery_template)){
			return array();
		}
		
		$data = $deliverys = array();

		foreach($delivery_template as $template)
		{
			$data = array();
			$data['template_id'] = $template['template_id'];
			$data['name'] = $template['name'];
			$data['created'] = $template['created'];
			$data['store_id'] = $template['store_id'];
			
			$template_types = explode(';', $template['template_types']);
			$template_dests = explode(';', $template['template_dests']);
			$template_start_standards = explode(';', $template['template_start_standards']);
			$template_start_fees = explode(';', $template['template_start_fees']);
			$template_add_standards = explode(';', $template['template_add_standards']);
			$template_add_fees = explode(';', $template['template_add_fees']);
			
			$i=0;
			foreach($template_types as $key=>$type)
			{
				$dests = explode(',',$template_dests[$key]);
				$start_standards = explode(',', $template_start_standards[$key]);
				$start_fees = explode(',', $template_start_fees[$key]);
				$add_standards = explode(',', $template_add_standards[$key]);
				$add_fees = explode(',', $template_add_fees[$key]);
				
				foreach($dests as $k=>$v)
				{
					$data['area_fee'][$i] = array(
						'type'=> $type,
						'dests'=>$region_mod->get_region_name($v),
						'start_standards'=> $start_standards[$k],
						'start_fees'	 => $start_fees[$k],
						'add_standards'  => $add_standards[$k],
						'add_fees'		 => $add_fees[$k]
					);
					if($need_dest_ids){
						$data['area_fee'][$i]['dest_ids'] = $v;
					}
					$i++;
				}
			}
			$deliverys[] = $data;	
		}
		return $deliverys;
	}
	
	function Delivery_templateModel_format_template_foredit($delivery_template, $region_mod)
	{
		$data[] = $delivery_template;
		$delivery = $this->Delivery_templateModel_format_template($region_mod, $data,true);
		$delivery = current($delivery);
		
		$area_fee_list = array();
		foreach($delivery['area_fee'] as $key=>$val)
		{
			$type = $val['type'];
			$area_fee_list[$type][] = $val;
		}
		$delivery['area_fee'] = $area_fee_list;
		
		foreach($delivery['area_fee'] as $key=>$val)
		{
			$default_fee=true;
			foreach($val as $k=>$v){
				if($default_fee){
					$delivery['area_fee'][$key]['default_fee'] = $v;
					$default_fee=false;
				} else {
					$delivery['area_fee'][$key]['other_fee'][] = $v;
				}
				unset($delivery['area_fee'][$key][$k]);
			}
		}

		return $delivery;
	}
	function lefttime($time)
    {
        $lefttime = $time - gmtime();
	
		if(empty($time) || $lefttime <=0) return array();
	
        $d = intval($lefttime / 86400);
        $lefttime -= $d * 86400;
        $h = intval($lefttime / 3600);
        $lefttime -= $h * 3600;
        $m = intval($lefttime / 60);
        $lefttime -= $m * 60;
        $s = $lefttime;
         
        return array('d'=> $d,'h'=>$h,'m'=>$m,'s'=>$s);
   }
   
	/* 获取卖家设置的营销工具中的每一项配置的值 */
	function getRulesItem($goods_id = 0, $appid = 'fullgift')
	{
		$item = array();
		if($appid == 'fullgift') {
			$gift_mod = &m('gift');	
			if($item = $gift_mod->get(
				array('conditions' => 'goods_id = ' . $goods_id, 'fields' => 'goods_name,price,default_image,if_show'))){
					$item['available'] = $item['if_show'] ? TRUE : FALSE;
			}
		}
		else
		{
			$goods_mod = &m('goods');
			$item = $goods_mod->get(
				array('conditions' => 'goods_id = ' . $goods_id, 'fields' => 'goods_name, price, default_spec,default_image,if_show, closed'));
					$item['available'] = ($item['if_show'] && !$item['closed']) ? TRUE : FALSE;
		}
		return $item;
	}
	
	/* 检查卖家设置的该营销工具是否可用，并且还在购买期限内 */
	function checkAvailable($store_id, $appid)
	{
		$result = FALSE;
		
		/* 平台是否配置了营销工具信息 */
		$appmarket_mod = &m('appmarket');
		if($appmarket_mod->checkAvailable($appid))
		{
			/* 在此处判断用户是否购买了该营销工具 */
			$apprenewal_mod = &m('apprenewal');
			$apprenewal = $apprenewal_mod->get(array('conditions'=>'appid="'.$appid.'" AND user_id='. $store_id, 'order'=>'rid DESC'));
			
			/* 如果购买了，那么检查是否到期 */
			if($apprenewal && ($apprenewal['expired'] > gmtime())) {
	
				/* 没有到期，则检查卖家是否启用/配置了该营销工具 */
				$promotool_setting_mod = &bm('promotool_setting', array('_store_id' => $store_id, '_appid' => $appid));
				if($promotool_setting_mod->get(array('conditions'=>'status = 1 AND appid="' . $appid . '" AND store_id=' . $store_id, 'fields'=> 'psid'))){
					$result = TRUE;
				}
			}
		}
		
		return $result;
	}
	
}

?>