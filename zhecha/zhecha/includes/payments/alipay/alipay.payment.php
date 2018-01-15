<?php

/**
 *    支付宝支付方式插件
 *
 *    @author    ShopWind
 *    @usage    none
 */

class AlipayPayment extends BasePayment
{
	/* 支付宝网关地址（新）*/
	var $_gateway 	= 	'https://mapi.alipay.com/gateway.do';
    var $_code      =   'alipay';

    /**
     *    获取支付表单
     *
     *    @author    ShopWind
     *    @param     array $tradeInfo  待支付的订单信息，必须包含总费用及唯一外部交易号
     *    @return    array
     */
    function get_payform($orderInfo)
    {
		// 支付网关商户订单号
		$payTradeNo = $this->_get_trade_sn($orderInfo);

        $params = array(

            /* 基本信息 */
            'service'           => $this->_config['alipay_service'],
            'partner'           => $this->_config['alipay_partner'],
            '_input_charset'    => CHARSET,
            'notify_url'        => $this->_create_notify_url($payTradeNo),
            'return_url'        => $this->_create_return_url($payTradeNo),

            /* 业务参数 */
            'subject'           => $orderInfo['title'],
            //订单ID由不属签名验证的一部分，所以有可能被客户自行修改，所以在接收网关通知时要验证指定的订单ID的外部交易号是否与网关传过来的一致
            'out_trade_no'      => $payTradeNo,
            'price'             => $orderInfo['amount'],   //应付总价
            'quantity'          => 1,
            'payment_type'      => 1,

            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',

            /* 买卖双方信息 */
            'seller_email'      => $this->_config['alipay_account']
        );

        $params['sign']         =   $this->_get_sign($params);
        $params['sign_type']    =   'MD5';
		
        return $this->_create_payform('GET', $params);
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
        return SITE_URL .'/includes/payments/alipay/notify_url.php';
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

        /* 初始化所需数据 */
        $notify =   $this->_get_notify();

        /* 验证来路是否可信 */
        if ($strict)
        {
            /* 严格验证 */
            $verify_result = $this->_query_notify($notify['notify_id']);
            if(!$verify_result)
            {
                /* 来路不可信 */
                $this->_error('notify_unauthentic');

                return false;
            }
        }

        /* 验证通知是否可信 */
        $sign_result = $this->_verify_sign($notify);
        if (!$sign_result)
        {
            /* 若本地签名与网关签名不一致，说明签名不可信 */
            $this->_error('sign_inconsistent');

            return false;
        }

        /*----------通知验证结束----------*/
		
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
     *    查询通知是否有效
     *
     *    @author    ShopWind
     *    @param     string $notify_id
     *    @return    string
     */
    function _query_notify($notify_id)
    {
		/* 支付宝通知地址（新） */
		$query_url = "https://mapi.alipay.com/gateway.do?service=notify_verify&partner={$this->_config['alipay_partner']}&notify_id={$notify_id}";
		
		return (file_get_contents($query_url) === 'true');
    }

    /**
     *    获取签名字符串
     *
     *    @author    ShopWind
     *    @param     array $params
     *    @return    string
     */
    function _get_sign($params)
    {
        /* 去除不参与签名的数据 */
        unset($params['sign'], $params['sign_type'], $params['tradeNo'], $params['payTradeNo'], $params['order_id'], $params['app'], $params['act']);

        /* 排序 */
        ksort($params);
        reset($params);

        $sign  = '';
        foreach ($params AS $key => $value)
        {
            $sign  .= "{$key}={$value}&";
        }

        return md5(substr($sign, 0, -1) . $this->_config['alipay_key']);
    }

    /**
     *    验证签名是否可信
     *
     *    @author    ShopWind
     *    @param     array $notify
     *    @return    bool
     */
    function _verify_sign($notify)
    {
        $local_sign = $this->_get_sign($notify);

        return ($local_sign == $notify['sign']);
    }
	
	function _getNotifySpecificData()
	{
		$notify = $this->_get_notify();
		
		return array($notify['total_fee'], $notify['trade_no']);
	}
}

?>