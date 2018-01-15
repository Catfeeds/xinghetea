<!--<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.min.js'; ?>" charset="utf-8"></script>-->
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/fresco/fresco.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'zoom/mzp-packed.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/raty/jquery.raty.min.js'; ?>" charset="utf-8"></script>
<link href="<?php echo $this->lib_base . "/" . 'jquery.plugins/fresco/fresco.css'; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->lib_base . "/" . 'jquery.plugins/raty/css/application.css'; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">
//<!CDATA[
$(function(){
  $('#join').click(function(){
        var qty = 0;
        var error = false;
        var max_per_user = <?php echo $this->_var['group']['max_per_user']; ?>;
        $('input[ectype="quantity"]').each(function(){
            if(parseInt($(this).val()) > 0 ){
                qty += parseInt($(this).val());
            }
            if($(this).val() !='' && (parseInt($(this).val()) < 0 || isNaN(parseInt($(this).val()))))
            {
                error = true;
            }
        });
        if('<?php echo $this->_var['group']['ican']['login']; ?>'){
           alert('您需要登陆才能参加团购活动');
           window.location.href = SITE_URL + '/index.php?app=member&act=login&ret_url=' + encodeURIComponent('index.php?app=groupbuy&id=<?php echo $this->_var['group']['group_id']; ?>');
        }else if(error == true){
           alert('您输入的数量不正确');
        }else if(qty == 0){
           alert('请填写购买数量');
        }else if(max_per_user > 0 && qty > max_per_user){
           alert('<?php echo sprintf('该团购商品每人最多购买%s件', $this->_var['group']['max_per_user']); ?>');
        }else{
            $('.step-tow').show(400);
			$('.step-one').hide();
            $('input[name="link_man"]').focus();
            $('input[ectype="quantity"]').attr('disabled',true);
        }
    });
    $('.close-btn').click(function(){
        $(this).parent('.i-want-join').hide();
        $('input[ectype="quantity"]').attr('disabled',false);
    });
    $('#join_group_form').submit(function(){
        $('input[ectype="quantity"]').attr('disabled',false);
    });
    $('input[name="exit"]').click(function(){
        if(!confirm('您确定要退出该团购活动吗？')){
            return false;
        }
    });
	$.each($('.countdown'),function(){
	
		var theDaysBox  = $(this).find('.NumDays');
		var theHoursBox = $(this).find('.NumHours');
		var theMinsBox  = $(this).find('.NumMins');
		var theSecsBox  = $(this).find('.NumSeconds');
		
		countdown(theDaysBox, theHoursBox, theMinsBox, theSecsBox)
	});
	$.fn.raty.defaults.path = 'static/images/';
	$('#evaluation').raty({ readOnly: true, score:<?php echo ($this->_var['goods']['avg_g_eva'] == '') ? '0' : $this->_var['goods']['avg_g_eva']; ?>});
	//by psmoban start
	$('.tiny-pics .list li').mouseover(function(){
        $('.tiny-pics .list li').removeClass();
        $(this).addClass('pic_hover');
    });
	
	var moveNum=-62;
	var lengthLi = ($('.tiny-pics .list li').width()) * $('.tiny-pics .list li').length - 310;
	$('#forword').click(function(){
        var posleftNum = $('.tiny-pics .list').position().left;
        if($('.tiny-pics .list').position().left > -lengthLi){
            $('.tiny-pics .list').css({'left': posleftNum + moveNum});
        }
    });
	$('#backword').click(function(){
        var posleftNum = $('.tiny-pics .list').position().left;
        if($('.tiny-pics .list').position().left < 0){
            $('.tiny-pics .list').css({'left': posleftNum - moveNum});
        }
    });
})
</script>
<div class="w-shop clearfix group-info">
    <div class="zoom-pics col-sub">
        <div class="big_pic border  mb5">
            <a href="<?php echo $this->_var['goods']['_images']['0']['image_url']; ?>" id="zoom" class="MagicZoom MagicThumb">
                <img src="<?php echo ($this->_var['goods']['_images']['0']['thumbnail'] == '') ? $this->_var['default_image'] : $this->_var['goods']['_images']['0']['thumbnail']; ?>" width="350"
                height="350" id="main_img" class="main_img" />
            </a>
        </div>
        <div class="tiny-pics">
            <a href="javascript:;" id="forword" class="controler">
            </a>
            <a href="javascript:;" id="backword" class="controler">
            </a>
            <ul class="list clearfix">
                <?php $_from = $this->_var['goods']['_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_image');$this->_foreach['fe_goods_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods_image']['total'] > 0):
    foreach ($_from AS $this->_var['goods_image']):
        $this->_foreach['fe_goods_image']['iteration']++;
