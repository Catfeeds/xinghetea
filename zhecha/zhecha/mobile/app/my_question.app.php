<?php
/* 买家咨询管理控制器 */
class My_questionApp extends MemberbaseApp
{
    var $my_qa_mod;
    function __construct()
    {
        $this->My_questionApp();
    }
    function My_questionApp()
    {
        parent::__construct();
        $this->my_qa_mod = & m('goodsqa');
    }
    function index()
    {
        $page =$this->_get_page(8);
        $type = (isset($_GET['type']) && $_GET['type'] != '') ? trim($_GET['type']) : 'all_qa';
        $conditions = '1=1 AND goods_qa.user_id = '.$_SESSION['user_info']['user_id'] ;
        if ($type == 'reply_qa')
        {
            $conditions .= ' AND reply_content !="" ';
        }
        $my_qa_data = $this->my_qa_mod->find(array(
            'fields' => 'ques_id,question_content,reply_content,time_post,time_reply,goods_qa.user_id,goods_qa.item_name,goods_qa.item_id,goods_qa.email,goods_qa.type,if_new,user_name,store_logo,store_name',
            'join' => 'belongs_to_store,belongs_to_user',
            'count' => true,
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'order' => 'if_new desc,time_post desc',
        ));
        
        foreach($my_qa_data as $key=>$val)
		{
			empty($val['store_logo']) && $my_qa_data[$key]['store_logo'] = Conf::get('default_store_logo');
		}
		
        $page['item_count'] = $this->my_qa_mod->getCount();   //获取统计的数据
        $this->_format_page($page);
        $this->assign('page_info',$page);
        $this->assign('my_qa_data',$my_qa_data);
        if ($type == 'reply_qa')
        {
            $update_data = array(
                'if_new' => '0',
            );
            $this->my_qa_mod->edit($my_qa_data['ques_id'],$update_data);
        }
		$this->_get_curlocal_title('my_question');
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_question'));
        $this->display('my_question.index.html');
    }
}

?>