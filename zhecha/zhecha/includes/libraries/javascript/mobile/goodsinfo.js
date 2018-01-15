/* spec对象 */
function spec(id, spec1, spec2, price, price_1, price_2, price_3, price_4, price_5, stock, goods_id)
{
    this.id    = id;
    this.spec1 = spec1;
    this.spec2 = spec2;
    this.price = price;
    this.price_1 = price_1;
    this.price_2 = price_2;
    this.price_3 = price_3;
    this.price_4 = price_4;
    this.price_5 = price_5;
    this.stock = stock;
	this.goods_id = goods_id;
}

/* goodsspec对象 */
function goodsspec(specs, specQty, defSpec)
{
    this.specs = specs;
    this.specQty = specQty;
    this.defSpec = defSpec;
    this.spec1 = null;
    this.spec2 = null;
    if (this.specQty >= 1)
    {
        for(var i = 0; i < this.specs.length; i++)
        {
            if (this.specs[i].id == this.defSpec)
            {
                this.spec1 = this.specs[i].spec1;
                if (this.specQty >= 2)
                {
                    this.spec2 = this.specs[i].spec2;
                }
                break;
            }
        }
    }

    // 取得某字段的不重复值，如果有spec1，以此为条件
    this.getDistinctValues = function(field, spec1)
    {
        var values = new Array();
        for (var i = 0; i < this.specs.length; i++)
        {
            var value = this.specs[i][field];
            if (spec1 != '' && spec1 != this.specs[i].spec1) continue;
            if ($.inArray(value, values) < 0)
            {
                values.push(value);
            }
        }
        return (values);
    }

    // 取得选中的spec
    this.getSpec = function()
    {
        for (var i = 0; i < this.specs.length; i++)
        {
            if (this.specQty >= 1 && this.specs[i].spec1 != this.spec1) continue;
            if (this.specQty >= 2 && this.specs[i].spec2 != this.spec2) continue;

            return this.specs[i];
        }
        return null;
    }

    // 初始化
    this.init = function()
    {
        if (this.specQty >= 1)
        {
            var spec1Values = this.getDistinctValues('spec1', '');
            for (var i = 0; i < spec1Values.length; i++)
            {
                if (spec1Values[i] == this.spec1)
                {
                    $(".handle ul:eq(0)").append("<li class='solid' onclick='selectSpec(1, this)'><a href='javascript:;'><span>" + spec1Values[i] + "</span><b></b></a></li>");

                }
                else
                {
                    $(".handle ul:eq(0)").append("<li class='dotted' onclick='selectSpec(1, this)'><a href='javascript:;'><span>" + spec1Values[i] + "</span><b></b></a></li>");
                }
            }
        }
        if (this.specQty >= 2)
        {
            var spec2Values = this.getDistinctValues('spec2', this.spec1);
            for (var i = 0; i < spec2Values.length; i++)
            {
                if (spec2Values[i] == this.spec2)
                {
                    $(".handle ul:eq(1)").append("<li class='solid' onclick='selectSpec(2, this)'><a href='javascript:;'><span>" + spec2Values[i] + "</span><b></b></a></li>");
                }
                else
                {
                    $(".handle ul:eq(1)").append("<li class='dotted' onclick='selectSpec(2, this)'><a href='javascript:;'><span>" + spec2Values[i] + "</span><b></b></a></li>");
                }
            }
        }
        var spec = this.getSpec();
		setGoodsProInfo(spec.goods_id, spec.id, spec.price,spec.price_1,spec.price_2,spec.price_3,spec.price_4,spec.price_5);
        $("[ectype='current_spec']").html(spec.spec1 + ' ' + spec.spec2);
    }
}

