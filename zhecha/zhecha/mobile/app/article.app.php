<?php

class ArticleApp extends MallbaseApp
{

    var $_article_mod;
    var $_acategory_mod;
    var $_ACC; //系统文章cate_id数据
    var $_cate_ids; //当前分类及子孙分类cate_id
    function __construct()
    {
        $this->ArticleApp();
    }
    function ArticleApp()
    {
        parent::__construct();
        $this->_article_mod = &m('article');
        $this->_acategory_mod = &m('acategory');
        /* 获得系统分类cate_id数据 */
        $this->_ACC = $this->_acategory_mod->get_ACC();
    }
    function index()
    {
		isset($_GET['keyword']) && $condition .= " AND title LIKE '%".html_script($_GET['keyword'])."%'";
        isset($_GET['code']) && isset($this->_ACC[trim($_GET['code'])]) && $condition .= " AND cate_id=".$this->_ACC[trim($_GET['code'])]; //如果有code
		
		$page = $this->_get_page(10);
        $articles = $this->_article_mod->find(array(
            'conditions'  => 'if_show=1 AND store_id=0 AND code = "" AND  cate_id !='. $this->_ACC[ACC_SYSTEM].$condition,
            'limit'   => $page['limit'],
            'order'   => 'add_time desc',
			'fields'  => 'title,add_time',
			'count'   => true
        )); 
		$page['item_count'] = $this->_article_mod->getCount();
		
		$this->_format_page($page);
        $this->assign('page_info', $page);
		$this->_get_curlocal_title('article');
		
		$this->assign('articles',$articles);
        $this->display('article.index.html');
    }
	
	function get_article()
	{
		$page = $this->_get_page(5);
        $articles = $this->_article_mod->find(array(
            'conditions'  => 'if_show=1 AND store_id=0 AND code = "" AND  cate_id !='. $this->_ACC[ACC_SYSTEM],
            'limit'   => $page['limit'],
            'order'   => 'add_time desc',
			'fields'  => 'title,add_time',
        )); 
        $this->json_result(array('article'=>$articles),'');
	}
	
    function view()
    {
        $article_id = empty($_GET['article_id']) ? 0 : intval($_GET['article_id']);
        $cate_ids = array();
        if ($article_id>0)
        {
            $article = $this->_article_mod->get('article_id=' . $article_id . ' AND code = "" AND if_show=1 AND store_id=0');
            if (!$article)
            {
                $this->show_warning('no_such_article');
                return;
            }
            if ($article['link']){ //外链文章跳转
                header("HTTP/1.1 301 Moved Permanently");
                header('location:'.$article['link']);
                return;
            }
        }
        else
        {
            $this->show_warning('no_such_article');
            return;
        }
        $this->assign('article', $article);
		$this->_get_curlocal_title('article_view');
        $this->_config_seo('title', $article['title'] . ' - ' . Conf::get('site_title'));
        $this->display('article.view.html');
    }

    function system()
    {
        $code = empty($_GET['code']) ? '' : trim($_GET['code']);
        if (!$code)
        {
            $this->show_warning('no_such_article');
            return;
        }
        $article = $this->_article_mod->get("code='" . $code . "'");
        if (!$article)
        {
            $this->show_warning('no_such_article');
            return;
        }
        if ($article['link']){ //外链文章跳转
                header("HTTP/1.1 301 Moved Permanently");
                header('location:'.$article['link']);
                return;
            }

        $this->assign('article', $article);
		$this->_get_curlocal_title($article['title']);
        $this->_config_seo('title', $article['title'] . ' - ' . Conf::get('site_title'));
        $this->display('article.view.html');

    }
}

?>
