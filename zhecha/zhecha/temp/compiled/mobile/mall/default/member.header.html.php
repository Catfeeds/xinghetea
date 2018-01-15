<!DOCTYPE html>
<html>
<head>
<base href="<?php echo $this->_var['site_url']; ?>/" />
<meta charset="<?php echo $this->_var['charset']; ?>" />
<?php echo $this->_var['page_seo']; ?>
<meta name="author" content="www.psmoban.com" />
<meta name="generator" content="ECMall <?php echo $this->_var['ecmall_version']; ?>" />
<meta name="copyright" content="ShopEx Inc. All Rights Reserved" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Expires" CONTENT="-1">
<meta http-equiv="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Pragma" CONTENT="no-cache">
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/header.css'; ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/user.css'; ?>" rel="stylesheet"  />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/footer.css'; ?>" rel="stylesheet" />
<script type="text/javascript" src="mobile/index.php?act=jslang"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/jquery.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/member.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/layer.m/layer.m.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/main.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
//]]>
</script>
<?php echo $this->_var['_head_tags']; ?>
</head>
<body>

<div class="bar-wrap <?php if ($_GET['app'] == 'member' && $_GET['act'] == ''): ?>w<?php endif; ?>">
	<div class="top-bar"> 
    	<a href="<?php if ($_GET['act'] == '' && $_GET['app'] != 'member'): ?><?php echo url('app=member'); ?><?php elseif ($_GET['app'] == 'member' && $_GET['act'] == ''): ?>mobile/index.php<?php else: ?>javascript:pageBack();<?php endif; ?>" class="pageback"><span></span></a>
		<h2 class="yahei"><?php echo $this->_var['curlocal_title']; ?></h2>
		<a href="javascript:;" class="pagemore J_pagemore"><span></span></a>
    </div>
	<div class="eject-tab J_eject_tab w-full clearfix hidden">
			<a href="<?php echo $this->_var['mobile_site_url']; ?>"> <span></span><p>首页</p></a>
			<a href="<?php echo url('app=search'); ?>"> <span class="icon2"></span><p>分类搜索</p></a>
			<a href="<?php echo url('app=cart'); ?>"> <span class="icon3"></span><p>购物车</p></a>
			<a href="<?php echo url('app=member'); ?>"> <span class="icon4"></span><p>用户中心</p></a>
		</div>
</div>
