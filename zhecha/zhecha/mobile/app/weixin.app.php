<?php

class WeixinApp extends MallbaseApp
{
	var $weixin;
	var $member_mod;
	var $bind_mod;
	var $weixin_reply_mod;
	
    function __construct()
    {
        $this-> WeixinApp();
    }
    function  WeixinApp()
    {
        parent::__construct();
		$this->bind_mod = &m('member_bind');
		$this->member_mod = &m('member');
		$this->weixin_reply_mod = &m('weixin_reply');
		
		import('weixin.lib');
		$this->weixin = new weixin();
    }
	
    function index()
    {
		$this->weixin->valid(); // 验证
		$postData = $this->weixin->getPostData();
		if($postData['MsgType'] == 'event') //接收事件推送
		{
			switch($postData['Event'])
			{
				case 'subscribe'://关注事件
					$reply = $this->weixin_reply_mod->get("user_id=0 AND action='beadded'");
					if($reply['type'])//图文消息
					{
						$content[] = $reply;
					}
					else
					{
						$content = $reply['content'] ? sprintf($reply['content'],'<a href="' . SITE_URL . '/mobile/index.php?app=weixin&act=login">【自动登录】</a>','<a href="' . SITE_URL . '/mobile/index.php?app=weixin&act=bind">【绑定】</a>') : '欢迎关注' . $this->weixin->_config['name'];
					}
				break;
				
				case 'CLICK'://点击菜单拉取消息时的事件推送,后台设定为图文消息
					$reply = $this->weixin_reply_mod->get(intval($postData['EventKey']));
					if(empty($reply)){
						return;
					}
					$content[] = $reply;
				break;
				default:
				break;
			}
		}else{
			//先执行回复命令，再找关键字，再自动回复
			$getContent = $postData['Content'];
			//关键词命令
			$getContent && $reply = $this->checkKeywords($getContent);
			
			if($reply){//关键字回复
				if($reply['type'])//图文消息
				{
					$content[] = $reply;
				}
				else
				{
					$content = $reply['content']; 
				}
			}else{//自动回复
				$autoreply = $this->weixin_reply_mod->get("user_id=0 AND action='autoreply'");
				if($autoreply){
					if($autoreply['type'])//图文消息
					{
						$content[] = $autoreply;
					}
					else
					{
						$content = $autoreply['content']; 
					}
				}
			}
		}
		$resultStr = $this->weixin->getMsgXML($postData['FromUserName'], $postData['ToUserName'], $content);
		if($resultStr){
			 echo $resultStr;
			 exit;
		}
	}
	
	function checkKeywords($word = '')
	{
		$replys = $this->weixin_reply_mod->find("user_id=0 AND keywords like'%".$word."%'");
		foreach($replys as $key => $val){
			$keywords = explode(',',str_replace('，',',',$val['keywords']));
			if(in_array($word,$keywords)){
				return $val;//找到匹配即返回
			}
		}
		return false;
	}
	
	function getCode($redirect_uri)
	{
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->weixin->_config['appid']."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=".mt_rand()."#wechat_redirect";
		header("location:$url");
	}
	
	function getOpenid($code)
	{
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->weixin->_config['appid']."&secret=".$this->weixin->_config['appsecret']."&code=".$code."&grant_type=authorization_code";
		$result = ecm_curl($url);
		if(!$result)
		{
			return false;
		}
		$result = json_decode($result, true);
		return $result['openid'];
	}
	
	function login()
	{
		if(!$this->visitor->get('user_id')){
			$redirect_uri = urlencode(SITE_URL . '/mobile/index.php?app=weixin&act=autoRegister');
			$this->getCode($redirect_uri);
		}else{
			header("location:index.php?app=member");
		}
	}
	
	function bind()
	{
		$redirect_uri = urlencode(SITE_URL . '/mobile/index.php?app=weixin&act=memberBind');
		$this->getCode($redirect_uri);
	}
	
	/**
     *  自动注册并登陆
     *  @author shopwind
     *  @param  string $FromUserName
	 *  @go member.index
     */
	function autoRegister()
	{
		if(empty($_GET['code']))
		{
			return false; 
		}
		
		$openid = $this->getOpenid($_GET['code']);
		if(!$openid)
		{
			$this->show_warning('no_such_openid');
			return;
		}
		$wx_info = $this->weixin->getUserInfo($openid);
		if(!$wx_info)
		{
			$this->show_warning('get_userinfo_fail');
			return;
		}
		$ms =& ms();
		$openid = isset($wx_info['unionid']) ? $wx_info['unionid'] : $openid;
		$member = $this->bind_mod->get(array('conditions'=>"openid='".$openid."' AND app='weixin'"));
		if($member && $this->member_mod->get($member['user_id']))
		{
			$user_id = $member['user_id'];
		}
		else
		{
			if(strlen($wx_info['nickname']) <=30 && $ms->user->check_username($wx_info['nickname'])){
				$user_name = $wx_info['nickname'];
			}
			else{
				$user_name = $wx_info['nickname'].'_'.mt_rand(1,9);
			}
			$other_data = array('portrait'=>$wx_info['headimgurl'],'real_name'=>$wx_info['nickname']);
			$user_id = $ms->user->register($user_name, md5('123456'), mt_rand().'@weixin.com',$other_data);
			$bind_data = array(
				'openid' => $openid,
				'user_id'=> $user_id,
				'app'   => 'weixin',
			);
			$this->bind_mod->add($bind_data);
		}
		/* 执行登陆操作 */
		$this->_do_login($user_id);
		/* 同步登陆外部系统 */
		$synlogin = $ms->user->synlogin($user_id);				
		$this->show_message('login_successed','back_list','index.php?app=member');
	}
	
	/**
     *  绑定已有的用户
     *  @author shopwind
     *  @param  string $FromUserName
	 *  @go member.index
     */
	function memberBind()
	{
		if(empty($_GET['code']))
		{
			return false; 
		}
		$openid = $this->getOpenid($_GET['code']);
		if(!$openid)
		{
			$this->show_warning('no_such_openid');
			return;
		}
		$wx_info = $this->weixin->getUserInfo($openid);
		if(!$wx_info)
		{
			$this->show_warning('get_user_fail');
			return;
		}
		$openid = isset($wx_info['unionid']) ? $wx_info['unionid'] : $openid;
		$member = $this->bind_mod->get(array('conditions'=>"openid='".$openid."' AND app='weixin'"));
		if($member)
		{
			$this->show_warning('bind_already');
			return;
		}
		if(!IS_POST)
		{
            $this->_config_seo('title', Lang::get('user_bind') . ' - ' . Conf::get('site_title'));
			$this->_get_curlocal_title('user_bind');
			$this->assign('ret_url','index.php?app=member');
            $this->display('member.wxbind.html');
		}
		else
		{
			$user_name = trim($_POST['user_name']);
            $password  = trim($_POST['password']);
			$ms =& ms();
			$user_id = $ms->user->auth($user_name, $password);
            if (!$user_id)
            {
				$error = current($ms->user->get_error());
				/* 未通过验证，提示错误信息 */
				$this->json_error($error['msg']);
				return;
            }
            else
            {

				$bind_data = array(
					'openid' => $openid,
					'user_id'=> $user_id,
					'app'   => 'weixin',
				);
				$this->bind_mod->add($bind_data);
                /* 执行登陆操作 */
                $this->_do_login($user_id);
                /* 同步登陆外部系统 */
                $synlogin = $ms->user->synlogin($user_id);				
				$this->json_result('', 'bind_successed');
            }
		}
	}
}
?>