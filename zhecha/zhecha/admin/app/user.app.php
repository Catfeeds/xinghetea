<?php

/* 会员控制器 */
class UserApp extends BackendApp
{
	var $_admin_mod;
    var $_user_mod;

    function __construct()
    {
        $this->UserApp();
    }

    function UserApp()
    {
        parent::__construct();
        $this->_user_mod =& m('member');
		$this->_admin_mod = & m('userpriv');
    }
	
	function index()
    {
        $this->import_resource(array(
			'script' => 'jquery.plugins/flexigrid.js,scrollbar/perfect-scrollbar.min.js',
		));
        $this->display('user.index.html');
    }
	
	function get_xml()
	{
        $conditions = '';
		if ($_POST['query'] != '') 
		{
			$conditions .= " AND ".$_POST['qtype']." like '%" . $_POST['query'] . "%'";
		}
		$order = 'user_id asc';
        $param = array('user_name','real_name','email','region_name','phone_mob','reg_time','last_login','logins','last_ip');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
		$pre_page = $_POST['rp']?intval($_POST['rp']):10;
		$page   =   $this->_get_page($pre_page);
		$users = $this->_user_mod->find(array(
            'join' => 'has_store,manage_mall',
            'fields' => 'this.*,store.store_id,userpriv.store_id as priv_store_id,userpriv.privs',
            'conditions' => '1=1' . $conditions,
            'limit' => $page['limit'],
            'order' => $order,
            'count' => true,
        ));
		$page['item_count'] = $this->_user_mod->getCount();
		$data = array();
		$data['now_page'] = $page['curr_page'];
        $data['total_num'] = $page['item_count'];
		foreach ($users as $k => $v){
			$list = array();
			$operation = "<a class='btn red' onclick=\"fg_delete({$k},'user')\"><i class='fa fa-trash-o'></i>删除</a>";
			$operation .= "<a class='btn blue' href='index.php?app=user&act=edit&id={$k}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
			$list['operation'] = $operation;
			$list['user_name'] = $v['user_name'];
			$list['real_name'] = $v['real_name'];
			$list['email'] = $v['email'];
			$list['phone_mob'] = $v['phone_mob'];
			$list['reg_time'] = local_date('Y-m-d',$v['reg_time']);
			$list['last_login'] = local_date('Y-m-d H:i:s',$v['last_login']);
			$list['last_ip'] = $v['last_ip'];
			$list['logins'] = $v['logins'];
			$list['if_admin'] = $v['priv_store_id'] == 0 && $v['privs'] != '' ? "<em class='yes'><i class='fa fa-check-circle'></i>是</em>" : "<a href='index.php?app=admin&act=add&id={$k}' onclick=\"parent.openItem('admin_manage', 'user')\">设为管理员</a>";
			$data['list'][$k] = $list;
		}
		$this->flexigridXML($data);
	}

