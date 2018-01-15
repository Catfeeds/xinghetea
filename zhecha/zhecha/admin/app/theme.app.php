<?php

/**
 *    主题设置控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class ThemeApp extends BackendApp
{
    /* 列表 */
    function index()
    {
		$type = isset($_GET['type']) ? $_GET['type'] : '';
        $themes = list_template('mall',$type);
        $theme_list = array();
        foreach ($themes as $theme)
        {
            $theme_list[$theme] = list_style('mall', $theme,$type);
        }
        $this->assign('curr_template_name', Conf::get('template_name'));
        $this->assign('curr_style_name', Conf::get('style_name'));
		$this->assign('curr_wap_template_name', Conf::get('wap_template_name'));
        $this->assign('curr_wap_style_name', Conf::get('wap_style_name'));
        $this->assign('theme_list', $theme_list);

        $this->display('theme.index.html');
    }
	
	//编辑店铺主题数据 by cengnlaeng
	function config()
    {
		$template = $_GET['template'];
		$style = $_GET['style'];
		$type = isset($_GET['type']) ? $_GET['type'] : '';
        $themes = list_template('mall',$type);
		/*
        if (empty($themes) || empty($themes[$template.'|'.$style]))
        {
            $this->show_warning('no_themes');

            return;
        }
		*/
		$decoration_mod = &af('decoration',array('template'=>$template,'type'=>$_GET['type']));
		$form_data = $decoration_mod->_get_form_data();
		$this->assign('form_data',$form_data);
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('theme_edit'));
		$this->display('theme.config.html');
    }
	
	
	function edit()
	{
		$template = $_GET['template'];
		$style = $_GET['style'];
		$model = $_GET['model'];
		$type = $_GET['type'];
		$decoration_mod = &af('decoration',array('template'=>$template,'type'=>$_GET['type'],'store_id'=>$this->visitor->get('manage_store')));
		/*
		if (empty($themes) || empty($themes[$template.'|'.$style]))
        {
            $this->show_warning('no_themes');
            return;
        }
		*/
		$config_data =$decoration_mod-> _get_config_data();
		$current_config = $config_data[$model] ? $config_data[$model] : array();
		if(!IS_POST)
		{
			$form_data = $decoration_mod->_get_form_data($model);
			foreach($form_data['config'] as $k => $v)
			{
				$form_data['config'][$k]['value'] = $current_config[$k];
				if(is_file(ROOT_PATH.'/'.$current_config[$k]) && file_exists(ROOT_PATH.'/'.$current_config[$k]))
				{
					$image_info = getimagesize(ROOT_PATH.'/'.$current_config[$k]);
					if($image_info[0] > 544)
					{
						$form_data['config'][$k]['width'] = 544;
					}
				}
				if(($v['type'] == 'select') && ($v['data'] == 'recommand'))
				{
					$form_data['config'][$k]['items'] = $this->_get_recommends();
				}
			}
			$this->assign('form_data',$form_data);
			$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('theme_edit'));
			$this->display('theme.form.html');
		}
		else
		{
			$new_data = array();
			$files = $this->_upload_file();
			$config_data[$model] = array_merge($current_config,array_merge($files,$_POST['config']));
			$decoration_mod->setAll($config_data);
			$this->json_result('','edit_ok');
		}
    }
	
	/**
     *    上传图片
     *
     *    @author    psmoban
     *    @return    void
     */
	 
	function _upload_file()
    {
        import('uploader.lib');
		$data = $files = array();
		for($i=0;$i<count($_FILES['file']['name']);$i++)
		{
            foreach ($_FILES['file'] as $key => $value)
            {
				foreach($value as $k=>$v){
                	$files[$k][$key] = $v;
				}
            }  
		}
		if(!$files)  return $data;
		foreach($files as $key=>$file)
		{
			if ($file['error'] == UPLOAD_ERR_OK && $file !='')
			{
				$uploader = new Uploader();
            	$uploader->addFile($file);
				$uploader->allowed_type(IMAGE_FILE_TYPE);
            	if ($uploader->file_info() === false)
            	{
					$error = current($uploader->get_error());
                	$this->json_error($error['msg']);
                	continue;
            	}
            	$uploader->root_dir(ROOT_PATH);
            	$data[$key] = $uploader->save('data/files/mall/template', date('Ymdhis',gmtime()).rand());
        	}
		}
        return $data;
    }
	
    function set()
    {
        $template_name = isset($_GET['template_name']) ? trim($_GET['template_name']) : null;
        $style_name = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;
        if (!$template_name)
        {
            $this->json_error('no_such_template');

            return;
        }
        if (!$style_name)
        {
            $this->json_error('no_such_style');

            return;
        }
        $af_setting =& af('settings');
		if($_GET['type'] == 'mobile')
		{
			$data = array('wap_template_name' => $template_name, 'wap_style_name' => $style_name);
		}
		else
		{
			$data = array('template_name' => $template_name, 'style_name' => $style_name);
		}
        $af_setting->setAll($data);

        $this->json_result('','set_theme_successed');
    }
    function preview()
    {
		$_POST['type'] == 'mobile' &&  $wap_path = '/mobile';
        $template_name = isset($_POST['template_name']) ? trim($_POST['template_name']) : null;
        $style_name = isset($_POST['style_name']) ? trim($_POST['style_name']) : null;
        if (!$template_name)
        {
            $this->show_warning('no_such_template');

            return;
        }
        if (!$style_name)
        {
            $this->show_warning('no_such_style');

            return;
        }
        header('Location:' . SITE_URL .$wap_path. '/themes/mall/' .  $template_name . '/styles/' . $style_name . '/screenshot.jpg');
    }
	/* 取得推荐类型 */
    function _get_recommends()
    {
        $recom_mod =& bm('recommend', array('_store_id' => 0));
        $recommends = $recom_mod->get_options();
       // $recommends[REC_NEW] = Lang::get('recommend_new');

        return $recommends;
    }

    /* 取得分类列表 */
    function _get_gcategory_options($layer = 0)
    {
        $gcategory_mod =& bm('gcategory', array('_store_id' => 0));
        $gcategories = $gcategory_mod->get_list();

        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');

        return $tree->getOptions($layer);
    }
}

?>