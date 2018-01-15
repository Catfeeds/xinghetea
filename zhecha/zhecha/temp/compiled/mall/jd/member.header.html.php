<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $this->_var['site_url']; ?>/" />
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $this->_var['charset']; ?>" />

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="author" content="浙江省茶叶集团兴润合茶业科技有限公司" />
<meta name="generator" content="浙江省茶叶集团兴润合茶业科技有限公司 <?php echo $this->_var['ecmall_version']; ?>" />
<meta name="copyright" content="浙江省茶叶集团兴润合茶业科技有限公司 All Rights Reserved" />
<?php echo $this->_var['page_seo']; ?>

<!--<link rel="icon" href="favicon.ico" type="image/x-icon" />-->
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/global.css'; ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/footer.css'; ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/user.css'; ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/hack.css'; ?>" rel="stylesheet" />
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
//]]>
</script>
<script type="text/javascript" src="index.php?act=jslang"></script>
<!--<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>-->
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery-1.11.1.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'cart.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/SuperSlide/jquery.SuperSlide.2.1.2.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.lazyload.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/global.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'member.js'; ?>" charset="utf-8"></script>

<?php echo $this->_var['_head_tags']; ?>

<style type="text/css">
#footer {background:#fff; color:#000;}
#footer .foot-service{background:#fff; padding-bottom:0;}
#footer a,#footer .foot-service dd a{color: #444;}
#footer a:hover,#footer .foot-service dd a:hover{color:#f33100;}
#footer .foot-baozhang{display:none}
</style>
</head>
<body>
<div id="site-nav" class="w-full">
   <div class="shoptop w clearfix">
      <div class="login_info">
         您好,
         <?php if (! $this->_var['visitor']['user_id']): ?>
         <?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?>
         <a href="<?php echo url('app=member&act=login&ret_url=' . $this->_var['ret_url']. ''); ?>">登录</a>
         <?php else: ?>
         <a href="<?php echo url('app=member'); ?>"><span><?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?></span></a>
         <a href="<?php echo url('app=member&act=logout'); ?>">退出</a>
         <a href="<?php echo url('app=message&act=newpm'); ?>">站内消息<?php if ($this->_var['new_message']): ?>(<span><?php echo $this->_var['new_message']; ?></span>)<?php endif; ?></a>
         <?php endif; ?>
      </div>
      <ul class="quick-menu J_GlobalPop">
        <?php if (! $this->_var['index']): ?><li class="home"><a href="<?php echo $this->_var['site_url']; ?>">回到首页</a></li><?php endif; ?>
        <li class="item">
           <div class="menu iwantbuy">
              <a class="menu-hd" href="<?php echo url('app=category'); ?>">我要买<b></b></a>
              <div class="menu-bd J_GlobalPopSub">
                 <div class="menu-bd-panel">
                    <div>
                       <p><a href="<?php echo url('app=category'); ?>">商品分类</a></p>
                       <p><a href="<?php echo url('app=search&order=sales desc'); ?>">大家都喜欢</a></p>
					   <p><a href="<?php echo url('app=search&order=add_time desc'); ?>">最新上架</a></p>
                    </div>
                 </div>
              </div>
           </div>
         </li>
         <li class="item">
            <div class="menu mytb">
               <a class="menu-hd" href="<?php echo url('app=buyer_admin'); ?>">我是买家<b></b></a>
               <div class="menu-bd J_GlobalPopSub">
                  <div class="menu-bd-panel">
                     <div>
                        <p><a href="<?php echo url('app=buyer_order'); ?>">已买到的宝贝</a></p>
                        <p><a href="<?php echo url('app=friend'); ?>">我的好友</a></p>
                        <p><a href="<?php echo url('app=my_question'); ?>">我的咨询</a></p>
                     </div>
                  </div>
               </div>
            </div>
         </li>
         <li class="item">
            <div class="menu seller-center">
               <a class="menu-hd" href="<?php echo url('app=seller_admin'); ?>">我是卖家<b></b></a>
               <div class="menu-bd J_GlobalPopSub">
                  <div class="menu-bd-panel">
                     <div>
                        <p><a href="<?php echo url('app=seller_order'); ?>">已卖出的宝贝</a></p>
                        <p><a href="<?php echo url('app=my_goods'); ?>">出售中的宝贝</a></p>
                     </div>
                  </div>
               </div>
            </div>
         </li>
         <li class="item">
            <div class="menu sites">
               <a class="menu-hd" href="javascript:;">用户中心<b></b></a>
               <div class="menu-bd J_GlobalPopSub">
                  <div class="cart-list  eject-box">
				<div class="login-status"> 你好，<?php if (! $this->_var['visitor']['user_id']): ?>请 <a href="<?php echo url('app=member'); ?>">登录</a><?php else: ?><a href="<?php echo url('app=member'); ?>"><?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?></a><a href="<?php echo url('app=member&act=logout'); ?>" class="ml5">[退出]</a><?php endif; ?> 
				</div>
				<div class="member-nav-list">
					<ul class="ls">
						<li><a href="<?php echo url('app=buyer_order'); ?>" target="_blank">我的订单</a></li>
						<li><a href="<?php echo url('app=my_question'); ?>" target="_blank">咨询回复</a></li>
					</ul>
					<ul class="ls">
						<li><a href="<?php echo url('app=friend'); ?>" target="_blank">我的好友</a></li>
						<li><a href="<?php echo url('app=my_favorite'); ?>" target="_blank">我的关注</a></li>
						<li><a href="<?php echo url('app=member&act=password'); ?>" target="_blank">修改密码</a></li>
						<li><a href="<?php echo url('app=find_password'); ?>" target="_blank">找回密码</a></li>
					</ul>
				</div>
				
			</div>
               </div>
            </div>
        </li>
         <li class="item">
            <div class="menu favorite">
               <a class="menu-hd" href="<?php echo url('app=my_favorite'); ?>">收藏夹<b></b></a>
               <div class="menu-bd J_GlobalPopSub">
                  <div class="menu-bd-panel">
                     <div>
                       <p><a href="<?php echo url('app=my_favorite'); ?>">收藏的宝贝</a></p>
                       <p><a href="<?php echo url('app=my_favorite&type=store'); ?>">收藏的店铺</a></p>
                    </div>
                 </div>
               </div>
           </div>
         </li>
         <li class="item" style="background:none">
            <div class="menu sites">
               <a class="menu-hd" href="javascript:;">网站导航<b></b></a>
               <div class="menu-bd padding10 J_GlobalPopSub">
                  <?php $_from = $this->_var['navs']['header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
                  <a href="<?php echo $this->_var['nav']['link']; ?>" <?php if ($this->_var['nav']['open_new']): ?> target="_blank" <?php endif; ?>><?php echo htmlspecialchars($this->_var['nav']['title']); ?></a>
                  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
               </div>
            </div>
        </li>
     </ul>
   </div>
</div>
<div id="header">
	<div class="w clearfix">
    	<div class="logo" title="<?php echo $this->_var['site_title']; ?>"><a href="index.php"><img src="<?php echo $this->_var['site_logo']; ?>" alt="<?php echo $this->_var['site_title']; ?>" /></a></div>
        <div class="search">
            <div class="tsearch tsearch-lite">
				<div class="tsearch-tabs">
					<ul>
						<li class="tsearch-tabs-active" ectype="index"><a href="javascript:;">商品</a><s class="rc-tp-l"></s><s class="rc-tp-r"></s></li>
						<li ectype="store"><a href="javascript:;">店铺</a><s class="rc-tp-l"></s><s class="rc-tp-r"></s></li>
					</ul>
				</div>
				<div class="tsearch-panel clearfix">
					<form  name="search" target="_top">
                    	<input type="hidden" name="app" value="search" />
                        <input type="hidden" name="act" value="index" />
						<div class="tsearch-panel-fields">
							<label for="keyword">请输入关键词</label>
							<input name="keyword" id="keyword" class="keyword"/>
							<s class="rc-tp-l"></s>
							<s class="rc-bt-l"></s>
							<s class="tsearch-panel-fields-shadow"></s>
						</div>
						<button type="submit" hidefocus="true">搜索</button>
					</form>
					<s class="rc-tp-l"></s>
					<s class="rc-tp-r"></s>
					<s class="rc-bt-l"></s>
					<s class="rc-bt-r"></s>
				</div>
			</div>
        </div>
    </div>
    <div class="w shopnav">
    	<ul class="clearfix">
        	<li class="first <?php if ($this->_var['member_role'] == 'buyer_admin' || ! $this->_var['member_role']): ?>current<?php endif; ?>"><a href="<?php echo url('app=buyer_admin'); ?>"><strong>我是买家</strong></a></li>
            <li class="last <?php if ($this->_var['member_role'] == 'seller_admin'): ?>current<?php endif; ?>"><a href="<?php echo url('app=seller_admin'); ?>"><strong>我是卖家</strong></a></li>
        </ul>
    </div>
</div>
