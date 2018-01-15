<?php
/**
 * 找回密码控制器
 * @author andcpp
 */
class Find_passwordApp extends MallbaseApp
{
    var $_member_mod;
    function __construct()
    {
        $this->Find_passwordApp();
    }

    function Find_passwordApp()
    {
        parent::FrontendApp();
        $this->_member_mod = &m("member");
    }

    /**
     * 显示文本框及处理提交的用户信息
     *
     */
    function index()
    {
       if(!IS_POST)
       {
		   $this->import_resource(array(
            	'script' => array(
					array(
                    	'path' => 'mobile/dialog/dialog.js',
                    	'attr' => 'id="dialog_js"',
                	),
                	array(
                    	'path' => 'mobile/jquery.ui/jquery.ui.js',
                    	'attr' => '',
					),
                	array(
                    	'path' => 'mobile/jquery.plugins/jquery.validate.js',
                    	'attr' => '',
                	),
				),
            	'style' =>  'mobile/jquery.ui/themes/ui-lightness/jquery.ui.css',
        	));
			
			/* 配置seo信息 */
        	$this->_config_seo(array('title' => Lang::get('find_password') . ' - ' . Conf::get('site_title')));
			$this->_get_curlocal_title('find_password');
			$this->display("find_password.html");
       }
       else
       {
			$user_name	= trim($_POST['user_name']);
		   	$codeType 	= trim($_POST['codeType']);
			$code     	= trim($_POST['code']);
			
			if (empty($user_name) || empty($_POST['captcha']) || empty($codeType) || empty($code))
           	{
               	$this->show_warning("unsettled_required");
               	return;
           	}
           	if (base64_decode($_SESSION['captcha']) != strtolower($_POST['captcha']))
           	{
               	$this->show_warning("captcha_faild");
               	return;
           	}
		   
			$member = $this->_member_mod->get(array('conditions'=>'user_name="'.$user_name.'"', 'fields'=>'user_id, email, phone_mob'));
			if($codeType == 'email')
			{
				if(($_SESSION['email_code'] != md5($member['email'].$code)) || ($_SESSION['last_send_time_email_code'] + 120 < gmtime())) {
					$this->show_warning('email_code_check_failed');
					return;
						
				}
			}
			elseif($codeType == 'phone')
			{
				if(($_SESSION['phone_code'] != md5($member['phone_mob'].$code)) || ($_SESSION['last_send_time_phone_code'] + 120 < gmtime())) {
					$this->show_warning('phone_code_check_failed');
					return;
						
				}
			} else {
				exit(0);
			}

           /* 至此，验证通过，为该用户重置密码 */
		   $activation = ($codeType == 'email') ? $_SESSION['email_code'] : $_SESSION['phone_code'];
		   $this->_member_mod->edit($member['user_id'], array('activation' => $activation));
		   header('Location: index.php?app=find_password&act=set&id='.$member['user_id'].'&key='.$activation);
       }
    }

    /**
     * 显示设置密码及处理提交的新密码信息
     *
     */
    function set()
    {
		$id  = intval($_GET['id']);
		$key = trim($_GET['key']);
		
		if(!$id || empty($key))
		{
			$this->show_warning("request_error");
			return;
		}
			
		if(($_SESSION['email_code'] && ($_SESSION['email_code'] != $key)) || ($_SESSION['phone_code'] && ($_SESSION['phone_code'] != $key)))
		{
			$this->show_warning("session_expire");
			return;
		}
		
		
        if (!IS_POST)
        {
            $this->import_resource('mobile/jquery.plugins/jquery.validate.js');
			
            /* 配置seo信息 */
        	$this->_config_seo(array('title' => Lang::get('find_password') . ' - ' . Conf::get('site_title')));
			$this->_get_curlocal_title('find_password');
			$this->display("find_password.step2.html");
        }
        else
        {
			$member = $this->_member_mod->get(array('conditions' => 'user_id='.$id, 'fields'=>'activation'));
			if(!$member || ($member['activation'] != $key))
			{
				$this->show_warning("request_error");
				return;
			}
			
			$password 		= trim($_POST['password']);
			$confirm_password 	= trim($_POST['confirm_password']);
			
            if (empty($password) || empty($confirm_password))
            {
                $this->show_warning("unsettled_required");
                return ;
            }
            if ($password != trim($confirm_password))
            {
                $this->show_warning("password_not_equal");
                return ;
            }
			
            $passlen = strlen($password);
            if ($passlen < 6 || $passlen > 20)
            {
                $this->show_warning('password_length_error');

                return;
            }
			
            $ms =& ms();        //连接用户系统
            $ms->user->edit($id, '', array('password' => $password), true); //强制修改
            if ($ms->user->has_error())
            {
                $this->show_warning($ms->user->get_error());

                return;
            }
			$this->_member_mod->edit($id, array('activation' => ''));
			unset($_SESSION['phone_code'], $_SESSION['last_send_time_phone_code'], $_SESSION['email_code'],$_SESSION['last_send_time_email_code']);
            
			header('Location: index.php?app=find_password&act=success');
        }
    }

	function success()
	{
		 /* 配置seo信息 */
        $this->_config_seo(array('title' => Lang::get('setPassword_successed') . ' - ' . Conf::get('site_title')));
		$this->_get_curlocal_title('setPassword_successed');
		$this->assign('ret_url', url('app=member'));
		$this->display("find_password.step3.html");
	}
	
}
?>
