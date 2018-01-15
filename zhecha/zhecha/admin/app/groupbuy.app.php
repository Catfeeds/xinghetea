<?php
/**
 * 后台团购管理控制器
 *
 */

class GroupbuyApp extends BackendApp
{
    var $_groupbuy_mod;

    function __construct()
    {
        $this->GroupbuyApp();
    }

    function GroupbuyApp()
    {
        parent::BackendApp();
        $this->_groupbuy_mod =& m('groupbuy');
    }
	
	function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js,inline_edit.js',
		));
        $this->display('groupbuy.index.html');
    }
	
	function get_xml()
	{
		$conditions = '';
		if ($_GET['closed'] == 1) {
            $conditions .= " AND closed=1";
        }
		if ($_POST['query'] != '') 
		{
			$conditions .= " AND ".$_POST['qtype']." like '%" . $_POST['query'] . "%'";
		}
		$order = 'group_id DESC';
        $param = array('group_name','store_name','state','start_time','end_time','views','recommended');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
		$pre_page = $_POST['rp']?intval($_POST['rp']):10;
		$page   =   $this->_get_page($pre_page);
		$groupbuys_list = $this->_groupbuy_mod->find(array(
            'conditions' => "1 = 1" . $conditions,
            'join'  => 'belong_store',
            'fields'=> 'this.*,s.store_name',
            'limit' => $page['limit'],
            'order' => $order,
            'count' => true
        ));
        $groupbuys = array();
        if ($ids = array_keys($groupbuys_list))
        {
            $quantity = $this->_groupbuy_mod->db->getAllWithIndex("SELECT group_id, sum(quantity) as quantity FROM ". DB_PREFIX ."groupbuy_log  WHERE group_id " . db_create_in($ids) . "GROUP BY group_id", array('group_id'));
        }
        foreach ($groupbuys_list as $key => $val)
        {
            $groupbuys[$key] = $val;
            $groupbuys[$key]['count'] = empty($quantity[$key]['quantity']) ? 0 : $quantity[$key]['quantity'];
        }
        $page['item_count'] = $this->_groupbuy_mod->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($groupbuys as $k => $v){
			$list = array();
			$operation = "<a class='btn red' onclick=\"fg_delete({$k},'groupbuy')\"><i class='fa fa-trash-o'></i>删除</a><a class='btn green' href='".SITE_URL."/index.php?app=groupbuy&id={$k}' target='_blank'><i class='fa fa-search-plus'></i>查看</a>";
			$list['operation'] = $operation;
			$list['group_name'] = "<a href='".SITE_URL."/index.php?app=groupbuy&id={$k}' target='_blank' title='{$v['group_name']}'>".$v['group_name']."</a>";
			$list['store_name'] = "<a href='".SITE_URL."/index.php?app=store&id={$v['store_id']}' target='_blank'>".$v['store_name']."</a>";
			$list['state'] = group_state($v['state']);
			$list['start_time'] = local_date('Y-m-d',$v['start_time']);
			$list['end_time'] = local_date('Y-m-d',$v['end_time']);
			$list['count'] = $v['count'];
			$list['min_quantity'] = $v['min_quantity'];
			$list['views'] = $v['views'];
			if($v['state'] == 1){
				$recommended = $v['recommended'] == 0 ? '<em class="no" ectype="inline_edit" fieldname="recommended" fieldid="'.$k.'" fieldvalue="0" title="'.Lang::get('editable').'"><i class="fa fa-ban"></i>否</em>' : '<em class="yes" ectype="inline_edit" fieldname="recommended" fieldid="'.$k.'" fieldvalue="1" title="'.Lang::get('editable').'"><i class="fa fa-check-circle"></i>是</em>';
			}else{
				$recommended = '-';
			}
			$list['recommended'] = $recommended;
			$data['list'][$k] = $list;
		}
		$this->flexigridXML($data);
	}

    function recommended()
    {
        $id = trim($_GET['id']);
        $ids = explode(',', $id);
        $this->_groupbuy_mod->edit(db_create_in($ids, 'group_id') . ' AND state = ' . GROUP_ON, array('recommended' => 1));
        if ($this->_groupbuy_mod->has_error())
        {
			$error = current($this->_groupbuy_mod->get_error());
            $this->json_error($error['msg']);
            exit;
        }
        $this->json_result('','recommended_success');
    }

    function drop()
    {
        $id = trim($_GET['id']);
        $ids = explode(',', $id);
        if (empty($ids))
        {
            $this->json_error("no_valid_data");
            exit;
        }
        $this->_groupbuy_mod->drop(db_create_in($ids, 'group_id'));
        if ($this->_groupbuy_mod->has_error())
        {
			$error = current($this->_groupbuy_mod->get_error());
            $this->json_error($error['msg']);
            exit;
        }
        $this->json_result('','drop_success');
    }

   function ajax_col()
   {
       $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data   = array();

       if (in_array($column ,array('recommended')))
       {
           $data[$column] = $value;
           $this->_groupbuy_mod->edit("group_id = " . $id . " AND state = " . GROUP_ON, $data);
           if(!$this->_groupbuy_mod->has_error())
           {
               echo ecm_json_encode(true);
           }
       }
       else
       {
           return ;
       }
       return ;
   }
}



?>