    function add()
    {
        if (!IS_POST)
        {
            $this->assign('user', array(
                'gender' => 0,
            ));
            $ms =& ms();
            $this->assign('set_avatar', $ms->user->set_avatar());
            $this->display('user.form.html');
        }
        else
        {
            $user_name = trim($_POST['user_name']);
            $password  = trim($_POST['password']);
            $email     = trim($_POST['email']);
			$phone_mob = trim($_POST['phone_mob']);
            $real_name = trim($_POST['real_name']);
            $gender    = trim($_POST['gender']);
            $im_qq     = trim($_POST['im_qq']);
            $im_msn    = trim($_POST['im_msn']);

            if (strlen($user_name) < 3 || strlen($user_name) > 15)
            {
                $this->json_error('user_length_limit');

                return;
            }

            if (strlen($password) < 6 || strlen($password) > 20)
            {
                $this->json_error('password_length_error');

                return;
            }

            if (!is_email($email))
            {
                $this->json_error('email_error');

                return;
            }
			
			if(!is_mobile($phone_mob)) 
			{
				$this->json_error('phone_mob_error');
				
				return;
			}

            /* 连接用户系统 */
            $ms =& ms();

            /* 检查名称是否已存在 */
            if (!$ms->user->check_username($user_name))
            {
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
            }
			
			
			
			/*  检查Email是否被注册过 */
			if(!$ms->user->check_email($email)){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
			}
			
			/*  检查手机是否被注册过 */
			if(!$ms->user->check_phone($phone_mob)){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
			}

            /* 保存本地资料 */
            $data = array(
                'real_name' => $_POST['real_name'],
                'gender'    => $_POST['gender'],
                'phone_mob' => $_POST['phone_mob'],
                'im_qq'     => $_POST['im_qq'],
		'locked'    => intval($_POST['locked']),
                'im_aliww'  => $_POST['im_aliww'],
                'reg_time'  => gmtime(),
            );

            /* 到用户系统中注册 */
            $user_id = $ms->user->register($user_name, $password, $email, $data);
            if (!$user_id)
            {
                $error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
            }
			// 如果开启了积分功能，则给新会员赠送积分
			$integral_mod=&m('integral');
			$data = array(
				'user_id'=> $user_id,
				'type'   => 'register_has_integral',
				'amount' => $integral_mod->_get_sys_setting('register_integral')
			);
			$integral_mod->update_integral($data);
			
            if (!empty($_FILES['portrait']))
            {
                $portrait = $this->_upload_portrait($user_id);
                if ($portrait === false)
                {
                    return;
                }

                $portrait && $this->_user_mod->edit($user_id, array('portrait' => $portrait));
            }
			
            $this->json_result('','add_ok');
        }
    }

    /*检查会员名称的唯一性*/
    function  check_user()
    {
          $user_name = empty($_GET['user_name']) ? null : trim($_GET['user_name']);
          if (!$user_name)
          {
              echo ecm_json_encode(false);
              return ;
          }

          /* 连接到用户系统 */
          $ms =& ms();
          echo ecm_json_encode($ms->user->check_username($user_name));
    }