?>
                <li <?php if (($this->_foreach['fe_goods_image']['iteration'] <= 1)): ?>class="pic_hover" <?php endif; ?>>
                    <a href="<?php echo $this->_var['goods_image']['image_url']; ?>" rel="zoom" rev="<?php echo $this->_var['goods_image']['image_url']; ?>">
                        <img src="<?php echo $this->_var['goods_image']['thumbnail']; ?>" />
                    </a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="share w-full clearfix mb10">
            <div class="view-big-imgs">
                <b>
                </b>
                <?php $_from = $this->_var['goods']['_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_image');$this->_foreach['fe_goods_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods_image']['total'] > 0):
    foreach ($_from AS $this->_var['goods_image']):
        $this->_foreach['fe_goods_image']['iteration']++;
?>
                <a href="<?php echo $this->_var['goods_image']['image_url']; ?>" data-fresco-group='goods_info' target="_blank"
                class="fresco <?php if ($this->_foreach['fe_goods_image']['iteration'] > 1): ?>hidden<?php endif; ?>">
                    查看大图
                </a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <div class="collect-goods">
                <a href="javascript:collect_goods(<?php echo $this->_var['goods']['goods_id']; ?>);">
                </a>
            </div>
            <div class="share-list">
                <em>
                    分享到：
                </em>
                
                <div class="jiathis_style" style="margin-top: 3px;">
                    <a class="jiathis_button_qzone">
                    </a>
                    <a class="jiathis_button_tsina">
                    </a>
                    <a class="jiathis_button_tqq">
                    </a>
                    <a class="jiathis_button_weixin">
                    </a>
                    <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis"
                    target="_blank">
                    </a>
                </div>
                <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js"
                charset="utf-8">
                </script>
                
            </div>
        </div>
    </div>
    <div class="col-main ml20 goods-attr ">
        <form method="post" id="join_group_form" action="index.php?app=groupbuy&amp;id=<?php echo $this->_var['group']['group_id']; ?>">
            <div class="goods-name mb10">
                <?php echo htmlspecialchars($this->_var['group']['group_name']); ?>
                <br />
            </div>
            <div class="attribute">
                <div class="attr-detail float-left">
                    <div class="rate pb10">
                        <p>
                            <span class="t">
                                商品名称：
                            </span>
                            <span>
                                <?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>
                            </span>
                        </p>
                        <p>
                            <span class="t">
                                商品原价：
                            </span>
                            <span class="price">
                                <del style="color:#999;"><?php echo price_format($this->_var['goods']['price']); ?></del>
                            </span><br />

                        </p>
                         <p>
                            <span class="t">
                                团购价格：
                            </span>
                            <span class="price">
                                <?php echo price_format($this->_var['group']['group_price']); ?>
                            </span>
                        </p>
                        <p>
                            <span class="t">
                                所属品牌：
                            </span>
                            <span>
                                <?php echo htmlspecialchars($this->_var['goods']['brand']); ?>
                            </span>
                        </p>
                        <p>
                            <span class="t">
                                商品标签：
                            </span>
                            &nbsp;&nbsp;
                            <?php $_from = $this->_var['goods']['tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'tag');if (count($_from)):
    foreach ($_from AS $this->_var['tag']):
?>
                            <?php echo $this->_var['tag']; ?>&nbsp;&nbsp;&nbsp;
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </p>
                        <p>
                            <span class="t">
                                商品评分：
                            </span>
                            <span id="evaluation">
                            </span>
                        </p>
                        <p>
                            <span class="t">
                                团购说明：
                            </span>
                            <span style="width:510px;color:#999;">
                                <?php if ($this->_var['group']['group_desc']): ?>
                                <?php echo $this->_var['group']['group_desc']; ?>
                                <?php else: ?>
                                暂无
                                <?php endif; ?>
                            </span>
                        </p>
                        <?php if ($this->_var['group']['max_per_user'] > 0): ?>
                        <p>
                            <span class="t">
                                每人限购：
                            </span>
                            <span>
                                <?php echo $this->_var['group']['max_per_user']; ?>
                            </span>
                        </p>
                        <?php endif; ?>
                        <p>
                            <span class="t">
                                成团件数：
                            </span>
                            <span>
                                <b class="inverse_proportion">
                                    <i style="width:<?php echo $this->_var['group']['left_per']; ?>%;">
                                    </i>
                                </b>
                            </span>
                            <span class="ml20 needed">
                                <?php if ($this->_var['group']['min_quantity'] > $this->_var['group']['quantity']): ?> 还差<?php echo $this->_var['group']['left_quantity']; ?>件
                                <?php else: ?> 团购已完成&nbsp;(已订<?php echo $this->_var['group']['quantity']; ?>件商品) <?php endif; ?>
                            </span>
                        </p>
                        <p>
                            <span class="t">
                                所在地区：
                            </span>
                            <span>
                                <?php echo htmlspecialchars($this->_var['store']['region_name']); ?>
                            </span>
                        </p>
                        <?php if ($this->_var['group']['left_time']): ?>
                        <p class="countdown mt10 mb10">
                            <span class="t">
                                剩余时间：
                            </span>
                            <span class="time NumDays">
                                <?php echo $this->_var['group']['left_time']['d']; ?>
                            </span>
                            <em>
                                天
                            </em>
                            <span class="time NumHours">
                                <?php echo $this->_var['group']['left_time']['h']; ?>
                            </span>
                            <em>
                                小时
                            </em>
                            <span class="time NumMins">
                                <?php echo $this->_var['group']['left_time']['m']; ?>
                            </span>
                            <em>
                                分
                            </em>
                            <span class="time NumSeconds">
                                <?php echo $this->_var['group']['left_time']['s']; ?>
                            </span>
                            <em>
                                秒
                            </em>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="buy-btn gr-btn">
                        <div class="i-want-join step-one  hidden">
                            <div class="close-btn">
                                X
                            </div>
                            <table>
                                <tr>
                                    <th>
                                        <?php echo htmlspecialchars($this->_var['goods']['spec_name']); ?>
                                    </th>
                                    <th>
                                        原价
                                    </th>
                                    <th>
                                        团购价
                                    </th>
                                    <?php if ($this->_var['group']['ican']['join'] || $this->_var['group']['ican']['join_info']): ?>
                                    <th style="width:70px;">
                                        购买数量
                                    </th>
                                    <?php endif; ?>
                                </tr>
                                <?php $_from = $this->_var['goods']['_specs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
                                <tr>
                                    <td>
                                        <?php echo $this->_var['spec']['spec']; ?>
                                        <input ectype="spec" name="spec[]" type="hidden" class="text" value="<?php echo $this->_var['spec']['spec']; ?>"
                                        />
                                        <input ectype="spec_id" name="spec_id[]" type="hidden" class="text" value="<?php echo $this->_var['spec']['spec_id']; ?>"
                                        />
                                    </td>
                                    <td>
                                        <?php echo price_format($this->_var['spec']['price']); ?>
                                    </td>
                                    <td>
                                        <?php echo price_format($this->_var['spec']['group_price']); ?>
                                    </td>
                                    <?php if ($this->_var['group']['ican']['join'] || $this->_var['group']['ican']['join_info']): ?>
                                    <td>
                                        <?php if ($this->_var['group']['ican']['join']): ?>
                                        <input style="width:70px;" ectype='quantity' name="quantity[]" type="text"
                                        class="text" />
                                        <?php endif; ?>
                                        <?php if ($this->_var['group']['ican']['join_info']): ?>
                                        <?php echo $this->_var['spec']['my_qty']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                <tr height="20">
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    </td>
                                    <td colspan="2" style="text-align:right;">
                                        <input id="join" type="button" value="确定并填写相关信息" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="i-want-join step-tow  hidden">
                            <div class="close-btn">
                                X
                            </div>
                            <table>
                                <tr>
                                    <td>
                                        <h1>
                                            参团人信息
                                        </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        请认真填写以下信息，以便店主与您联系
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        真实姓名：
                                        <input name="link_man" type="text" class="text" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        联系电话：
                                        <input name="tel" type="text" class="text" />
                                    </td>
                                </tr>
                                <tr height="50">
                                    <td colspan="2">
                                        <input name="join" style="margin-left:139px;" type="submit" value="参加团购"
                                        />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php if ($this->_var['group']['ican']['join']): ?>
                        <a onclick="$('.step-one').show(300);" class="add-to-cart btn ml10 pointer">
                            我要参加团购
                        </a>
                        <?php endif; ?>
                        <?php if ($this->_var['group']['ican']['join_info']): ?>
                        <span class="ml10 mr10">
                            我参加了该团购活动
                        </span>
                        <?php endif; ?>
                        <?php if ($this->_var['group']['ican']['exit']): ?>
                        <input name="exit" class="btn add-to-cart input" type="submit" value="退出团购"
                        />
                        <?php endif; ?>
                        <?php if ($this->_var['group']['ican']['buy']): ?>
                        <a class="buy-now btn pointer" onclick="window.location.href='index.php?app=order&goods=groupbuy&group_id=<?php echo $_GET['id']; ?>'">
                            购买
                        </a>
                        <?php endif; ?>
                        <?php if (! $this->_var['group']['ican']['join'] && ! $this->_var['group']['ican']['join_info'] && ! $this->_var['group']['ican']['buy']): ?>
                        <span style="margin-left:10px;" class="buy-now btn">
                            <?php echo $this->_var['group']['state_desc']; ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="store-info-g w210 float-right mt10">
                    <div class="store-info border mb10">
                        <h3 class="border-b">
                            <span>
                                <?php echo htmlspecialchars($this->_var['store']['store_name']); ?>
                            </span>
                        </h3>
                        <div class="content">
                            <dl class="border-b total_evaluation w-full clearfix">
                                <dt>
                                    综合评分：
                                </dt>
                                <dd>
                                    <div class="raty">
                                        <span style="width:<?php echo ($this->_var['store']['evaluation_rate'] == '') ? '0' : $this->_var['store']['evaluation_rate']; ?>;">
                                        </span>
                                    </div>
                                    <b>
                                        <?php echo ($this->_var['store']['avg_evaluation'] == '') ? '0' : $this->_var['store']['avg_evaluation']; ?>
                                    </b>
                                    分
                                </dd>
                            </dl>
                            <div class="rate-info">
                                <p>
                                    <strong>
                                        店铺动态评分
                                    </strong>
                                    与行业相比
                                </p>
                                <ul>
                                    <li>
                                        商品评分
                                        <span class="credit">
                                            <?php echo $this->_var['store']['avg_goods_evaluation']; ?>
                                        </span>
                                        <span class="<?php echo $this->_var['store']['industy_compare']['goods_compare']['class']; ?>">
                                            <i>
                                            </i>
                                            <?php echo $this->_var['store']['industy_compare']['goods_compare']['name']; ?>
                                            <em>
                                                <?php if ($this->_var['store']['industy_compare']['goods_compare']['value'] == 0): ?>
                                                ----
                                                <?php else: ?>
                                                <?php echo $this->_var['store']['industy_compare']['goods_compare']['value']; ?>%
                                                <?php endif; ?>
                                            </em>
                                        </span>
                                    </li>
                                    <li>
                                        服务评分
                                        <span class="credit">
                                            <?php echo $this->_var['store']['avg_service_evaluation']; ?>
                                        </span>
                                        <span class="<?php echo $this->_var['store']['industy_compare']['service_compare']['class']; ?>">
                                            <i>
                                            </i>
                                            <?php echo $this->_var['store']['industy_compare']['service_compare']['name']; ?>
                                            <em>
                                                <?php if ($this->_var['store']['industy_compare']['service_compare']['value'] == 0): ?>
                                                ----
                                                <?php else: ?>
                                                <?php echo $this->_var['store']['industy_compare']['goods_compare']['value']; ?>%
                                                <?php endif; ?>
                                            </em>
                                        </span>
                                    </li>
                                    <li>
                                        发货评分
                                        <span class="credit">
                                            <?php echo $this->_var['store']['avg_shipped_evaluation']; ?>
                                        </span>
                                        <span class="<?php echo $this->_var['store']['industy_compare']['shipped_compare']['class']; ?>">
                                            <i>
                                            </i>
                                            <?php echo $this->_var['store']['industy_compare']['shipped_compare']['name']; ?>
                                            <em>
                                                <?php if ($this->_var['store']['industy_compare']['shipped_compare']['value'] == 0): ?>
                                                ----
                                                <?php else: ?>
                                                <?php echo $this->_var['store']['industy_compare']['shipped_compare']['value']; ?>%
                                                <?php endif; ?>
                                            </em>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <dl class="border-b contact_us dl-c-s clearfix">
                                <dt>
                                    联系方式：
                                </dt>
                                <dd>
                                    <?php if ($this->_var['store']['im_qq']): ?>
                                    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo htmlspecialchars($this->_var['store']['im_qq']); ?>&site=qq&menu=yes">
                                        <img border="0" src="http://wpa.qq.com/pa?p=1:<?php echo htmlspecialchars($this->_var['store']['im_qq']); ?>:4"
                                        alt="点击这里给我发消息" title="点击这里给我发消息" />
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($this->_var['store']['im_ww']): ?>
                                    <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo urlencode($this->_var['store']['im_ww']); ?>&site=cntaobao&s=2&charset=<?php echo $this->_var['charset']; ?>">
                                        <img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo urlencode($this->_var['store']['im_ww']); ?>&site=cntaobao&s=2&charset=<?php echo $this->_var['charset']; ?>"
                                        alt="Wang Wang" />
                                    </a>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <dl class="dl-c-s w-full clearfix">
                                <dt>
                                    店铺名称：
                                </dt>
                                <dd>
                                    <?php echo htmlspecialchars($this->_var['store']['store_name']); ?>
                                </dd>
                            </dl>
                            <dl style="padding-top:2px;" class="dl-c-s w-full clearfix">
                                <dt>
                                    信用度：
                                </dt>
                                <dd>
                                    <?php if ($this->_var['store']['credit_value'] >= 0): ?>
                                    <img src="<?php echo $this->_var['store']['credit_image']; ?>" alt="" align="absmiddle" />
                                    <?php endif; ?>
                                </dd>
                            </dl>
                            <?php if ($this->_var['store']['certifications']): ?>
                            <dl style="padding-top:1px;" class="dl-c-s w-full clearfix">
                                <dt style="margin-top:2px;">
                                    认证：
                                </dt>
                                <dd>
                                    <?php $_from = $this->_var['store']['certifications']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cert');if (count($_from)):
    foreach ($_from AS $this->_var['cert']):
?>
                                    <?php if ($this->_var['cert'] == "autonym"): ?>
                                    <a href="<?php echo url('app=article&act=system&code=cert_autonym'); ?>" target="_blank"
                                    title="实名认证">
                                        <img src="<?php echo $this->res_base . "/" . 'images/cert_autonym.gif'; ?>" />
                                    </a>
                                    <?php elseif ($this->_var['cert'] == "material"): ?>
                                    <a href="<?php echo url('app=article&act=system&code=cert_material'); ?>" target="_blank"
                                    title="实体店铺">
                                        <img src="<?php echo $this->res_base . "/" . 'images/cert_material.gif'; ?>" />
                                    </a>
                                    <?php endif; ?>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </dd>
                            </dl>
                            <?php endif; ?>
                            <?php if ($this->_var['store']['tel']): ?>
                            <dl style="padding-top:1px;" class="dl-c-s w-full clearfix">
                                <dt>
                                    创店时间：
                                </dt>
                                <dd>
                                    <?php echo local_date("Y-m-d",$this->_var['store']['add_time']); ?>
                                </dd>
                            </dl>
                            <?php endif; ?>
                            <dl style="padding-top:2px;" class="dl-c-s w-full clearfix">
                                <dt>
                                    联系电话：
                                </dt>
                                <dd>
                                    <?php echo htmlspecialchars($this->_var['store']['tel']); ?>
                                </dd>
                            </dl>
                            <dl style="padding-top:2px;padding-bottom:10px;" class="dl-c-s border-b w-full clearfix">
                                <dt>
                                    详细地址：
                                </dt>
                                <dd>
                                    <?php echo htmlspecialchars($this->_var['store']['address']); ?>
                                </dd>
                            </dl>
                            <div class="go2store">
                                <a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>">
                                    进入商家店铺
                                </a>
                                <a href="javascript:collect_store(<?php echo $this->_var['store']['store_id']; ?>)">
                                    收藏该店铺
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>