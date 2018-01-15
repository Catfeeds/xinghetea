<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

//外部定时任务 by PwordC
class ConsoleApp extends MallbaseApp
{
    //每日统计传入积分和电子币
    function total(){
        $deposit_trade_mod =& m('deposit_trade');
        $integral_log_mod =& m('integral_log');
        $daily_summary_mod =& m('daily_summary');
        $deposit_total = 0;
        $integral_total = 0;
        $yesterday = date('Y-m-d',time()-86400);
        $time_0 = strtotime(date('Ymd',time()));
        $time_1 = strtotime(date('Ymd',time())) - 86400;
        $deposit_records = $deposit_trade_mod->find(array(
            'conditions' => "title='erp导入' and add_time < {$time_0} and add_time>= {$time_1}",      
        ));
        foreach ($deposit_records as $v){
            $deposit_total += $v['amount'];
        }
        $integral_records = $integral_log_mod->find(array(
            'conditions' => "flag='erp导入' and add_time < {$time_0} and add_time>= {$time_1}",
        ));
        foreach ($integral_records as $vv){
            $integral_total += $vv['changes'];
        }
        $data=array();
        $data['deduct'] = $deposit_total;
        $data['integral'] = $integral_total;
        $data['date'] = $yesterday;
        //验证是否插入过
        $count = $daily_summary_mod->get("date='{$yesterday}'");
        if (!$count){
            $id = $daily_summary_mod->add($data);  
            addLog('daily_summary', $id.'---'.$data);
        }else {
            addLog('daily_summary', '----false----'.json_encode($data),'重复插入');
        }
        
    }
    
