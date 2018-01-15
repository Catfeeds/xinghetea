<?php

require_once("lib/alipay_submit.class.php");

/**
 *    支付宝支付方式插件
 *
 *    @author    ShopWind
 *    @usage    none
 */

class Alipay_mobilePayment extends BasePayment
{
	/* 支付宝手机支付网关地址 */
	var $_gateway 	= 	'http://wappaygw.alipay.com/service/rest.htm?';
    var $_code      =   'alipay_mobile';
	
	var $_alipay_config = array();
	
	function __construct($payment_info = array())
	{
		parent:: __construct($payment_info);
		

		//合作身份者id，以2088开头的16位纯数字
		$this->_alipay_config['partner']			= $this->_config['alipay_partner'];

		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为"MD5"时，请设置该参数
		$this->_alipay_config['key']				= $this->_config['alipay_key'];

		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为"0001"时，请设置该参数
		//$this->_alipay_config['private_key_path']	= ROOT_PATH . '/includes/payments/alipay_mobile/key/rsa_private_key.pem';

		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为"0001"时，请设置该参数
		//$this->_alipay_config['ali_public_key_path']= ROOT_PATH . '/includes/payments/alipay_mobile/key/alipay_public_key.pem';


		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


		//签名方式 不需修改
		$this->_alipay_config['sign_type']    = 'MD5';//'0001';

		//字符编码格式 目前支持 gbk 或 utf-8
		$this->_alipay_config['input_charset']= CHARSET;

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$this->_alipay_config['cacert']    = getcwd().'\\cacert.pem';

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$this->_alipay_config['transport']    = 'http';
	}
	
    /**
     *    获取支付表单
     *
     *    @author    ShopWind
     *    @param     array $orderInfo  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($orderInfo)
    {
		// 支付网关商户订单号
		$payTradeNo = $this->_get_trade_sn($orderInfo);
		
		//返回格式
		$format = "xml";
		//必填，不需要修改

		//返回格式
		$v = "2.0";
		//必填，不需要修改

		//请求号
		$req_id = date('Ymdhis');
		//必填，须保证每次请求都是唯一

		//**req_data详细信息**

		//服务器异步通知页面路径
		$notify_url = $this->_create_notify_url($payTradeNo);
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//页面跳转同步通知页面路径
		$call_back_url = $this->_create_return_url($payTradeNo);		
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//操作中断返回地址
		$merchant_url = site_url();
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

		//卖家支付宝帐户
		$seller_email = $this->_config['alipay_account'];
		//必填

		//商户订单号
		$out_trade_no = $payTradeNo;
		//商户网站订单系统中唯一订单号，必填

		//订单名称
		$subject = $orderInfo['title'];
		//必填

		//付款金额
		$total_fee = $orderInfo['amount'];
		//必填

		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$para_token = array(
			"service" => "alipay.wap.trade.create.direct",
			"partner" => trim($this->_config['alipay_partner']),
			"sec_id" => $this->_alipay_config['sign_type'],
			"format"	=> $format,
			"v"	=> $v,
			"req_id"	=> $req_id,
			"req_data"	=> $req_data,
			"_input_charset"	=> CHARSET
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->_alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];

		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		
        $parameter = array(
		
			"service" => "alipay.wap.auth.authAndExecute",
			"partner" => trim($this->_alipay_config['partner']),
			"sec_id" => trim($this->_alipay_config['sign_type']),
			"format"	=> $format,
			"v"	=> $v,
			"req_id"	=> $req_id,
			"req_data"	=> $req_data,
			"_input_charset"	=> trim(strtolower($this->_alipay_config['input_charset'])),
        );
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($this->_alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', 'loading...');
		echo $html_text;
    }
	
	/**
     *    获取通知地址
     *
     *    @author    ShopWind
     *    @param     int $store_id
     *    @param     int $tradeNo
     *    @return    string
     */
    function _create_notify_url($tradeNo)
    {
        return SITE_URL .'/includes/payments/alipay_mobile/notify_url.php';
    }

    /**
     *    获取返回地址
     *
     *    @author    ShopWind
     *    @param     int $store_id
     *    @param     int $tradeNo
     *    @return    string
     */
    function _create_return_url($payTradeNo)
    {
        return SITE_URL .'/includes/payments/alipay_mobile/return_url.php';
    }

    /**
     *    返回通知结果
     *
     *    @author    ShopWind
     *    @param     array $orderInfo
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

        /* 初始化所需数据 */
        $notify =   $this->_get_notify();
		
        /* 验证通知是否可信 */
        $sign_result = $this->_verify_sign($notify, $strict);
        if (!$sign_result)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');

            return false;
        }
        /*----------通知验证结束----------*/
		
		/* 以下是服务器异步通知，需要解析返回的数据 */
		
		//解析notify_data
		//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
		$doc = new DOMDocument();	
		if ($this->_alipay_config['sign_type'] == 'MD5') {
			$doc->loadXML($notify['notify_data']);
		}
	
		if ($this->_alipay_config['sign_type'] == '0001') {
			$alipayNotify = new AlipayNotify($this->_alipay_config);
			$doc->loadXML($alipayNotify->decrypt($notify['notify_data']));
		}
	
		if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
			// 外部交易号
			$notify['out_trade_no'] = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
			//支付宝交易号
			$notify['trade_no'] = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
			//交易状态
			$notify['trade_status'] = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
			// 订单总额
			$notify['total_fee'] = $doc->getElementsByTagName( "total_fee" )->item(0)->nodeValue;
		}
		
        /*----------本地验证开始----------*/
        /* 验证与本地信息是否匹配 */
        /* 这里不只是付款通知，有可能是发货通知，确认收货通知 */

        if ($orderInfo['payTradeNo'] != $notify['out_trade_no'])
        {
            /* 通知中的订单与欲改变的订单不一致 */
            $this->_error('order_inconsistent');

            return false;
        }
        if ($orderInfo['amount'] != $notify['total_fee'])
        {
            /* 支付的金额与实际金额不一致 */
            $this->_error('price_inconsistent');

            return false;
        }
        //至此，说明通知是可信的，订单也是对应的，可信的
		
        /* 按通知结果返回相应的结果 */
        switch ($notify['trade_status'])
        {
            case 'TRADE_FINISHED':              //交易结束
			case 'TRADE_SUCCESS':               // 交易成功
                
				$order_status = ORDER_ACCEPTED;
            break;
            case 'TRADE_CLOSED':                //交易关闭
                $order_status = ORDER_CANCLED;
            break;

            default:
                $this->_error('undefined_status');
                return false;
            break;
        }
        return array(
            'target'    =>  $order_status,
        );
    }

    /**
     *    验证签名是否可信
     *
     *    @author    ShopWind
     *    @param     array $notify
     *    @return    bool
     */
    function _verify_sign($notify, $strict = false)
    {
		require_once("lib/alipay_notify.class.php");
		
        $alipayNotify = new AlipayNotify($this->_alipay_config);
		
		if($strict)
		{
			$verify_result = $alipayNotify->verifyNotify();
		}
		else
		{
			$verify_result = $alipayNotify->verifyReturn();
		}
		
		return $verify_result;
    }
	
	function _getNotifySpecificData()
	{
		$notify = $this->_get_notify();
		
		return array($notify['total_fee'], $notify['trade_no']);
	}
}

?>