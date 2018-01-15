<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mobile/goodsinfo.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
/* buy */
function buy()
{
	//增加store_id变量，by PwordC
	var store_id = <?php echo $this->_var['store_id']; ?>;
    if (goodsspec.getSpec() == null)
    {
        layer.open({content:lang.select_specs, time:2});
        return;
    }
    var spec_id = goodsspec.getSpec().id;

    var quantity = $("#quantity").val();
    if (quantity == '')
    {
        layer.open({content:lang.input_quantity, time: 2});
        return;
    }
    if (parseInt(quantity) < 1 || isNaN(quantity))
    {
        layer.open({content:lang.invalid_quantity, time: 2});
        return;
    }

    add_to_cart(spec_id, quantity,store_id);//增加店铺id参数 by PwordC
}

/* add cart */
function add_to_cart(spec_id, quantity,store_id)//增加店铺id参数 by PwordC
{
    var url = REAL_SITE_URL + '/index.php?app=cart&act=add';
    $.getJSON(url, {'spec_id':spec_id, 'quantity':quantity,'store_id':store_id}, function(data){
    	if (data.done)
    	{
			<?php if ($this->_var['goods']['spec_name_1'] || $this->_var['goods']['spec_name_2']): ?>
			closePop();
			<?php endif; ?>
			layer.open({content:lang.success_add_to_cart, className:'layer-popup',time: 2});
        	return;
   	 	}
    	else
    	{
       		layer.open({content:data.msg, time: 2});
    	}
    })
}
/*buy_now*/
function buy_now()
{
	//增加store_id变量，by PwordC
	var store_id = <?php echo $this->_var['store_id']; ?>;
    //验证数据
	if (goodsspec.getSpec() == null)
    {
        layer.open({content:lang.select_specs, time: 2});
        return;
    }
    var spec_id = goodsspec.getSpec().id;
 
    var quantity = $("#quantity").val();
    if (quantity == '')
    {
        layer.open({content:lang.input_quantity, time: 2});
        return;
    }
    if (parseInt(quantity) < 1 || isNaN(quantity))
    {
        layer.open({content:lang.invalid_quantity, time: 2});
        return;
    }
    buy_now_add_cart(spec_id, quantity,store_id);//增加店铺id参数 by PwordC
}

