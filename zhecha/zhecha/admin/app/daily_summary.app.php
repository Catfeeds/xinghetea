<?php

/* 积分管理控制器 */
class Daily_summaryApp extends BackendApp
{
	var $_daily_summary_mod;
    function __construct()
    {
        $this->Daily_summaryApp();
    }

    function Daily_summaryApp()
    {
        parent::__construct();
		$this->_daily_summary_mod =& m('daily_summary');
    }

	function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js',
		));
        $this->display('daily_summary.index.html');
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
		$users = $this->_daily_summary_mod->find(array(
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'order' => "date desc",
            'count' => true,
        ));
		$page['item_count'] = $this->_daily_summary_mod->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($users as $k => $v)
		{
			$list = array();
			$list['deduct'] = $v['deduct'];
			$list['integral'] = $v['integral'];
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
	    $users = $this->_daily_summary_mod->find(array(
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
	    $folder = 'daily_summary_'.local_date('Ymdhis', gmtime());
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
