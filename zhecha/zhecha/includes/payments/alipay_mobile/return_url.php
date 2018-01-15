<?php

define('ROOT_PATH', dirname(dirname(dirname(dirname(__FILE__)))));

include(ROOT_PATH . '/eccore/ecmall.php');
ecm_define(ROOT_PATH . '/data/config.inc.php');

//require_once("lib/alipay_submit.class.php");
//$params = createLinkstringUrlencode($_GET);

// 外部交易号
$payTradeNo = html_script($_GET['out_trade_no']);

$url = SITE_URL . '/mobile/index.php?app=paynotify&payTradeNo='.$payTradeNo;
header('Location:'.$url);

?>