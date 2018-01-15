<?php

/**
 *    满折满减管理控制器
 *
 *    @author   psmb
 *    @usage    none
 */
class Seller_fullpreferApp extends StoreadminbaseApp
{
	var $_appid;
    var $_store_id;
    var $_fullprefer_mod;
	var $_appmarket_mod;

    /* 构造函数 */
    function __construct()
    {
         $this->Seller_fullpreferApp();
    }

    function Seller_fullpreferApp()
    {
        parent::__construct();
		$this->_appid     = 'fullprefer';
        $this->_store_id  = intval($this->visitor->get('manage_store'));
        $this->_fullprefer_mod = &bm('promotool_setting', array('_store_id' => $this->_store_id, '_appid' => $this->_appid));
		$this->_appmarket_mod = &m('appmarket');

    }

    function index()
    {
		$fullprefer = $this->_fullprefer_mod->get_info();
		
		if(!IS_POST)
		{
			$this->import_resource(array('script' => 'jquery.plugins/jquery.validate.js'));
			
			/* 当前位置 */
			$this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
							 LANG::get('fullprefer'), 	'index.php?app=seller_fullprefer',
							 LANG::get('fullprefer_setting'));
	
			/* 当前用户中心菜单 */
			$this->_curitem('fullprefer');;
	
			/* 当前所处子菜单 */
			$this->_curmenu('fullprefer_setting');
			
			$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('fullprefer'));
			$this->assign('appAvailable', $this->_appmarket_mod->getCheckAvailableInfo($this->_appid, $this->_store_id));
			$this->assign('fullprefer', $fullprefer);
			$this->display('seller_fullprefer.index.html');
		}
		else
		{
			if(($appAvailable = $this->_appmarket_mod->getCheckAvailableInfo($this->_appid, $this->_store_id)) !== TRUE) {
				$this->show_warning($appAvailable['msg']);
				return;
			}
			
			$post 	= $_POST['prefer'];
			$status = intval($_POST['status']);
			
			if($this->checkPostData() === TRUE) {
				$post['amount'] 	= $this->_filter_price($post['amount']);
				if($post['type'] == 'discount') {
					$post['discount']  	= round(floatval($post['discount']),1); 
					unset($post['decrease']);
				}
				else {
					$post['decrease']  	= $this->_filter_price($post['decrease']);
					unset($post['discount']); 
				}
			}
			
			$data = array(
				'store_id'  => $this->_store_id,
				'appid' 	=> $this->_appid,
				'rules' 	=> serialize($post),
				'status' 	=> $status,
				'add_time'	=> gmtime()
			);
			if($fullprefer){
				$this->_fullprefer_mod->edit($fullprefer['psid'], $data);
				$this->batch_fullprefer('edit',$fullprefer['psid'], $data);
			} else {
				$id = $this->_fullprefer_mod->add($data);
				$this->batch_fullprefer('add',$id, $data);
			}
			$this->show_message('handle_ok');
		}
    }
	function checkPostData()
	{
		$prefer	= $_POST['prefer'];
		
		if($this->_filter_price($prefer['amount']) <= 0) {
			$this->show_warning('not_allempty');
			exit;
		}
		
		if($prefer['type'] == 'discount')
		{
			$discount = $prefer['discount'];
			if($discount <= 0 || $discount >= 10) {
				$this->show_warning('discount_invalid');
				exit;
			}
		}
		elseif($prefer['type'] == 'decrease')
		{
			if($this->_filter_price($prefer['decrease']) <= 0) {
				$this->show_warning('price_le_0');
				exit;
			}
			if($this->_filter_price($prefer['amount']) <= $this->_filter_price($prefer['decrease'])) {
				$this->show_warning('amount_le_decrease');
				exit;
			}
			
		}
		else {
			$this->show_warning('pls_select_type');
			exit;
		}
		return true;
	}
	
	/**
     *    三级菜单
     *
     *    @author    Garbin
     *    @return    void
     */
    function _get_member_submenu()
    {
        $menus = array(
			array(
				'name'  => 'fullprefer_setting',
				'url'   => 'index.php?app=seller_fullprefer',
			),
        );
        return $menus;
    }
	/* 价格过滤，返回非负浮点数 */
    function _filter_price($price)
    {
        return abs(floatval($price));
    }
    /*
     * 将优惠信息推送到所有店铺
     * by PwordC
     */
    function batch_fullprefer($type,$id=0,$data){
        //获取其余所有店铺id
        $store_mod =& m('store');
        $store_id = $this->visitor->get('store_id');
        $other_store_ids = $store_mod->find(array(
            'conditions' => 'store_id != '.$store_id,
            'fields' => 'store_id',
        ));
        $store_ids = array();
        foreach ($other_store_ids as $k=>$v){
            array_push($store_ids,$v['store_id']);
        }
        if($type=='edit'){
            $info = $this->_fullprefer_mod->get("psid={$id}");
            $psids = $this->_fullprefer_mod->find(array(
                'conditions' => "appid = '".$info['appid']."' and store_id !=".$store_id,
                'fields' => 'psid,store_id',
            ));
            foreach ($psids as $k=>$v){
                 $data['store_id'] = $v['store_id'];
                 $this->_fullprefer_mod->edit($v['psid'],$data);
            }
        }
        if($type=='add'){
            foreach ($store_ids as $k=>$v){
                $data['store_id'] = $v;
                $data['parent_id'] = $id;
                $this->_fullprefer_mod->add($data);
            }
        }
    }
}


?>