<?php

/**
 *    后台统计控制器
 */
class StatApp extends BackendApp
{	

	var $search_arr;
	var $_order_mod;

    function __construct()
    {
        $this->StatApp();
    }

    function StatApp()
    {
        parent::__construct();
		import('datehelper.lib');
        $this->search_arr = $this->dealwithSearchTime($_REQUEST);
		$this->_order_mod = &m('order');
    }

    function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js,highcharts.js,jquery.ui/jquery.ui.js,jquery.ui/i18n/' . i18n_code() . '.js',
			'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
		));
		$this->assign('order_status', array(
            ORDER_PENDING => Lang::get('order_pending'),
            ORDER_SUBMITTED => Lang::get('order_submitted'),
            ORDER_ACCEPTED => Lang::get('order_accepted'),
            ORDER_SHIPPED => Lang::get('order_shipped'),
            ORDER_FINISHED => Lang::get('order_finished'),
            ORDER_CANCELED => Lang::get('order_canceled'),
        ));
		//获得系统年份
		$year_arr = getSystemYearArr();
		//获得系统月份
		$month_arr = getSystemMonthArr();
		//获得本月的周时间段
		$week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
		$this->assign('year_arr', $year_arr);
		$this->assign('month_arr', $month_arr);
		$this->assign('week_arr', $week_arr);
		$this->assign('search_arr', $this->search_arr);
        $this->display('stat.order.html');
    }
	
	function export_csv()
	{
		list($conditions, $useTime, $start_time, $end_time) = $this->getConditions();
		if ($_GET['id'] != '') {
            $ids = explode(',', $_GET['id']);
			$conditions .= ' AND order_alias.order_id' . db_create_in($ids);
        }
        $orders = $this->_order_mod->findAll(array(
            'conditions'    => $conditions,
			'join'          => 'has_orderextm',
            'order'         => "{$useTime} DESC, order_alias.order_id DESC",
			'include'       => array(
                'has_ordergoods',   //取出订单商品
            ),
        )); 
		if(!$orders) {
			$this->show_warning('no_such_order');
            return;
		}
		
		// 所有订单总金额
		$amount = 0;
		foreach($orders as $k=>$v)
		{
			foreach($v['order_goods'] as $ordergoods)
			{
				$orders[$k]['goods'] .= '商品名称：'.$ordergoods['goods_name'].',价格：'.$ordergoods['price'].',数量：'.$ordergoods['quantity'].'；';
			}
			$amount += $v['order_amount'];
		}
		
		/* xls文件数组 */
		$record_xls = array();		
		$record_title = array(
			'seller_name' 	=> 	'店铺名称',
    		'order_sn' 		=> 	'订单编号',
    		'dateline' 		=> 	'时间',
    		'buyer_name' 	=> 	'买家名称',
    		'order_amount' 	=> 	'订单总额',
    		'payment_name' 	=> 	'付款方式',
			'name' 			=> 	'收货人姓名',
    		'buyer_addr' 	=> 	'地址',
			'buyer_phone' 	=> 	'电话',
			'pay_message'	=>	'买家留言',
			'status'		=>	'订单状态',
			'invoice_no'	=>	'快递单号',
			'postscript'	=>	'备注',
			'goods'			=>	'商品信息',
		);
		$folder = 'order_'.local_date('Ymdhis', gmtime());
		$record_xls[] = $record_title;
		foreach($orders as $key=>$order)
    	{
			$record_value['seller_name']	=	$order['seller_name'];
			$record_value['order_sn']		=	$order['order_sn'];
			$record_value['dateline']		=	local_date('Y-m-d H:i:s', $order[$useTime]);
			$record_value['buyer_name']		=	$order['buyer_name'];
			$record_value['order_amount']	=	$order['order_amount'];
			$record_value['payment_name']	=	$order['payment_name'];
			$record_value['name']			=	$order['consignee'];
			$record_value['buyer_addr']		=	$order['region_name'].$order['address'];
			$record_value['buyer_phone']	=	$order['phone_mob'];
			$record_value['pay_message']   	=   $order['pay_message'];
			$record_value['status']			=	order_status($order['status']);
			$record_value['invoice_no']		=	$order['invoice_no'];
			$record_value['postscript']		=	$order['postscript'];
			$record_value['goods']			=	$order['goods'];
        	$record_xls[] 					= 	$record_value;
    	}
		import('excelwriter.lib');
		$ExcelWriter = new ExcelWriter(CHARSET, $folder);
		$ExcelWriter->add_array($record_xls);
		$ExcelWriter->output();
	}
	
	/**
     * 输出平台订单总数据
     */
    function get_plat_sale()
	{
        list($conditions) = $this->getConditions();
        $statcount_arr = $this->_order_mod->get(array(
			'conditions' => $conditions,
			'fields' => 'COUNT(*) as ordernum, SUM(order_amount) as orderamount',		
		));
		echo '<dl class="row"><dd class="opt"><ul>';
		echo '<li><h4>总销售额：</h4><h2 class="timer">'.number_format($statcount_arr['orderamount'],2).'</h2><h6>元</h6></li>';
		echo '<li><h4>总订单量：</h4><h2 class="timer">'. $statcount_arr['ordernum'].'</h2><h6>笔</h6></li>';
		echo '</ul></dd><dl>';
        exit();
    }

    /**
     * 输出订单统计XML数据
     */
    function get_order_xml()
	{
        list($conditions, $useTime) = $this->getConditions();
		$order = " {$useTime} DESC, order_id DESC ";
        $param = array('order_sn','seller_name', 'dateline','buyer_name','order_amount','payment_name','status');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
			if(trim($_POST['sortname']) == 'dateline') $_POST['sortname'] = $useTime;
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
		$pre_page = $_POST['rp']?intval($_POST['rp']):10;
		$page   =   $this->_get_page($pre_page);
		$model_order =& m('order');
		$orders = $model_order->find(array(
            'conditions'    => $conditions,
            'limit'         => $page['limit'],  //获取当前页的数据
            'order'         => "$order",
            'count'         => true             //允许统计
        ));
        $page['item_count'] = $model_order->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($orders as $k => $v){
			$list = array();
			$operation = "<a class='btn green' href='index.php?app=order&act=view&id={$k}'><i class='fa fa-search-plus'></i>查看</a>";
			$list['operation'] = $operation;
			$list['order_sn'] = $v['order_sn'];
			$list['seller_name'] = $v['seller_name'];
			$list['dateline'] = local_date('Y-m-d H:i:s',$v[$useTime]);
			$list['buyer_name'] = $v['buyer_name'];
			$list['order_amount'] = $v['order_amount'];
			$list['payment_name'] = $v['payment_name'];
			$list['status'] = order_status($v['status']);
			$data['list'][$k] = $list;
		}
        $this->flexigridXML($data);
    }
    /**
     * 订单走势
     */
    function sale_trend()
	{
		$conditions = "1=1";
		
        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
		
		$useTime = 'add_time';
        if(trim($_GET['order_type']) != ''){
			$status = intval($_GET['order_type']);
            $conditions .= " AND status = " . $status;
			if(in_array($status, array(ORDER_FINISHED))) $useTime = 'finished_time';
			if(in_array($status, array(ORDER_SHIPPED)))  $useTime = 'ship_time';
			if(in_array($status, array(ORDER_ACCEPTED))) $useTime = 'pay_time';
        }
        if(trim($_GET['store_name']) != ''){
            $conditions .= " AND seller_name like '%".trim($_GET['store_name'])."%' ";
        }

        $stattype = trim($_GET['type']);
        if($stattype == 'ordernum'){
            $field = ' COUNT(*) as ordernum ';
            $stat_arr['title'] = '订单量统计';
            $stat_arr['yAxis'] = '订单量';
        } else {
            $stattype = 'orderamount';
            $field = ' SUM(order_amount) as orderamount ';
            $stat_arr['title'] = '订单销售额统计';
            $stat_arr['yAxis'] = '订单销售额';
        }
        if($this->search_arr['search_type'] == 'day'){
    	    $stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
    	    $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            //构造横轴数据
            for($i=0; $i<24; $i++){
                //统计图数据
                $curr_arr[$i] = 0;//今天
                $up_arr[$i] = 0;//昨天
                //统计表数据
                $currlist_arr[$i]['timetext'] = $i;

                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = "$i";
            }
			
            $today_day = local_date('d', $etime);//今天日期
            $yesterday_day = local_date('d', $stime);//昨天日期
			
			$conditions .= " AND {$useTime} BETWEEN {$stime} AND {$etime} ";
            $field .= " ,DAY(FROM_UNIXTIME({$useTime})) as dayval, HOUR(FROM_UNIXTIME({$useTime})) as hourval ";
            $group = ' GROUP BY dayval,hourval';
			$orderlist= $this->_order_mod->find(array(
				'conditions' => $conditions.$group,
				'fields' => $field,
			));

            foreach($orderlist as $k => $v){
                if($today_day == $v['dayval']){
                    $curr_arr[$v['hourval']] = floatval($v[$stattype]);
                    $currlist_arr[$v['hourval']]['val'] = $v[$stattype];
                }
                if($yesterday_day == $v['dayval']){
                    $up_arr[$v['hourval']] = floatval($v[$stattype]);
                    $uplist_arr[$v['hourval']]['val'] = $v[$stattype];
                }
            }
            $stat_arr['series'][0]['name'] = '昨天';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '今天';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }

        if($this->search_arr['search_type'] == 'week'){
			$current_weekarr = explode('|', $this->search_arr['week']['current_week']);
			
			$stime = gmstr2time($current_weekarr[0]);
			$etime = gmstr2time_end($current_weekarr[1]) - 1;
			
			// 当年的第几周
            $up_week = local_date('W', $stime);//上周
            $curr_week = local_date('W', $etime+1);//本周
			if(($curr_week < $up_week) && ($curr_week == 1)) { // 统计了去年的第几周了，故重置
				$up_week = 0;
			}
			
            //构造横轴数据
            for($i=1; $i<=7; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                $tmp_weekarr = getSystemWeekArr();
                //统计表数据
                $uplist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $currlist_arr[$i]['timetext'] = $tmp_weekarr[$i];
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
                unset($tmp_weekarr);
            }
            $conditions .= " AND {$useTime} BETWEEN {$stime} AND {$etime} ";
            $field .= ",WEEKOFYEAR(FROM_UNIXTIME({$useTime}))+1 as weekval, WEEKDAY(FROM_UNIXTIME({$useTime}))+1 as dayofweekval ";
            $group = ' GROUP BY weekval,dayofweekval';
            $orderlist= $this->_order_mod->find(array(
				'conditions' => $conditions.$group,
				'fields' => $field,
			));
			
            foreach($orderlist as $k => $v){
                if ($up_week == $v['weekval']){
                    $up_arr[$v['dayofweekval']] = floatval($v[$stattype]);
                    $uplist_arr[$v['dayofweekval']]['val'] = floatval($v[$stattype]);
                }
                if ($curr_week == $v['weekval']){
                    $curr_arr[$v['dayofweekval']] = floatval($v[$stattype]);
                    $currlist_arr[$v['dayofweekval']]['val'] = floatval($v[$stattype]);
                }
            }
            $stat_arr['series'][0]['name'] = '上周';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本周';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }

        if($this->search_arr['search_type'] == 'month'){
			$stime = getMonthFirstDay($this->search_arr['month']['current_year'], $this->search_arr['month']['current_month']);
			$etime = getMonthLastDay($this->search_arr['month']['current_year'], $this->search_arr['month']['current_month']);
			
            $up_month = local_date('m', $stime-1);
            $curr_month = local_date('m',$etime);
			
            //计算横轴的最大量（由于每个月的天数不同）
            $up_dayofmonth = local_date('t', $stime-1);
            $curr_dayofmonth = local_date('t',$etime);

            $x_max = $up_dayofmonth > $curr_dayofmonth ? $up_dayofmonth : $curr_dayofmonth;

            //构造横轴数据
            for($i=1; $i<=$x_max; $i++){
                //统计图数据
                $up_arr[$i] = 0;
                $curr_arr[$i] = 0;
                //统计表数据
                $currlist_arr[$i]['timetext'] = $i;
                $uplist_arr[$i]['val'] = 0;
                $currlist_arr[$i]['val'] = 0;
                //横轴
                $stat_arr['xAxis']['categories'][] = $i;
            }
            $conditions .= " AND {$useTime} BETWEEN {$stime} AND {$etime} ";
            $field .= ",MONTH(FROM_UNIXTIME({$useTime})) as monthval,day(FROM_UNIXTIME({$useTime})) as dayval ";
            $group = ' GROUP BY monthval,dayval';
            $orderlist= $this->_order_mod->find(array(
				'conditions' => $conditions.$group,
				'fields' => $field,
			));
			
            foreach($orderlist as $k => $v){
                if ($up_month == $v['monthval']){
                    $up_arr[$v['dayval']] = floatval($v[$stattype]);
                    $uplist_arr[$v['dayval']]['val'] = floatval($v[$stattype]);
                }
                if ($curr_month == $v['monthval']){
                    $curr_arr[$v['dayval']] = floatval($v[$stattype]);
                    $currlist_arr[$v['dayval']]['val'] = floatval($v[$stattype]);
                }
            }
            $stat_arr['series'][0]['name'] = '上月';
            $stat_arr['series'][0]['data'] = array_values($up_arr);
            $stat_arr['series'][1]['name'] = '本月';
            $stat_arr['series'][1]['data'] = array_values($curr_arr);
        }
        $stat_json = getStatData_LineLabels($stat_arr);
        $this->assign('stat_json',$stat_json);
        $this->assign('stattype',$stattype);
        $this->display('stat.linelabels.html');
    }
	
	function getConditions()
	{
		$conditions = " 1=1 ";
		
		//默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        //计算昨天和今天时间
        if($this->search_arr['search_type'] == 'day'){
            $stime = $this->search_arr['day']['search_time'] - 86400;//昨天0点
            $etime = $this->search_arr['day']['search_time'] + 86400 - 1;//今天24点
            $curr_stime = $this->search_arr['day']['search_time'];//今天0点
        } elseif ($this->search_arr['search_type'] == 'week'){
			$current_weekarr = explode('|', $this->search_arr['week']['current_week']);
			$stime = gmstr2time($current_weekarr[0]);
			$etime = gmstr2time_end($current_weekarr[1]) - 1;
			$curr_stime = $stime;//本周0点
        } elseif ($this->search_arr['search_type'] == 'month'){
            $stime = getMonthFirstDay($this->search_arr['month']['current_year'], $this->search_arr['month']['current_month']);
            $etime = getMonthLastDay($this->search_arr['month']['current_year'], $this->search_arr['month']['current_month']);
            $curr_stime = $stime; // 本月0点
        }
		
		//  根据不同的状态，使用不同的时间字段
        $useTime = 'add_time';
        if(trim($_GET['order_type']) != ''){
			$status = intval($_GET['order_type']);
            $conditions .= " AND status = " . $status;
			if(in_array($status, array(ORDER_FINISHED))) $useTime = 'finished_time';
			if(in_array($status, array(ORDER_SHIPPED)))  $useTime = 'ship_time';
			if(in_array($status, array(ORDER_ACCEPTED))) $useTime = 'pay_time';
        }
		if(trim($_GET['store_name']) != ''){
            $conditions .= " AND seller_name like '%".trim($_GET['store_name'])."%' ";
        }
		$conditions .= " AND {$useTime} BETWEEN ".$curr_stime." AND ".$etime;
		return 	array($conditions, $useTime, $curr_stime, $etime);
	}
	
	/**
     * 查询每月的周数组
     */
    function getweekofmonth()
	{
        $year = $_GET['y'];
        $month = $_GET['m'];
		if(!$year || !$month)
		{
			$this->json_error('error');
			return;
		}
        $week_arr = getMonthWeekArr($year, $month);
        $this->json_result($week_arr);
    }
	
	// 格式化时间
	function dealwithSearchTime($search_arr = array())
	{
        //天
        if(!$search_arr['search_time']){
            $search_arr['search_time'] = local_date('Y-m-d', gmtime());
        }
        $search_arr['day']['search_time'] = gmstr2time($search_arr['search_time']);//搜索的时间

        // 年
        if(!$search_arr['searchweek_year']){
            $search_arr['searchweek_year'] = local_date('Y', gmtime());
        } 
		// 月
        if(!$search_arr['searchweek_month']){
            $search_arr['searchweek_month'] = local_date('m', gmtime());
        }
		// 周
        if(!$search_arr['searchweek_week']){
            $searchweek_weekarr = getWeek_SdateAndEdate(gmtime());
            $search_arr['searchweek_week'] = implode('|', $searchweek_weekarr);
            $searchweek_week_edate_m = local_date('m', gmstr2time($searchweek_weekarr['edate']));
            if($searchweek_week_edate_m <> $search_arr['searchweek_month']){
                $search_arr['searchweek_month'] = $searchweek_week_edate_m;
            }
        }
        $weekcurrent_year = $search_arr['searchweek_year'];
        $weekcurrent_month = $search_arr['searchweek_month'];
        $weekcurrent_week = $search_arr['searchweek_week'];
        $search_arr['week']['current_year'] = $weekcurrent_year;
        $search_arr['week']['current_month'] = $weekcurrent_month;
        $search_arr['week']['current_week'] = $weekcurrent_week;

        // 年
        if(!$search_arr['searchmonth_year']){
            $search_arr['searchmonth_year'] = local_date('Y', gmtime());
        }
		// 月
        if(!$search_arr['searchmonth_month']){
            $search_arr['searchmonth_month'] = local_date('m', gmtime());
        }
        $monthcurrent_year = $search_arr['searchmonth_year'];
        $monthcurrent_month = $search_arr['searchmonth_month'];
        $search_arr['month']['current_year'] = $monthcurrent_year;
        $search_arr['month']['current_month'] = $monthcurrent_month;

        return $search_arr;
    }
}
?>
