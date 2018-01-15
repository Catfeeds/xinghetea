<?php echo $this->fetch('top.html'); ?> 
<script type="text/javascript">
$(function(){
	$("img.lazyload").lazyLoad();
	
	/* 首页轮播 */
	if($('#slide').children('li').length>1){
		$('#slide').slidesjs({
			width: 320,
			height: 120,
			navigation: false,
			play: {
				auto: true
			}
		});
	}
	$('.fake-search-box').find('input').click(function(){
		window.location.href = REAL_SITE_URL+'/index.php?app=search&act=form';
	})
})
</script>
<div id="page-index" class="page J_page derect-left">
	<div id="main"> 
		
		<div class="bar-wrap">
			<div style="line-height:30px;" class="top-bar"> 
            	<a href="javascript:;" onclick="PsmobanShowMenu('left');" class="category"><span></span></a>
				<h2 class="yahei"><?php echo $this->_var['site_title']; ?></h2>
				<a href="javascript:;" class="pagemore J_pagemore"><span></span></a>
            </div>
            <div class="eject-tab J_eject_tab w-full clearfix hidden">
			<a href="<?php echo $this->_var['mobile_site_url']; ?>"> <span></span><p>首页</p></a>
			<a href="<?php echo url('app=search'); ?>"> <span class="icon2"></span><p>分类搜索</p></a>
			<a href="<?php echo url('app=cart'); ?>"> <span class="icon3"></span><p>购物车</p></a>
			<a href="<?php echo url('app=member'); ?>"> <span class="icon4"></span><p>用户中心</p></a>
		</div>
		</div>
		
		
        <div class="slides">
        	<ul id="slide" class="scroller" > 
                <?php $_from = $this->_var['slides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'slide');$this->_foreach['fe_slide'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_slide']['total'] > 0):
    foreach ($_from AS $this->_var['slide']):
        $this->_foreach['fe_slide']['iteration']++;
