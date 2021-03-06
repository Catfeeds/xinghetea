<?php

/**
 *    主题设置控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_themeApp extends StoreadminbaseApp
{
    function index()
    {
        extract($this->_get_themes());

        if (empty($themes))
        {
            $this->show_warning('no_themes');

            return;
        }

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('theme_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_theme');
        $this->_curmenu('pc_theme');
		Psmb_init()->check_template_editable($themes);
        $this->assign('themes', $themes);
        $this->assign('curr_template_name', $curr_template_name);
        $this->assign('curr_style_name', $curr_style_name);
        $this->assign('manage_store', $this->visitor->get('manage_store'));
        $this->assign('id',$this->visitor->get('user_id'));
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_theme'));
        $this->display('my_theme.index.html');
    }
	
	
	
	function mobile()
    {
        extract($this->_get_themes(true));

        if (empty($themes))
        {
            $this->show_warning('no_themes');

            return;
        }

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('theme_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_theme');
        $this->_curmenu('mobile_theme');
		$themes = Psmb_init()->check_template_editable($themes,true);
        $this->assign('themes', $themes);
        $this->assign('curr_template_name', $curr_template_name);
        $this->assign('curr_style_name', $curr_style_name);
        $this->assign('manage_store', $this->visitor->get('manage_store'));
        $this->assign('id',$this->visitor->get('user_id'));
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_theme'));
        $this->display('my_theme.index.html');
    }
	//编辑店铺主题数据 by cengnlaeng
	function config()
    {
        extract($this->_get_themes(true));
		$template = $_GET['template'];
		$style = $_GET['style'];
		$type = $_GET['type'];

        if (empty($themes) || empty($themes[$template.'|'.$style]))
        {
            $this->show_warning('no_themes');

            return;
        }
		
		$this->import_resource(array(
          'script' => array(
                   array(
                      'path' => 'dialog/dialog.js',
                      'attr' => 'id="dialog_js"',
                   ),
                   array(
                      'path' => 'jquery.ui/jquery.ui.js',
                      'attr' => '',
                   ),
          ),
        ));

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('theme_edit'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_theme');
        $this->_curmenu('theme_edit');
		
		$decoration_mod = &af('decoration',array('template'=>$template,'type'=>$_GET['type'],'store_id'=>$this->visitor->get('manage_store')));
		$form_data = $decoration_mod->_get_form_data();
		$this->assign('form_data',$form_data);
		$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('theme_edit'));
		$this->display('my_theme.config.html');
    }
	
	
	function edit()
	{
		extract($this->_get_themes(true));
		$template = $_GET['template'];
		$style = $_GET['style'];
		$model = $_GET['model'];
		$type = $_GET['type'];
		$decoration_mod = &af('decoration',array('template'=>$template,'type'=>$_GET['type'],'store_id'=>$this->visitor->get('manage_store')));
		if (empty($themes) || empty($themes[$template.'|'.$style]))
        {
            $this->show_warning('no_themes');
            return;
        }
		$config_data =$decoration_mod-> _get_config_data();
		$current_config = $config_data[$model];
		if(!IS_POST)
		{
			$form_data = $decoration_mod->_get_form_data($model);
			foreach($form_data['config'] as $k => $v)
			{
				$form_data['config'][$k]['value'] = $current_config[$k];
				if(is_file($current_config[$k]) && file_exists(ROOT_PATH.'/'.$current_config[$k]))
				{
					$image_info = getimagesize($current_config[$k]);
					if($image_info[0] > 544)
					{
						$form_data['config'][$k]['width'] = 544;
					}
				}
			}
			$this->assign('form_data',$form_data);
			$this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('theme_edit'));
			$this->display('my_theme.form.html');
		}
		else
		{
			$new_data = array();
			$files = $this->_upload_file();
			$config_data[$model] = array_merge($current_config,array_merge($files,$_POST['config']));
			$decoration_mod->setAll($config_data);
			$this->show_message('edit_theme_successed');
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
		for($i=0;$i<count($_FILES['config']['name']);$i++)
		{
            foreach ($_FILES['config'] as $key => $value)
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
                	$this->show_warning($uploader->get_error());
                	return false;
            	}
            	$uploader->root_dir(ROOT_PATH);
            	$data[$key] = $uploader->save('data/files/store_'.$this->visitor->get('manage_store').'/config_images', date('Ymdhis',gmtime()).rand());
        	}
		}
        return $data;
    }
    function set()
    {
        $template_name = isset($_GET['template_name']) ? trim($_GET['template_name']) : null;
        $style_name = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;
		$type = isset($_GET['type']) ? trim($_GET['type']) : null;
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
        extract($this->_get_themes($type));
        $theme = $template_name . '|' . $style_name;

        /* 检查是否可以选择此主题 */
        if (!isset($themes[$theme]))
        {
            $this->json_error('no_such_theme');

            return;
        }
        $model_store =& m('store');
		if($type == 'mobile')
		{
			$data = array('wap_theme' => $theme);
		}
		else
		{
			$data = array('theme' => $theme);
		}
        $model_store->edit($this->visitor->get('manage_store'),$data);

        $this->json_result('', 'set_theme_successed');
    }

    function _get_themes($wap=false)
    {
        /* 获取当前所使用的风格 */
        $model_store =& m('store');
        $store_info  = $model_store->get($this->visitor->get('manage_store'));
        $theme = !empty($store_info['theme']) ? $store_info['theme'] : 'default|default';
		$wap_theme = !empty($store_info['wap_theme']) ? $store_info['wap_theme'] : 'default|default';

        /* 获取待选主题列表 */
        $model_grade =& m('sgrade');
        $grade_info  =  $model_grade->get($store_info['sgrade']);
		if($wap == true)
		{
			list($curr_template_name, $curr_style_name) = explode('|', $wap_theme);
        	$grade_info['wap_skins'] && $skins = explode(',', $grade_info['wap_skins']);
		}
		else
		{
			list($curr_template_name, $curr_style_name) = explode('|', $theme);
			$grade_info['skins'] && $skins = explode(',', $grade_info['skins']);
		}
        $themes = array();
		if(!empty($skins))
		{
			foreach ($skins as $skin)
			{
				list($template_name, $style_name) = explode('|', $skin);
				$themes[$skin] = array('template_name' => $template_name, 'style_name' => $style_name);
			}
		}
		else
		{
			$themes['default|default'] = array('template_name' => 'default', 'style_name' => 'default');
		}

        return array(
            'curr_template_name' => $curr_template_name,
            'curr_style_name'    => $curr_style_name,
            'themes'             => $themes
        );
    }
	/*三级菜单*/
    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name' => 'pc_theme',
                'url'  => 'index.php?app=my_theme',
            ),
			array(
                'name' => 'mobile_theme',
                'url'  => 'index.php?app=my_theme&act=mobile',
            ),
        );
		if(ACT == 'config')
		{
			$menus []= array(
					'name' => 'theme_edit',
					'url'  => 'index.php?app=my_theme&act=config',
			);
		}
        return $menus;
    }
}
?>