/* 选中某规格 num=1,2 */
function selectSpec(num, liObj)
{
	
    goodsspec['spec' + num] = $(liObj).find('a span').html();
	
    $(liObj).attr("class", "solid");
    $(liObj).siblings(".solid").attr("class", "dotted");

    // 当有2种规格并且选中了第一个规格时，刷新第二个规格
    if (num == 1 && goodsspec.specQty == 2)
    {
        goodsspec.spec2 = null;
        $(".aggregate").html("");
        $(".handle ul:eq(1) li[class='handle_title']").siblings().remove();

        var spec2Values = goodsspec.getDistinctValues('spec2', goodsspec.spec1);
        for (var i = 0; i < spec2Values.length; i++)
        {
            $(".handle ul:eq(1)").append("<li class='dotted' onclick='selectSpec(2, this)'><a href='javascript:;'><span>" + spec2Values[i] + "</span><b></b></a></li>");
        }
    }
    else
    {
        var spec = goodsspec.getSpec();
        if (spec != null)
        {
			setGoodsProInfo(spec.goods_id, spec.id, spec.price);
			$("[ectype='current_spec']").html(spec.spec1 + ' ' + spec.spec2);
            $("[ectype='goods_stock']").html(spec.stock);
        }
    }
}
function slideUp_fn()
{
    $('.ware_cen').slideUp('slow');
}
$(function(){
    goodsspec.init();
	
    //放大镜效果/
    if ($(".jqzoom img").attr('jqimg'))
    {
        $(".jqzoom").jqueryzoom({ xzoom: 425, yzoom: 310 });
    }
    // 图片替换效果
    $('.ware_box li').mouseover(function(){
        $('.ware_box li').removeClass();
        $(this).addClass('ware_pic_hover');
        $('.big_pic img').attr('src', $(this).children('img:first').attr('src'));
        $('.big_pic img').attr('jqimg', $(this).attr('bigimg'));
    });

    //点击后移动的距离
    var left_num = -61;

    //整个ul超出显示区域的尺寸
    var li_length = ($('.ware_box li').width() + 6) * $('.ware_box li').length - 305;

    $('.right_btn').click(function(){
        var posleft_num = $('.ware_box ul').position().left;
        if($('.ware_box ul').position().left > -li_length){
            $('.ware_box ul').css({'left': posleft_num + left_num});
        }
    });

    $('.left_btn').click(function(){
        var posleft_num = $('.ware_box ul').position().left;
        if($('.ware_box ul').position().left < 0){
            $('.ware_box ul').css({'left': posleft_num - left_num});
        }
    });

    // 加入购物车弹出层
    $('.close_btn').click(function(){
        $('.ware_cen').slideUp('slow');
    });
});

// 获取促销商品，会员价格等的优惠信息
function setGoodsProInfo(goods_id, spec_id, price, price_1, price_2, price_3, price_4, price_5)
{
	var url = REAL_SITE_URL + "/index.php?app=goods&act=getGoodsProInfo&goods_id="+goods_id+"&spec_id="+spec_id;
	$.getJSON(url,function(data){
		if (data.done){
			pro_price = data.retval.pro_price;
			pro_type  = data.retval.pro_type;
			
			// 目前只有限时打折商品有倒计时效果
			if(pro_type == 'limitbuy') {
				$('.J_CountDown').css('display', 'block');
			}
			$(".J_IsPro").css('display','block');
			$(".J_IsNotPro").css('display','none');
			$("[ectype='goods_price']").html('<del>'+price_format(price)+'</del>');
			$("[ectype='goods_pro_price']").html(price_format(pro_price));
		}
		else
		{
			$("[ectype='goods_price']").html(price_format(price));
			price_1 == 0 ? $("[ectype='goods_price_1']").html(price_format(price)):$("[ectype='goods_price_1']").html(price_format(price_1));
			price_2 == 0 ? $("[ectype='goods_price_2']").html(price_format(price)):$("[ectype='goods_price_2']").html(price_format(price_2));
			price_3 == 0 ? $("[ectype='goods_price_3']").html(price_format(price)):$("[ectype='goods_price_3']").html(price_format(price_3));
			price_4 == 0 ? $("[ectype='goods_price_4']").html(price_format(price)):$("[ectype='goods_price_4']").html(price_format(price_4));			
			price_5 == 0 ? $("[ectype='goods_price_5']").html(price_format(price)):$("[ectype='goods_price_5']").html(price_format(price_5));

//			$("[ectype='goods_price_1']").html(price_format(price_1));
//			$("[ectype='goods_price_2']").html(price_format(price_2));
//			$("[ectype='goods_price_3']").html(price_format(price_3));
//			$("[ectype='goods_price_4']").html(price_format(price_4));
//			$("[ectype='goods_price_5']").html(price_format(price_5));
			$('.J_CountDown').css('display', 'none');
			$(".J_IsPro").css('display','none');
			$(".J_IsNotPro").css('display','block');
		}
	});
}