/* add buy_now_add_cart */
function buy_now_add_cart(spec_id, quantity,store_id)//增加店铺id参数 by PwordC
{
    var url = REAL_SITE_URL + '/index.php?app=cart&act=add&selected=1';
    $.getJSON(url, {'spec_id':spec_id, 'quantity':quantity,'store_id':store_id}, function(data){
		if (data.done)
        {
			location.href= REAL_SITE_URL + '/index.php?app=order&goods=cart';
        }else{
            layer.open({content:data.msg, time: 2});
        }
    });
}
var specs = new Array();
<?php $_from = $this->_var['goods']['_specs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
specs.push(new spec(<?php echo $this->_var['spec']['spec_id']; ?>, '<?php echo htmlspecialchars($this->_var['spec']['spec_1']); ?>', '<?php echo htmlspecialchars($this->_var['spec']['spec_2']); ?>', <?php echo $this->_var['spec']['price']; ?>,<?php if ($this->_var['spec']['price_1']): ?><?php echo $this->_var['spec']['price_1']; ?><?php else: ?>0<?php endif; ?>,<?php if ($this->_var['spec']['price_2']): ?><?php echo $this->_var['spec']['price_2']; ?><?php else: ?>0<?php endif; ?>,<?php if ($this->_var['spec']['price_3']): ?><?php echo $this->_var['spec']['price_3']; ?><?php else: ?>0<?php endif; ?>,<?php if ($this->_var['spec']['price_4']): ?><?php echo $this->_var['spec']['price_4']; ?><?php else: ?>0<?php endif; ?>,<?php if ($this->_var['spec']['price_5']): ?><?php echo $this->_var['spec']['price_5']; ?><?php else: ?>0<?php endif; ?>, <?php echo $this->_var['spec']['stock']; ?>, <?php echo $this->_var['goods']['goods_id']; ?>));
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

var specQty = <?php echo $this->_var['goods']['spec_qty']; ?>;
var defSpec = <?php echo htmlspecialchars($this->_var['goods']['default_spec']); ?>;
var goodsspec = new goodsspec(specs, specQty, defSpec);

$(function(){
	/* 抢购倒计时 */
	$.each($('.countdown'),function(){
		var theDaysBox  = $(this).find('.NumDays');
		var theHoursBox = $(this).find('.NumHours');
		var theMinsBox  = $(this).find('.NumMins');
		var theSecsBox  = $(this).find('.NumSeconds');
			
		countdown(theDaysBox, theHoursBox, theMinsBox, theSecsBox)
	});
	
	if($('#slides').children('div').length>1){
      	$('#slides').slidesjs({
        	width: 300,
        	height: 300,
			navigation: false,
			play: {
          		auto: false
        	}
      	});
	 }
	 $('.handle .selected').click(function(){
		 $(this).parent().find('.J_hidden').toggle();
		var cl = $(this).find('span').attr('class');
		if(cl == 'icon-arr')
		{
			$(this).find('span').attr('class','icon-arr-on');
		}
		else
		{
			$(this).find('span').attr('class','icon-arr');
		}
	})
	$('.change-quality em').click(function(){
		var type = $(this).attr('class');
		var _v = Number($('#quantity').val());
		var stock = Number($('*[ectype="goods_stock"]').text());
		if(type == 'plus')
		{
			if(_v > 1)
			{
				$('#quantity').val(_v-1);
			}
		}
		else if(_v < stock) {
			$('#quantity').val(_v+1);
		}else{
			layer.open({content:"没有足够的商品", time: 5});
		}
	});
		
	$('.change-quality #quantity').keyup(function(){
		var _v = Number($('#quantity').val());
		var stock = Number($('*[ectype="goods_stock"]').text());
		if(_v > stock){ 
			layer.open({content:"没有足够的商品", time: 5});
			$(this).val(stock);
		}
		if(_v < 1 || isNaN(_v)) {
			layer.open({content:lang.invalid_quantity, time: 5});
			$(this).val(1);
		}
	});
	$('.J_PromotoolMoreLink').click(function(){
		$(this).parent().parent().find('.toggle').toggle();
		$(this).toggleClass('active');
	});
});
function closePop()
{
	$('.masker').remove();
	$('.pop-select-spec').slideUp();
}
function popLayer(arg)
{
	$('body').append("<div style='background:rgba(0,0,0,0.6);position:fixed;' onclick='closePop();' class='masker masker-color-1'></div>");
	$('.masker').show();
	$('.pop-select-spec').slideDown();
	$('.confirm-btn .'+arg).css('display','block').siblings().hide();
}
//]]>
</script>

<div class="goods-detail">
	<div class="col-img relative">
		<div class="scroll-wrapper">
			<div id="slides" class="scroller" >
            	<?php if ($this->_var['goods']['_images']): ?>
				<?php $_from = $this->_var['goods']['_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_image');$this->_foreach['fe_goods_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods_image']['total'] > 0):
    foreach ($_from AS $this->_var['goods_image']):
        $this->_foreach['fe_goods_image']['iteration']++;
?>
				<div><img src="<?php echo $this->_var['site_url']; ?>/<?php echo ($this->_var['goods_image']['thumbnail'] == '') ? $this->_var['default_image'] : $this->_var['goods_image']['thumbnail']; ?>" /></div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php else: ?>
                <div><img src="<?php echo $this->_var['site_url']; ?>/<?php echo ($this->_var['goods']['default_image'] == '') ? $this->_var['default_image'] : $this->_var['goods']['default_image']; ?>" /></div>
                <?php endif; ?> 
			</div>
			<ul class="new-banner-num new-tbl-type" id="idNum">
			</ul>
		</div>
	</div>
    <div class="col-title">
		<div class="title"> <span><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span> <font class="gray"><?php $_from = $this->_var['goods']['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tag');if (count($_from)):
    foreach ($_from AS $this->_var['tag']):
