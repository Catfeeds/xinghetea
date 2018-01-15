<?php

define('UPLOAD_DIR', 'data/files/mall/settings');

/**
 *    基本设置控制器
 *
 *    @author    Hyber
 *    @usage    none
 */
class Store_settingApp extends BackendApp
{
    public $_store_setting_mod;
    
    function __construct()
    {
        $this->Store_settingApp();
    }

    function Store_settingApp()
    {
        parent::BackendApp();
        $this->_store_setting_mod =& m('store_setting');
    }
    
    function index(){
        $this->vip_price();
    }
    
    function vip_price(){
        if (!IS_POST)
        {
            if ($vip_price_setting = $this->_store_setting_mod->get("appid='vip_price'")){
                
                if ($vip_price_setting['status'] == 1){
                    $vip_price_setting['price'] = unserialize($vip_price_setting['config']);
                }
                $this->assign('vip_price_setting',$vip_price_setting);
            }
            $this->display('store_setting.vip_price.html');
        }
        else
        {
            $data=array();
            $data['status']   = $_POST['vip_price_allow'];
            $data['appid']    = 'vip_price';
            $price['price_1'] = $_POST['price_1'];
            $price['price_2'] = $_POST['price_2'];
            $price['price_3'] = $_POST['price_3'];
            $price['price_4'] = $_POST['price_4'];
            $price['price_5'] = $_POST['price_5'];
            $data['config']   = serialize($price);
            //dump($data);die();
            if ($vip_price_setting = $this->_store_setting_mod->get("appid='vip_price'")){
                $id = $vip_price_setting['id'];
                $this->_store_setting_mod->edit($id,$data);
            }else{
                $this->_store_setting_mod->add($data);
            }
        
           $this->json_result(array('rel'=>1),'edit_store_setting_successed_vip_price');
        }
        
    }
    /**
     *    pv显示及名称设置
     *
     *    @author    PwordC
     *    @param     
     *    @return    
     */
    function pv_setting(){
        if (!IS_POST)
        {
            if ($pv_setting = $this->_store_setting_mod->get("appid='pv'")){
        
                if ($pv_setting['status'] == 1){
                    $pv_setting['pv_view'] = unserialize($pv_setting['config']);
                }
                $this->assign('pv_setting',$pv_setting);
            }
            $this->display('store_setting.pv_setting.html');
        }
        else
        {
            $data=array();
            $data['status']   = $_POST['pv_setting_allow'];
            $data['appid']    = 'pv';
            $pv_view          = $_POST['pv_view']; 
            $data['config']   = serialize($pv_view);
            //dump($a);die();
            if ($pv_setting = $this->_store_setting_mod->get("appid='pv'")){
                $id = $pv_setting['id'];
                $this->_store_setting_mod->edit($id,$data);
            }else{
                $this->_store_setting_mod->add($data);
            }
        
            $this->json_result(array('rel'=>1),'edit_store_setting_successed');
        }
    }
    
    /**
     *    是否允许添加产品
     *
     *    @author    PwordC
     *    @param     
     *    @return    
     */
    function add_goods(){
        if (!IS_POST)
        {
            if ($add_goods = $this->_store_setting_mod->get("appid='add_goods'")){
                       
                $this->assign('add_goods',$add_goods);
            }
            $this->display('store_setting.add_goods.html');
        }
        else
        {
            $data=array();
            $data['status']   = $_POST['add_goods_allow'];
            $data['appid']    = 'add_goods';
            //dump($a);die();
            if ($add_goods = $this->_store_setting_mod->get("appid='add_goods'")){
                $id = $add_goods['id'];
                $this->_store_setting_mod->edit($id,$data);
            }else{
                $this->_store_setting_mod->add($data);
            }
        
            $this->json_result(array('rel'=>1),'edit_store_setting_successed');
        }
    }


}

?>
