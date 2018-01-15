<?php

/* 消费对账控制器 */
class Balance_accountApp extends BackendApp
{
	var $_balance_account_mod;
    function __construct()
    {
        $this->Balance_accountApp();
    }

    function Balance_accountApp()
    {
        parent::__construct();
		$this->_balance_account_mod =& m('balance_account');
    }

	function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js',
		));
        $this->display('balance_account.index.html');
    }
	
	function get_xml()
	{
		$conditions = '1 = 1';
		if ($_POST['query'] != '') 
		{
			$conditions .= " AND ".$_POST['qtype']." like '%" . $_POST['query'] . "%'";
		}
        $param = array('date');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
		$pre_page = $_POST['rp']?intval($_POST['rp']):10;
		$page   =   $this->_get_page($pre_page);
		$users = $this->_balance_account_mod->find(array(
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'order' => "create_time desc",
            'count' => true,
        ));
		$page['item_count'] = $this->_balance_account_mod->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($users as $k => $v)
		{
			$list = array();
			$list['order_amount'] = $v['order_amount'];
			$list['order_integral'] = $v['order_integral'];
			$list['deposit_pay'] = $v['deposit_pay'];
			$list['cash_pay'] = $v['cash_pay'];
			$list['integral_pay'] = $v['integral_pay'];
			$list['status'] = $v['status'];
			$list['date'] = $v['create_time'];
			$data['list'][$k] = $list;
		}
		$this->flexigridXML($data);
	}
	function export_csv()
	{
	    $conditions = '1 = 1';
	    if ($_POST['query'] != '')
	    {
	        $conditions .= " AND ".$_POST['qtype']." like '%" . $_POST['query'] . "%'";
	    }
	    if ($_GET['id'] != '') {
	        $ids = explode(',', $_GET['id']);
	        $conditions .= ' AND id' . db_create_in($ids);
	    }
	    $users = $this->_balance_account_mod->find(array(
	        'conditions' => $conditions,
	        'order' => "date desc",
	    ));
	
	    /* xls文件数组 */
	    $record_xls = array();
	    $record_title = array(
	        'deduct' 		=> 	'电子币',
	        'integral' 		=> 	'积分',
	        'date' => '日期',
	    );
	    $folder = 'balance_account_'.local_date('Ymdhis', gmtime());
	    $record_xls[] = $record_title;
	    foreach($users as $key=>$val)
	    {
	        $record_value['deduct']	=	$val['deduct'];
	        $record_value['integral']	=	$val['integral'];
	        $record_value['date']	=	$val['date'];
	        $record_xls[] = $record_value;
	    }
	    import('excelwriter.lib');
	    $ExcelWriter = new ExcelWriter(CHARSET, $folder);
	    $ExcelWriter->add_array($record_xls);
	    $ExcelWriter->output();
	}
	
}

?>