?><?php echo $this->_var['tag']; ?>&nbsp;&nbsp;&nbsp;<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></font> </div>
	</div>
	<div class="col-price clearfix">
    	<div class="J_IsPro col-title pb5 pt5" <?php if (! $this->_var['goods']['_specs']['0']['pro_price']): ?>style="display:none"<?php endif; ?>>
            <p class="clearfix pro-pri"> <span class="promo-price mr10 float-left ml10 yahei" ectype="goods_pro_price"><i><?php echo $this->_var['price_format']; ?></i><?php echo $this->_var['goods']['_specs']['0']['pro_price']; ?></span><em><?php echo $this->_var['goods']['pro_name']; ?></em></p>
            <p class="ml10 orig_price">原价：<del ectype="goods_price" class="f66 yahei"><?php echo price_format($this->_var['goods']['price']); ?></del></p>
            <?php if ($this->_var['goods']['lefttime']): ?>
            <p class="J_CountDown countdown hidden ml10"> <span><ins class="lefttime">距结束仅剩：</ins></span> <span class="tm NumDays"><?php echo $this->_var['goods']['lefttime']['d']; ?></span><em> 天 </em> <span class="tm NumHours"><?php echo $this->_var['goods']['lefttime']['h']; ?></span><em> 小时 </em><span class="tm NumMins"><?php echo $this->_var['goods']['lefttime']['m']; ?></span><em> 分 </em><span class="tm NumSeconds"><?php echo $this->_var['goods']['lefttime']['s']; ?></span><em> 秒 </em></p>
            <?php endif; ?> 
        </div>
		<p class="J_IsNotPro pro-pri is-no-pro"  style="<?php if ($this->_var['goods']['_specs']['0']['pro_price']): ?>display:none;<?php endif; ?>padding:8px 10px 4px;"> <span class="t"> 零售价： </span><span class="yahei fw-normal" ectype="goods_price"><?php echo price_format($this->_var['goods']['price']); ?></span> </p>
        <p class="J_IsNotPro pro-pri is-no-pro" style="padding:8px 10px 4px;"><span class="t"> 会员价： </span> <span class="yahei fw-normal" ectype="goods_price_i"><?php echo price_format($this->_var['goods']['integral_price']); ?>+<?php echo $this->_var['goods']['max_exchange']; ?>积分</span> </p>
        
        <p class="padding10 extra clearfix col-title"><span><?php if ($this->_var['goods']['default_logist']): ?><?php echo $this->_var['goods']['default_logist']['name']; ?>：<?php echo price_format($this->_var['goods']['default_logist']['start_fees']); ?><?php endif; ?></span><span>销量：<?php echo $this->_var['goods']['sales']; ?>件</span><span style="text-align:right;"><?php echo htmlspecialchars($this->_var['store']['region_name']); ?></span></p>
	</div>
	<?php if ($this->_var['integral_enabled'] && $this->_var['goods']['exchange_price']): ?>
    <div class="line-background"></div>
	<div class="col-title">
		<div class="title padding10"> <span class="t">积分抵扣： </span><span class="discount-info"><b class="d-name">可使用<?php echo $this->_var['goods']['max_exchange']; ?> 积分 </b> <b class="d-price">抵 <?php echo price_format($this->_var['goods']['exchange_price']); ?> 元</b><b class="d-price">积分不足无法购买</b></span></div>
	</div>
	<?php endif; ?>
	
	<?php if ($this->_var['promotool']['storeFullfreeInfo']): ?>
  	<div class="line-background"></div>
  	<div class="col-title promotool">
    	<div class="title padding10"> <span class="t">包邮条件：</span> <span><?php echo $this->_var['promotool']['storeFullfreeInfo']; ?></span> </div>
  	</div>
  	<?php endif; ?> 
    
    <?php if ($this->_var['promotool']['storeFullPreferInfo']): ?>
    <div class="line-background"></div>
   	<div class="col-title promotool">
    	<div class="title padding10"> <span class="t">满折满减：</span> <span><?php echo $this->_var['promotool']['storeFullPreferInfo']; ?></span> </div>
    </div>
    <?php endif; ?>	
  
  	<?php if ($this->_var['promotool']['storeFullGiftList']): ?>
  	<div class="line-background"></div>
  	<div class="col-title promotool"> 
    	<div class="title">
        	<span class="t">赠<i style="margin:0 13px;"></i>品：</span> 
            <span> 
    			<?php $_from = $this->_var['promotool']['storeFullGiftList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'fullgift');$this->_foreach['fe_fullgift'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_fullgift']['total'] > 0):
    foreach ($_from AS $this->_var['fullgift']):
        $this->_foreach['fe_fullgift']['iteration']++;
