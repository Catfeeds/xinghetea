<?php
define('ROOT_PATH', dirname(dirname(dirname(dirname(__FILE__)))));

include (ROOT_PATH . '/eccore/ecmall.php');
ecm_define(ROOT_PATH . '/data/config.inc.php');
// require(ROOT_PATH . '/includes/ecapp.base.php');

// 此参数在ECAPP中定义，因没有引入，所以在此定义，如果不用到模型（model），可以不定义
if (! defined('CHARSET')) {
    define('CHARSET', substr(LANG, 3));
}

require (ROOT_PATH . '/eccore/model/model.base.php'); // 模型基础类
require (ROOT_PATH . '/includes/payment.base.php'); // 支付模型基础类
addLog('heepayN', $_REQUEST);

$result = $_GET['result'];
$pay_message = $_GET['pay_message'];
$agent_id = $_GET['agent_id'];
$jnet_bill_no = $_GET['jnet_bill_no'];
$agent_bill_id = $_GET['agent_bill_id'];
$pay_type = $_GET['pay_type'];

$pay_amt = $_GET['pay_amt'];
$remark = $_GET['remark'];

$returnSign = $_GET['sign'];
// 商户的KEY
$key = '4B05A95416DB4184ACEE4313';

$signStr = '';
$signStr = $signStr . 'result=' . $result;
$signStr = $signStr . '&agent_id=' . $agent_id;
$signStr = $signStr . '&jnet_bill_no=' . $jnet_bill_no;
$signStr = $signStr . '&agent_bill_id=' . $agent_bill_id;
$signStr = $signStr . '&pay_type=' . $pay_type;

$signStr = $signStr . '&pay_amt=' . $pay_amt;
$signStr = $signStr . '&remark=' . $remark;

$signStr = $signStr . '&key=' . $key;

$sign = '';
$sign = md5($signStr);


// 外部交易号
$payTradeNo = substr($agent_bill_id,0,20);
addLog('heepayN', $payTradeNo);
addLog('heepayN', $returnSign);
if($result == 1 && $sign==$returnSign){
    if ($payTradeNo) {
        $sendNotify = FALSE;
        $deposit_trade_mod = &m('deposit_trade');
    
        // 检索出最后支付的单纯充值或购物（或购买应用）订单，如果最后一笔是支付成功的，那么认为都是支付成功了
        $tradeInfo = $deposit_trade_mod->get(array(
            'conditions' => "payTradeNo='{$payTradeNo}'",
            'fields' => 'status',
            'order' => 'trade_id DESC'
                ));
    
        if (empty($tradeInfo)) {
            // 由于支付变更，通过商户交易号找不到对应的交易记录后，插入的资金退回记录
            $tradeInfo = $deposit_trade_mod->get(array(
                'conditions' => "tradeNo='{$payTradeNo}' AND status='SUCCESS' ",
                'fields' => 'trade_id',
                'order' => 'trade_id DESC'
                    ));
    
            if (empty($tradeInfo)) {
                $sendNotify = TRUE;
            }
        } elseif (in_array($tradeInfo['status'], array(
            'PENDING',
            'SUBMITTED'
        ))) {
            $sendNotify = TRUE;
        }
        addLog('heepayN', $sendNotify,'sendNotify');
    
        if ($sendNotify === TRUE) {
            $url = SITE_URL . "/index.php?app=paynotify&act=notify&payTradeNo={$payTradeNo}";
    
            // 输出处理结果给支付网关
            addLog('heepayN', $url,'输出处理结果给支付网关');
            echo ecm_curl($url, 'POST', $payTradeNo);
        } else {
            // 可能不需要了
            echo 'ok';
        }
    }
   
}

?>