?>
                <?php if ($this->_var['slide']['ad_image_url'] && $this->_var['slide']['ad_link_url']): ?>
                <li <?php if (! ($this->_foreach['fe_slide']['iteration'] <= 1)): ?>style="display:none"<?php endif; ?>><a href='<?php echo $this->_var['slide']['ad_link_url']; ?>'><img src="<?php echo $this->_var['slide']['ad_image_url']; ?>" /></a></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
            </ul>
        </div>
        
        
        <div class="fake-search-box margin10 mt20">
           <div class="input-wraper">
              <p class="input-child-wraper"><input class="text-input" placeholder="请输入你要搜索的商品名称" readonly="readonly" /></p>
              <input type="button" class="fake-submit" value="" />
          </div>
        </div>
        
		
        <div class="memnus-list pl10 mb10">
           <ul class="clearfix">
            	<li>
                    <a href="<?php echo url('app=category'); ?>">
                       <p><img src="mobile/static/images/home-icon-category.png" /></p>
                       <p>商品分类</p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('app=member'); ?>">
                        <p><img src="mobile/static/images/home-icon-member-center.png" /></p>
                        <p>用户中心</p>
                    </a>
                </li>
                <li>
                   <a href="<?php echo url('app=buyer_order'); ?>">
                       <p><img src="mobile/static/images/home-icon-order.png" /></p>
                       <p>我的订单</p>
                   </a>
                </li>
                <li>
                    <a href="<?php echo url('app=my_coupon'); ?>">
                       <p><img src="mobile/static/images/home-icon-member-collect.png" /></p>
                       <p>优惠券</p>
                   </a>
                </li>
                <li>
                   <a href="<?php echo url('app=category&act=store'); ?>">
                        <p><img src="mobile/static/images/home-icon-category-store.png" /></p>
                        <p>店铺分类</p>
                   </a>
                </li>
                <li>
                   <a href="<?php echo url('app=deposit'); ?>">
                      <p><img src="mobile/static/images/home-icon-doposit.png" /></p>
                      <p>我的钱包</p>
                   </a>
                </li>
                <li>
                   <a href="<?php echo url('app=cart'); ?>">
                       <p><img src="mobile/static/images/home-icon-cart.png" /></p>
                       <p>购物车</p>
                   </a>
                </li>
                <li>
                    <a href="<?php echo url('app=search&act=store'); ?>">
                        <p><img src="mobile/static/images/home-icon-help.png" /></p>
                        <p>店铺</p>
                    </a>
                </li>
            </ul>
        </div>
        
		<div class="floor floor1 J_Floor">
			<div class="content clearfix">
				<div class="col-1 relative">
					<a href="<?php echo $this->_var['floor_1']['ads']['1']['ad_link_url']; ?>"><img src="<?php echo $this->_var['floor_1']['ads']['1']['ad_image_url']; ?>" /></a>
					
				</div>
				<div class="col-2">
					<div class="border-left"> <a href="<?php echo $this->_var['floor_1']['ads']['2']['ad_link_url']; ?>" class="border-bottom"><img src="<?php echo $this->_var['floor_1']['ads']['2']['ad_image_url']; ?>"  /></a> <a href="<?php echo $this->_var['floor_1']['ads']['3']['ad_link_url']; ?>"><img src="<?php echo $this->_var['floor_1']['ads']['3']['ad_image_url']; ?>" /></a> </div>
				</div>
			</div>
		</div>
		<div class="labels clearfix"> 
			<?php $_from = $this->_var['floor_1']['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'kw1');if (count($_from)):
    foreach ($_from AS $this->_var['kw1']):
?> 
			<span><a href="<?php echo $this->_var['kw1']['link']; ?>" class="yahei" res="1"><?php echo htmlspecialchars($this->_var['kw1']['title']); ?></a></span> 
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		</div>
		<div class="floor floor2  J_Floor">
			<div class="title yahei"><?php echo htmlspecialchars($this->_var['floor_2']['model_name']); ?></div>
			<div class="content clearfix">
				<div class="col-1"><a href="<?php echo $this->_var['floor_2']['ads']['1']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_2']['ads']['1']['ad_image_url']; ?>" /></a></div>
				<div class="col-2">
					<div class="border-left"> <a href="<?php echo $this->_var['floor_2']['ads']['2']['ad_link_url']; ?>" class="border-bottom"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_2']['ads']['2']['ad_image_url']; ?>"  /></a> <a href="<?php echo $this->_var['floor_2']['ads']['3']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_2']['ads']['3']['ad_image_url']; ?>" /></a> </div>
				</div>
			</div>
			<div class="labels clearfix"> 
				<?php $_from = $this->_var['floor_2']['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'kw2');if (count($_from)):
    foreach ($_from AS $this->_var['kw2']):
?> 
				<span><a href="<?php echo $this->_var['kw2']['link']; ?>" class="yahei" res="1"><?php echo htmlspecialchars($this->_var['kw2']['title']); ?></a></span> 
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
			</div>
		</div>
		<div class="floor floor3  J_Floor">
			<div class="title yahei"><?php echo htmlspecialchars($this->_var['floor_3']['model_name']); ?></div>
			<div class="content clearfix">
				<div class="col-1">
					<div class="border-right"> <a href="<?php echo $this->_var['floor_3']['ads']['1']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_3']['ads']['1']['ad_image_url']; ?>" /></a><a href="<?php echo $this->_var['floor_3']['ads']['2']['ad_link_url']; ?>" class="border-top"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_3']['ads']['2']['ad_image_url']; ?>" /></a> </div>
				</div>
				<div class="col-2"> <a href="<?php echo $this->_var['floor_3']['ads']['3']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_3']['ads']['3']['ad_image_url']; ?>" /></a> </div>
			</div>
		</div>
		<div class="labels clearfix"> 
			<?php $_from = $this->_var['floor_3']['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'kw3');if (count($_from)):
    foreach ($_from AS $this->_var['kw3']):
?> 
			<span><a href="<?php echo $this->_var['kw3']['link']; ?>" class="yahei" res="1"><?php echo htmlspecialchars($this->_var['kw3']['title']); ?></a></span> 
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		</div>
		<div class="floor floor4  J_Floor">
			<div class="title yahei"><?php echo htmlspecialchars($this->_var['floor_4']['model_name']); ?></div>
			<div class="content clearfix">
				<div class="col-1"><a href="<?php echo $this->_var['floor_4']['ads']['1']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_4']['ads']['1']['ad_image_url']; ?>" /></a></div>
				<div class="col-2">
					<div class="border-left"> <a href="<?php echo $this->_var['floor_4']['ads']['2']['ad_link_url']; ?>" class="border-bottom"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_4']['ads']['2']['ad_image_url']; ?>" /></a><a href="<?php echo $this->_var['floor_4']['ads']['3']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_4']['ads']['3']['ad_image_url']; ?>" /></a></div>
				</div>
			</div>
		</div>
		<div class="labels clearfix"> 
			<?php $_from = $this->_var['floor_4']['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'kw4');if (count($_from)):
    foreach ($_from AS $this->_var['kw4']):
?> 
			<span><a href="<?php echo $this->_var['kw4']['link']; ?>" class="yahei" res="1"><?php echo htmlspecialchars($this->_var['kw4']['title']); ?></a></span> 
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		</div>
		<div class="floor floor5  J_Floor">
			<div class="title yahei"><?php echo htmlspecialchars($this->_var['floor_5']['model_name']); ?></div>
			<div class="content clearfix">
				<div class="col-1"><a href="<?php echo $this->_var['floor_5']['ads']['1']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_5']['ads']['1']['ad_image_url']; ?>" /></a></div>
				<div class="col-2">
					<div class="border-left"> <a href="<?php echo $this->_var['floor_5']['ads']['2']['ad_link_url']; ?>" class="border-bottom"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_5']['ads']['2']['ad_image_url']; ?>" /></a><a href="<?php echo $this->_var['floor_5']['ads']['3']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['floor_5']['ads']['3']['ad_image_url']; ?>" /></a></div>
				</div>
			</div>
		</div>
		<div class="labels clearfix"> 
			<?php $_from = $this->_var['floor_5']['keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'kw5');if (count($_from)):
    foreach ($_from AS $this->_var['kw5']):
?> 
			<span><a href="<?php echo $this->_var['kw5']['link']; ?>" class="yahei" res="1"><?php echo htmlspecialchars($this->_var['kw5']['title']); ?></a></span> 
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
		</div>
		<ul class="theme clearfix">
			<?php $_from = $this->_var['six_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'six_image');$this->_foreach['fe_six_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_six_image']['total'] > 0):
    foreach ($_from AS $this->_var['six_image']):
        $this->_foreach['fe_six_image']['iteration']++;
?>
			<li><a  href="<?php echo $this->_var['six_image']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['six_image']['ad_image_url']; ?>" /></a></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="global-image-ads"><a href="<?php echo $this->_var['banner']['ad_link_url']; ?>"><img src="<?php echo $this->res_base . "/" . 'images/empty.gif'; ?>"  class="lazyload" initial-url="<?php echo $this->_var['banner']['ad_image_url']; ?>" /></a></div>
        <div class="bottom-search">
        	<div class="search-box">
            	<form method="get">
                	<input type="hidden" name="app" value="search" />
                    <div class="wraper"><input name="keyword" type="text" value="" placeholder="" /></div>
                    <input type="submit" value="" />
                </form>
            </div>
        	<a href="index.php" class="home-icon"></a>
            <a href="javascript:scroll(0,0);" class="back-top"></a>
        </div>
	</div>
</div>

<div class="J_masker masker" onclick="PsmobanShowMenu('left');"></div>
<div class="J_menus menus home-category">
	<ul>
    	<?php $_from = $this->_var['header_gcategories']['gcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gcate');if (count($_from)):
    foreach ($_from AS $this->_var['gcate']):
?>
    	<li>
        	<a href="<?php echo url('app=search&cate_id=' . $this->_var['gcate']['id']. ''); ?>" class="arrow"></a>
            <a href="<?php echo url('app=search&cate_id=' . $this->_var['gcate']['id']. ''); ?>" class="title"><?php echo $this->_var['gcate']['value']; ?></a>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>

<?php echo $this->fetch('footer.html'); ?>