?> 
    			<ins class="each mr10 <?php if (! ($this->_foreach['fe_fullgift']['iteration'] <= 1)): ?> toggle hidden <?php endif; ?> fs13"> 购物满 <b class="f60"><?php echo $this->_var['fullgift']['amount']; ?></b> 元获赠： 
    			<?php $_from = $this->_var['fullgift']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?> 
    			[<a href="<?php echo url('app=gift&id=' . $this->_var['item']['goods_id']. ''); ?>" class="inline-block"><?php echo $this->_var['item']['goods_name']; ?></a>] 
    			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    			</ins> 
    			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    			<a href="javascript:;" class="J_PromotoolMoreLink morelink inline-block"></a> 
            </span> 
 		</div>
    </div>
  	<?php endif; ?> 
  
 	<?php if ($this->_var['promotool']['goodsGrowbuyList']): ?>
  	<div class="line-background"></div>
  	<div class="col-title promotool">
    	<div class="title">
    	<span class="t">加<i style="margin:0 7px;">价</i>够：</span> 
        <span> 
    		<?php $_from = $this->_var['promotool']['goodsGrowbuyList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'growbuy');$this->_foreach['fe_growbuy'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_growbuy']['total'] > 0):
    foreach ($_from AS $this->_var['growbuy']):
        $this->_foreach['fe_growbuy']['iteration']++;
?> 
    		<ins class="each mr10 <?php if (! ($this->_foreach['fe_growbuy']['iteration'] <= 1)): ?> toggle hidden <?php endif; ?> fs13"> 加 <b class="f60"><?php echo $this->_var['growbuy']['money']; ?></b> 元可购买 
    			<?php $_from = $this->_var['growbuy']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?> 
    			[<a href="<?php echo url('app=goods&id=' . $this->_var['item']['goods_id']. ''); ?>" class="inline-block"><?php echo $this->_var['item']['goods_name']; ?></a>] 
    			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    		</ins> 
    		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    		<a href="javascript:;" class="J_PromotoolMoreLink morelink inline-block" ></a> 
    	</span> 
        </div>
    </div>
  	<?php endif; ?>
  
    <?php if ($this->_var['goods']['spec_name_1'] || $this->_var['goods']['spec_name_2']): ?>
	<div class="line-background"></div>
    <div class="col-title">
		<p class="padding10 select-specs clearfix" onclick="popLayer('buy');">请选择： <?php echo htmlspecialchars($this->_var['goods']['spec_name_1']); ?><?php echo htmlspecialchars($this->_var['goods']['spec_name_2']); ?></p>
	</div>
    <?php endif; ?>
	<div class="line-background"></div>
  	<?php echo $this->fetch('goods.meal.html'); ?>
	<div class="store-info">
		<div class="info margin10">
			<div class="store-logo"><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><img width="60" height="60" src="<?php echo $this->_var['store']['store_logo']; ?>" /></a></div>
			<div class="store-m-info">
				<p class="store-name"><a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><?php echo $this->_var['store']['store_name']; ?></a></p>
				<p class="extra"><span>好评率</span> <b><?php echo $this->_var['store']['praise_rate']; ?>%</b> <span>信誉</span><span> <?php if ($this->_var['store']['credit_value'] >= 0): ?><img src="<?php echo $this->_var['store']['credit_image']; ?>" alt="" /><?php endif; ?></span></p>
			</div>
		</div>
		<div class="btns clearfix pb10 pl10 pr10">
			<p> <a href="<?php echo url('app=store&act=search&id=' . $this->_var['store']['store_id']. ''); ?>"><span><ins>&#xe700;</ins>全部商品</span></a> </p>
			<p> <a style="margin-left:5px;margin-right:0px;" href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>"><span><ins>&#xe676;</ins>进入店铺</span></a> </p>
		</div>
	</div>
	<div class="line-background"></div>
