<?php

/* 消费对账控制器 */
class Account_summaryApp extends BackendApp
{
	var $_account_summary_mod;
    function __construct()
    {
        $this->Account_summaryApp();
    }

    function Account_summaryApp()
    {
        parent::__construct();
		$this->_account_summary_mod =& m('account_summary');
    }

	function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js',
		));
        $this->display('account_summary.index.html');
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
		$users = $this->_account_summary_mod->find(array(
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'order' => "date desc",
            'count' => true,
        ));
		$page['item_count'] = $this->_account_summary_mod->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($users as $k => $v)
		{
			$list = array();
			$list['type'] = $v['type'] == 'deposit'?'电子币':'积分';
			$list['yesterday_balance'] = $v['yesterday_balance'];
			$list['real_balance'] = $v['real_balance'];
			$list['status'] = $v['status'];
			$list['date'] = $v['date'];
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
	    $users = $this->_account_summary_mod->find(array(
	        'conditions' => $conditions,
	        'order' => "date desc",
	    ));
	
	    /* xls文件数组 */
	    $record_xls = array();
	    $record_title = array(
	        'type' 		=> 	'类型',
	        'yesterday_account' 		=> 	'昨日余额',
	        'real_account' => '实际余额',
	        'status' => '状态',
	        'date' => '日期',
	    );
	    $folder = 'account_summary_'.local_date('Ymdhis', gmtime());
	    $record_xls[] = $record_title;
	    foreach($users as $key=>$val)
	    {
	        $record_value['type']	=	$val['type'] == 'deposit'?'电子币':'积分';
	        $record_value['yesterday_balance']	=	$val['yesterday_balance'];
	        $record_value['real_balance']	=	$val['real_balance'];
	        $record_value['status']	=	$val['status'];
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