    /**
     *    每日消费对账
     *
     *    @author    PwordC
     *    @param     
     *    @return    
     */
    function balance_account(){
        $balance_account_mod =& m('balance_account');
        $time_e = strtotime(date('Ymd',time()));
        $time_b = strtotime(date('Ymd',time())) - 86400;
        $order_amount = $this->count_order_amount($time_b, $time_e);

        $integral_pay = $this->real_integral_pay($time_b, $time_e);
        $cash_pay = $this->real_cash_pay($time_b, $time_e);
        $deposit_pay = $this->real_deposit_pay($time_b, $time_e);
        $data=array();
        $data['order_amount'] = $order_amount['amount'];
        $data['order_integral'] = $order_amount['integral'];
        $data['deposit_pay'] = $deposit_pay;
        $data['integral_pay'] = $integral_pay;
        $data['cash_pay'] = $cash_pay;
        if ($cash_pay + $deposit_pay == $order_amount['amount'] && $order_amount['integral'] + $integral_pay ==0){
            $data['status'] = 1;
        }else{
            $data['status'] = 0;
        }
        $data['create_time'] = date('Y-m-d',time()-86400);
        //验证是否插入过
        $count = $balance_account_mod->get("create_time='{$data['create_time']}'");
        if (!$count){
            $id = $balance_account_mod->add($data);
            addLog('balance_account', $id.'---'.$data);
        }else {
            addLog('balance_account', '----false----'.$data,'重复插入');
        }
        
        
    }
    /**
     *    每日余额统计
     *
     *    @author    PwordC
     *    @param     
     *    @return    
     */
    function account_summary(){
        $integral_mod =& m('integral');
        $deposit_account_mod =& m('deposit_account');
        $account_summary_mod =& m('account_summary');
        $yesterday_time = date('Y-m-d',time()-86400-86400);
        $time_e = strtotime(date('Ymd',time())) + 86400;
        $time_b = strtotime(date('Ymd',time()));
        $yesterday_integral_info = $account_summary_mod->get(" type='integral' and date='{$yesterday_time}'");
        $yesterday_deposit_info = $account_summary_mod->get(" type='deposit' and date='{$yesterday_time}'");
        //计算当前余额
        $integral_info = $integral_mod->find(array(
            'conditions' => "1=1",
        ));
        $integral = 0;
        
        foreach ($integral_info as $k=>$v){
            $integral += $v['amount'];
        }
        //单独计算未缴费订单，冻结的积分
        $frozen_integral = 0;
        $integral_log_mod =& m('integral_log');
        $order_mod =& m('order');
        $integral_log_frozen_info = $integral_log_mod->find(array(
            'conditions' => "order_id !=0 and add_time >= {$time_b} and add_time < {$time_e} and state='frozen'",
        ));
        foreach ($integral_log_frozen_info as $k=>$v){
            $order_info = $order_mod->get("order_sn={$v['order_sn']}");
            if ($order_info['status'] <= 11){
                $frozen_integral += $v['changes'];
            }else {
                $frozen_integral += 0;
            }
        }
        
        $integral_data = array();
        $integral_data['type'] = 'integral';
        $integral_data['yesterday_balance'] = $yesterday_integral_info['real_balance'];
        $integral_data['real_balance'] = $integral; 
        $integral_data['date'] = date('Y-m-d',time()-86400);
        $real_integral_pay = $this->real_integral_pay($time_b, $time_e);
        $real_integral_income = $this->real_integral_income($time_b, $time_e);
        $integral_data['pay'] = $real_integral_pay;
        $integral_data['income'] = $real_integral_income;
        if ($integral_data['yesterday_balance'] + $real_integral_pay + $real_integral_income + $frozen_integral != $integral){
            $integral_data['status'] = 0;
        }else {
            $integral_data['status'] = 1;
        }
        $account_summary_mod->add($integral_data);
        $deposit = 0;
        $deposit_account_info = $deposit_account_mod->find(array(
            'conditions' => "1=1",
        ));
        foreach ($deposit_account_info as $k=>$v){
            $deposit += $v['money'];
        }
        $deposit_data = array();
        $deposit_data['type'] = 'deposit';
        $deposit_data['yesterday_balance'] = $yesterday_deposit_info['real_balance'];
        $deposit_data['real_balance'] = $deposit;
        $deposit_data['date'] = date('Y-m-d',time()-86400);
        $real_deposit_income = $this->real_deposit_income($time_b, $time_e);
        $real_deposit_pay = $this->real_deposit_pay($time_b, $time_e);
        $deposit_data['pay'] = $real_deposit_pay;
        $deposit_data['income'] = $real_deposit_income;
        //计算现金支付
        $real_cash_pay = $this->real_cash_pay($time_b, $time_e);
        $deposit = strval($deposit);
        if ($deposit_data['yesterday_balance'] - $real_deposit_pay + $real_deposit_income - $real_cash_pay != $deposit){
            $deposit_data['status'] = 0;
        }else {
            $deposit_data['status'] = 1;
        }
        $account_summary_mod->add($deposit_data);
    }
    /**
     *    计算某一时间段已支付订单金额
     *
     *    @author    PwordC
     *    @param     $time_b 起始时间
     *    @param     $time_e 结束时间
     *    @return    array  包括现金支付总额和积分抵扣总额
     */
    function count_order_amount($time_b,$time_e){
        $order_mod  =& m('order');
        $orders = $order_mod->find(array(
            'conditions' => "status >= 20 and pay_time >= {$time_b} and pay_time <{$time_e}",
        ));
        $order_amount = array();
        $order_amount['amount'] = 0;
        $order_amount['integral'] = 0;
        foreach ($orders as $k=>$v){
            $order_amount['amount'] += $v['order_amount'];
            $order_amount['integral'] += $v['discount'];
        }
        return $order_amount;
    }
    /**
     *    计算某一时间段实际支出（积分）
     *
     *    @author    PwordC
     *    @param     $time_b
     *    @param     $time_e
     *    @return    
     */
    function real_integral_pay($time_b,$time_e){
        $integral_log_mod =& m('integral_log');
        $order_mod =& m('order');
        $integral_log_info = $integral_log_mod->find(array(
            'conditions' => "order_id !=0 and add_time >= {$time_b} and add_time < {$time_e} ",
        ));
        $integral_pay = 0;
        foreach ($integral_log_info as $k=>$v){
            $order_info = $order_mod->get("order_sn={$v['order_sn']}");
            if ($order_info['status'] <= 11){
                $integral_pay += 0;
            }else {
                if ($v['type'] == 'return_integral'){
                    $integral_pay += 0;
                }else {
                    $integral_pay += $v['changes'];
                }
                
            }          
        }
        return $integral_pay;
    }
    