</div>
<div class="pop-select-spec">
	<div class="info">
    	<img src="<?php echo $this->_var['goods']['default_image']; ?>" height="100" width="100" />
        <div class="goods-attr">
        	<p class="clearfix J_IsPro" <?php if (! $this->_var['goods']['_specs']['0']['pro_price']): ?>style="display:none"<?php endif; ?>> <!--<em class="promo-price-type float-left ml10" title="<?php echo $this->_var['goods']['pro_desc']; ?>"><?php echo $this->_var['goods']['pro_name']; ?></em>--> <span class="promo-price mr10 float-left yahei pri" ectype="goods_pro_price"><?php echo price_format($this->_var['goods']['_specs']['0']['pro_price']); ?></span> <del ectype="goods_price" class="float-left fff yahei"><?php echo price_format($this->_var['goods']['price']); ?></del> </p>
			<p class="J_IsNotPro" <?php if ($this->_var['goods']['_specs']['0']['pro_price']): ?> style="display:none"<?php endif; ?>> <span class="yahei fw-normal pri" ectype="goods_price"><?php echo price_format($this->_var['goods']['price']); ?></span> </p>
            <p><i class="gray">库存 <span class="stock gray" ectype="goods_stock"><?php echo $this->_var['goods']['_specs']['0']['stock']; ?></span>件</i></p>
            <p>您已选择:<span class="aggregate" ectype="current_spec"></span></p>
        </div>
        <div class="close-pop" onclick="closePop();">&#xe659;</div>
    </div>
	<div class="handle"> 
		<?php if ($this->_var['goods']['spec_qty'] > 0): ?>
		<ul class="clearfix w-full J_hidden mb5">
			<li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_1']); ?> </li>
		</ul>
		<?php endif; ?> 
		<?php if ($this->_var['goods']['spec_qty'] > 1): ?>
		<ul class="clearfix w-full J_hidden mb5">
			<li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_2']); ?></li>
		</ul>
		<?php endif; ?>
		<ul class="clearfix w-full mb5">
			<li class="handle_title mr5">购买数量 </li>
			<li class="change-quality"> <em class="plus"><b></b></em>
				<input type="text" class="text width1" name="quantity" id="quantity" value="1" />
				<em class="add"><b></b></em> </li>
			
		</ul>
	</div>
    <div class="confirm-btn">
    	<a href="javascript:;" onclick="buy_now();" class="buy-now">确定</a>
        <a href="javascript:;" onclick="buy();" class="buy">确定</a>
    </div>
</div>
<div class="btn-fixed clearfix">
	<div class="small-ico clearfix">
        <div class="ico-it">
            <a  <?php if ($this->_var['store']['im_qq']): ?>href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($this->_var['store']['im_qq']); ?>&site=qq&menu=yes"<?php endif; ?> class="btn-to-cart yahei">
            	<em <?php if ($this->_var['store']['im_qq']): ?>style="color:#4A90E2;"<?php endif; ?>>&#xe6ff;</em><br />客服
            </a>
        </div>
        <div class="ico-it">
            <a  href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>" class="btn-to-cart yahei">
            	<em>&#xe676;</em><br />进店
            </a>
        </div>
        <div class="ico-it">
            <a  href="javascript:;" class="J_AjaxRequest btn-to-cart yahei <?php if ($this->_var['goods']['collects']): ?>collected<?php endif; ?>" action="<?php echo url('app=my_favorite&act=add&type=goods&item_id=' . $this->_var['goods']['goods_id']. '&ajax=1'); ?>">
            	<em>&#xe669;</em><br />收藏
            </a>
        </div>
    </div>
    <div class="large-btn clearfix">
        <div class="btn-it it1"><a  href="javascript:;" <?php if ($this->_var['goods']['spec_name_1'] || $this->_var['goods']['spec_name_2']): ?>onclick="popLayer('buy-now');"<?php else: ?>onclick="buy_now();"<?php endif; ?> class="btn-buy yahei">立刻购买</a></div>
        <div class="btn-it it2"><a  href="javascript:;" <?php if ($this->_var['goods']['spec_name_1'] || $this->_var['goods']['spec_name_2']): ?>onclick="popLayer('buy');"<?php else: ?>onclick="buy();"<?php endif; ?> class="btn-cart yahei">加入购物车</a></div>
    </div>
</div>

<a name="module"></a>
