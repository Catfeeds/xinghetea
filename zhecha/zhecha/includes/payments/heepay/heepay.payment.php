<?php

/**
 *    汇付宝支付插件
 *
 *    @author   PwordC
 *    @usage    none
 */

class HeepayPayment extends BasePayment
{
	var $_gateway 	= 	'https://pay.heepay.com/Payment/Index.aspx';
	//var $_gateway 	= 	'http://58.56.23.89:7002/NetPay/BankSelect.action';//测试网关地址
    var $_code      =   'heepay';

    /**
     *    获取支付表单
     *
     *    @author    ShopWind
     *    @param     array $tradeInfo  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform(&$orderInfo = array())
    {
		$params = array();
		// 支付网关商户订单号
		$payTradeNo = $this->_get_trade_sn($orderInfo);
		// 给其他页面使用
		foreach($orderInfo['tradeList'] as $key => $val) {
			$orderInfo['tradeList'][$key]['payTradeNo'] = $payTradeNo;
		}
		
		
		//获取所需参数
		//获取ip
		$user_ip = "";
		if(isset($_SERVER['HTTP_CLIENT_IP']))
		{
		    $user_ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
		    $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		    $user_ip = $_SERVER['REMOTE_ADDR'];
		}
		$user_ip = str_replace('.', '_', $user_ip);
		$pay_url = $this->_gateway;
		//$pay_url = "http://www.kzdd100.com/heepay/test.php";
		//$key = strtolower("4B05A95416DB4184ACEE4313");//商户MD5签名密钥
		
		
		$key = $this->_config['datakey'];
		$agent_id = $this->_config['merchantId'];//商户编码
		$version = 1;
		$agent_bill_id = $payTradeNo.date('is',time());//保证每次请求单号唯一
		$agent_bill_time = date('YmdHis', time());
		$pay_type = 20;
		$pay_code = 0;
		$pay_amt = strval($orderInfo['amount']);
		$notify_url = $this->_create_notify_url($payTradeNo);
		$return_url = "http://test.xinghetea.com/index.php?app=depopay&act=heepay_success";
// 		$goods_name_coding =  mb_detect_encoding($orderInfo['title'], array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));		
// 		$goods_name = iconv($goods_name_coding,"GB2312//IGNORE",$orderInfo['title']);
		$goods_name = urlencode($orderInfo['title']);
		//$goods_name = '%b2%e2%ca%d4';
		$remark = '';
		
		
		$sign_str = '';
		$sign_str  = $sign_str . 'version=' . $version;
		$sign_str  = $sign_str . '&agent_id=' . $agent_id;
		$sign_str  = $sign_str . '&agent_bill_id=' . $agent_bill_id;
		$sign_str  = $sign_str . '&agent_bill_time=' . $agent_bill_time;
		$sign_str  = $sign_str . '&pay_type=' . $pay_type;
		$sign_str  = $sign_str . '&pay_amt=' . $pay_amt;
		$sign_str  = $sign_str .  '&notify_url=' . $notify_url;
		$sign_str  = $sign_str . '&return_url=' . $return_url;
		$sign_str  = $sign_str . '&user_ip=' . $user_ip;
		$sign_str = $sign_str . '&key=' . $key;
		
		$sign = md5($sign_str); //计算签名值
		//$url = $pay_url."?version=".$version."&agent_id=".$agent_id."&agent_bill_id=".$agent_bill_id."&agent_bill_time=".agent_bill_time."&pay_type=".$pay_type."&pay_amt=".$pay_amt."&notify_url=".$notify_url."&return_url=".$return_url."&user_ip=".$user_ip."&goods_name=".$goods_name."&pay_code=".$pay_code."&remark=".$remark."&sign_type=MD5&sign=".$sign;

		//判断是否为手机支付
		if(defined('IN_MOBILE') && IN_MOBILE === true){
		    $params = array(
		        'version'=>$version, //版本号
		        'is_phone' => 1,//手机支付
		        'agent_id'=>$agent_id,
		        'agent_bill_id'=>$agent_bill_id,
		        'agent_bill_time'=>$agent_bill_time,
		        'pay_type'=>$pay_type,
		        'pay_amt'=>$pay_amt,
		        'notify_url'=>$notify_url,
		        'return_url'=>$return_url,
		        'user_ip'=>$user_ip,
		        'goods_name'=>$goods_name,
		        'pay_code'=>$pay_code,
		        'remark'=>$remark,
		        'sign_type'=>"MD5",
		        'sign'=>$sign,
		    );
		}else {
		    $params = array(
		        'version'=>$version, //版本号
		        'agent_id'=>$agent_id,
		        'agent_bill_id'=>$agent_bill_id,
		        'agent_bill_time'=>$agent_bill_time,
		        'pay_type'=>$pay_type,
		        'pay_amt'=>$pay_amt,
		        'notify_url'=>$notify_url,
		        'return_url'=>$return_url,
		        'user_ip'=>$user_ip,
		        'goods_name'=>$goods_name,
		        'pay_code'=>$pay_code,
		        'remark'=>$remark,
		        'sign_type'=>"MD5",
		        'sign'=>$sign,
		    );
		}

			
		addLog('heepay', $params);
		//dump($params);die();
        return $this->_create_payform('POST', $params);
    }
	
	/**
     *    获取通知地址
     *
     *    @author    ShopWind
     *    @param     int $store_id
     *    @param     int $order_id
     *    @return    string
     */
    function _create_notify_url($payTradeNo)
    {
        return SITE_URL .'/includes/payments/heepay/notify_url.php';
    }

    /**
     *    返回通知结果
     *
     *    @author    ShopWind
     *    @param     array $tradeInfo
     *    @param     bool  $strict
     *    @return    array
     */
    function verify_notify($orderInfo, $strict = false)
    {
		if (empty($orderInfo))
        {
            $this->_error('order_info_empty');

            return false;
        }
		


	    $order_status = ORDER_ACCEPTED;
			
	
		return array(
			'target'    =>  $order_status
		);
    }

 
	
	function _getNotifySpecificData()
	{
		$notify = $this->_get_notify();
		
		return array(round($notify['total_fee']/100,2), $notify['out_trade_no']);
	}
}

?>