    function edit()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
		//判断是否是系统初始管理员，如果是系统管理员，必须是自己才能编辑，其他管理员不能编辑系统管理员
        if ($this->_admin_mod->check_system_manager($id) && !$this->_admin_mod->check_system_manager($this->visitor->get('user_id')))
        {
            $this->show_warning('system_admin_edit');
            return;
        }
        if (!IS_POST)
        {
            /* 是否存在 */
            $user = $this->_user_mod->get_info($id);
            if (!$user)
            {
                $this->show_warning('user_empty');
                return;
            }

            $ms =& ms();
            $this->assign('set_avatar', $ms->user->set_avatar($id));
            $this->assign('user', $user);
            $this->assign('phone_tel', explode('-', $user['phone_tel']));
            $this->display('user.form.html');
        }
        else
        {
            $data = array(
                'real_name' => $_POST['real_name'],
                'gender'    => $_POST['gender'],
                'phone_mob' => $_POST['phone_mob'],
                'im_qq'     => $_POST['im_qq'],
				'locked'    => intval($_POST['locked']),
                'im_aliww'  => $_POST['im_aliww'],
            );
            if (!empty($_POST['password']))
            {
                $password = trim($_POST['password']);
                if (strlen($password) < 6 || strlen($password) > 20)
                {
                    $this->json_error('password_length_error');

                    return;
                }
            }
            if (!is_email(trim($_POST['email'])))
            {
                $this->json_error('email_error');

                return;
            }
			if(!is_mobile(trim($_POST['phone_mob']))) 
			{
				$this->json_error('phone_mob_error');
				
				return;
			}

            if (!empty($_FILES['portrait']))
            {
                $portrait = $this->_upload_portrait($id);
                $portrait && $data['portrait'] = $portrait;
            }
			
			$ms =& ms();    //连接用户系统
			
			/*  检查Email是否被注册过 */
			if(!$ms->user->check_email(trim($_POST['email']), $id)){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
			}
			
			/*  检查手机是否被注册过 */
			if(!$ms->user->check_phone(trim($_POST['phone_mob']), $id)){
				$error = current($ms->user->get_error());
                $this->json_error($error['msg']);

                return;
			}

            /* 修改本地数据 */
            $this->_user_mod->edit($id, $data);

            /* 修改用户系统数据 */
            $user_data = array();
            !empty($_POST['password']) && $user_data['password'] = trim($_POST['password']);
            !empty($_POST['email'])    && $user_data['email']    = trim($_POST['email']);
			!empty($_POST['phone_mob']) && $user_data['phone_mob'] = trim($_POST['phone_mob']);
            if (!empty($user_data))
            {
                $ms =& ms();
                $ms->user->edit($id, '', $user_data, true);
            }

            $this->json_result('','edit_ok');
        }
    }

    function drop()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->json_error('no_user_to_drop');
            return;
        }
        $admin_mod =& m('userpriv');
        if(!$admin_mod->check_admin($id))
        {
            $this->json_error('cannot_drop_admin');
            return;
        }

        $ids = explode(',', $id);

        /* 连接用户系统，从用户系统中删除会员 */
        $ms =& ms();
        if (!$ms->user->drop($ids))
        {
            $error = current($ms->user->get_error());
            $this->json_error($error['msg']);

            return;
        }
		
		/* 如果是第三方账号登陆进来的会员，则删掉相应的绑定数据 */
		$member_bind_mod = &m('member_bind');
		$member_bind_mod->drop('user_id '.db_create_in($ids));

        $this->json_result('','drop_ok');
    }
    
    function export_csv()
	{
		$conditions = '1=1';
		if ($_GET['id'] != '') {
            $ids = explode(',', $_GET['id']);
			$conditions .= ' AND user_id' . db_create_in($ids);
        }
		if ($_GET['query'] != '') 
		{
			$conditions .= " AND ".$_GET['qtype']." like '%" . $_GET['query'] . "%'";
		}
		$users = $this->_user_mod->find(array(
            'fields' => 'this.*',
            'conditions' => $conditions,
            'order' => "user_id asc"
        ));
		
		if(!$users) {
			$this->show_warning('no_such_user');
            return;
		}
		/* xls文件数组 */
		$record_xls = array();		
		$record_title = array(
			'user_name' 		=> 	'会员名',
    		'real_name' 		=> 	'真实姓名',
    		'email' 		=> 	'电子邮箱',
			'phone_mob' => '手机号码',
    		'im_qq' 		=> 	'QQ',
    		'im_ww' => 	'旺旺',
    		'reg_time' 	=> 	'注册时间',
			'last_login' => '最后登录时间',
			'last_ip' => '最后登录ip',
    		'logins' 	=> 	'登录次数',
		);
		$folder = 'user_'.local_date('Ymdhis', gmtime());
		$record_xls[] = $record_title;
		$amount = 0;
		foreach($users as $key=>$user)
    	{
			$record_value['user_name']	=	$user['user_name'];
			$record_value['real_name']	=	$user['real_name'];
			$record_value['email']	=	$user['email'];
			$record_value['phone_mob']	=	$user['phone_mob'];
			$record_value['im_qq']	=	$user['im_qq'];
			$record_value['im_ww']	=	$user['im_ww'];
			$record_value['reg_time']	=	local_date('Y/m/d H:i:s',$user['reg_time']);
			$record_value['last_login']	=	local_date('Y/m/d H:i:s',$user['last_login']);
			$record_value['last_ip']	=	$user['last_ip'];
			$record_value['logins']   =   $user['logins'];
        	$record_xls[] = $record_value;
    	}
		//$record_xls[] = array('会员总数:',count($users));
		import('excelwriter.lib');
		$ExcelWriter = new ExcelWriter(CHARSET, $folder);
		$ExcelWriter->add_array($record_xls);
		$ExcelWriter->output();
	}

    /**
     * 上传头像
     *
     * @param int $user_id
     * @return mix false表示上传失败,空串表示没有上传,string表示上传文件地址
     */
    function _upload_portrait($user_id)
    {
        $file = $_FILES['portrait'];
        if ($file['error'] != UPLOAD_ERR_OK)
        {
            return '';
        }

        import('uploader.lib');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->addFile($file);
        if ($uploader->file_info() === false)
        {
			$error = current($uploader->get_error());
            $this->json_error($error['msg']);
            return false;
        }

        $uploader->root_dir(ROOT_PATH);
        return $uploader->save('data/files/mall/portrait/' . ceil($user_id / 500), $user_id);
    }
}

?>
