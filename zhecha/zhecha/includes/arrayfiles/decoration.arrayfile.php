<?php
class DecorationArrayfile extends BaseArrayfile
{
	var $template,$type,$store_id,$mobile_dir,$tempalte_dir;
	
    function __construct()
    {
		extract(current(func_get_args()));
		$this->template = $template;
		$this->type = $type;
		$this->store_id = $store_id;
		$this->mobile_dir = $this->type == 'mobile' ? '/mobile' :'';
		$this->tempalte_dir = $this->store_id ? 'store' : 'mall';
        $this->DecorationArrayfile();
    }

    function DecorationArrayfile()
    {
		$this->_filename = ROOT_PATH . '/data/page_config'.$this->mobile_dir.'/'.$this->tempalte_dir.$this->store_id.'.'.$this->template.'.inc.php';
    }
	
	function _get_form_data($model='')
	{
		$form_file = ROOT_PATH.$this->mobile_dir.'/themes/'.$this->tempalte_dir.'/'.$this->template.'/form.info.php';
		if(file_exists($form_file)) $form_data = include($form_file);else return;
		return $model? $form_data[$model] : $form_data;
	}
	
	function _get_sample_data()
	{
		$sample_file = ROOT_PATH . '/data/page_config'.$this->mobile_dir.'/'.$this->tempalte_dir.'.sample.'.$this->template.'.inc.php';
		if(file_exists($sample_file)) return include($sample_file);else return;
	}
	
	function _get_config_data()
	{
		$config_data = $this->getAll();
		$sample_data = $this->_get_sample_data();
		return $config_data ? $config_data : $sample_data;
	}
	
	function _get_decoration_data($moduleKey='')
	{
		$config_data = $this->_get_config_data();
		$form_data = $this->_get_form_data()?$this->_get_form_data():array();
	
		if(!empty($moduleKey) && !empty($form_data[$moduleKey]) && !empty($config_data[$moduleKey]))
		{
			$data[$moduleKey] = Psmb_init()->deal_config_data($form_data[$moduleKey]['model'],$config_data[$moduleKey]);
		}
		else
		{
			foreach($form_data  as $key => $val)
			{
				$data[$key] = Psmb_init()->deal_config_data($val['model'],$config_data[$key]);	
			}	
		}
		return $data;
	}
}
?>