    /**
     *    计算某一时间段实际支出（现金）
     *
     *    @author    PwordC
     *    @param     $time_b
     *    @param     $time_e
     *    @return
     */
    function real_cash_pay($time_b,$time_e){
        $deposit_trade_mod =& m('deposit_trade');
        $order_mod =& m('order');
        $deposit_trade_info = $deposit_trade_mod->find(array(
            'conditions' => "bizIdentity = 'trade10001' and payment_code!='deposit' and seller_id != 0 and pay_time >= {$time_b} and pay_time<{$time_e}",
        ));
        $cash_pay = 0;
        foreach ($deposit_trade_info as $k=>$v){
            //判断订单是否关闭
            $order_info = $order_mod->get("order_sn={$v['bizOrderId']}");
            if ($order_info['status'] == 0 ){
                $cash_pay += 0;
            }else {
                $cash_pay += $v['amount'];
            }
        }
        return  $cash_pay;
    }
    /**
     *    计算某一时间段实际支出（电子币 ）
     *
     *    @author    PwordC
     *    @param     $time_b
     *    @param     $time_e
     *    @return
     */
    function real_deposit_pay($time_b,$time_e){
        $deposit_trade_mod =& m('deposit_trade');
        $order_mod =& m('order');
        $deposit_trade_info = $deposit_trade_mod->find(array(
            'conditions' => "bizIdentity = 'trade10001' and payment_code='deposit' and seller_id != 0 and pay_time >= {$time_b} and pay_time<{$time_e}",
        ));
        $deposit_pay = 0;
        foreach ($deposit_trade_info as $k=>$v){
            //判断是否退款
            $order_info = $order_mod->get("order_sn={$v['bizOrderId']}");
            if ($order_info['status'] == 0){
                $deposit_pay += 0;
            }else {
                $deposit_pay += $v['amount'];
            }
            
        }
        return $deposit_pay;
    }
    /**
     *    计算某一时间段实际收入（电子币）
     *
     *    @author    PwordC
     *    @param     $time_b
     *    @param     $time_e
     *    @return    
     */
    function real_deposit_income($time_b,$time_e){
        $deposit_trade_mod =& m('deposit_trade');
        $deposit_trade_info = $deposit_trade_mod->find(array(
            'conditions' => "bizIdentity = 'trade20001' and flow='income' and seller_id = 0 and pay_time >= {$time_b} and pay_time<{$time_e}",
        ));
        $deposit_income = 0;
        foreach ($deposit_trade_info as $k=>$v){
            $deposit_income += $v['amount'];
        }
        //退款金额也算是电子币收入，计算退款。
        $refund_mod =& m('refund');
        $refund = 0;
        $refund_info = $refund_mod->find(array(
            'conditions' => "status='SUCCESS' and end_time >= {$time_b} and end_time <= {$time_e}",
        ));
        foreach ($refund_info as $k=>$v){
            $refund += $v['refund_total_fee'];
        }
        $deposit_income = $deposit_income + $refund;
        return $deposit_income;
    }
    /**
     *    计算某一时间段实际收入（积分）
     *
     *    @author    PwordC
     *    @param     $time_b
     *    @param     $time_e
     *    @return
     */
    function real_integral_income($time_b,$time_e){
        $integral_log_mod =& m('integral_log');
        $integral_log_info = $integral_log_mod->find(array(
            'conditions' => "order_id =0 and type='admin_handle' and add_time >= {$time_b} and add_time < {$time_e} ",
        ));
        $integral_income = 0;
        foreach ($integral_log_info as $k=>$v){
            $integral_income += $v['changes'];
        }
        return $integral_income;
    
    }
}

?>
