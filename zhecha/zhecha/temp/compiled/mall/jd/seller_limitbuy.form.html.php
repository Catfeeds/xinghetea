<?php echo $this->fetch('member.header.html'); ?>
<style>
.txt {margin-right:20px}
.spec ul {width: 530px; overflow: hidden;}
.spec .td {padding-bottom: 10px;}
.spec li {float: left; margin-left: 6px; display: inline;}
.spec li input {text-align: center;}
.spec .th {padding: 3px 0; margin-bottom: 10px; border-top: 2px solid #e3e3e3; border-bottom: 1px solid #e3e3e3; background: #f8f8f8;}
</style>
<script type="text/javascript">
//<!CDATA[
$(function(){
    $('#start_time input').datepicker({dateFormat: 'yy-mm-dd'});
    $('#end_time input').datepicker({dateFormat: 'yy-mm-dd'});
    check_spec();

    $('#pro_form').validate({
        errorPlacement: function(error, element){
            $(element).next('label').remove();
            $(element).after(error);
        },
        success       : function(label){
			label.removeClass('error');
            label.addClass('validate_right').text('OK!');
        },
        onkeyup : false,
        rules : {
            pro_name : {
                required   : true
            },
            pro_desc : {
                maxlength   : 255
            },
            end_time      : {
                required     : true
            },
            goods_id      : {
                required   :true,
                min    : 1
            }
        },
        messages : {
            pro_name  : {
                required   : '请填写促销名称'
            },
            pro_desc  : {
                maxlength   : 'fill_max_pro_desc'
            },
            end_time       : {
                required     : '请填写结束时间'
            },
            goods_id      : {
                required:  '请先搜索限时打折商品',
                min   : '请先搜索限时打折商品'
            }
        }
    });

});

function gs_callback(){
    query_spec($('#goods_id').val());
}

function check_spec(){
	$('#submit_pro').unbind('click');
    $('#submit_pro').click(function(){
        $('label.error').remove();
        var qty = 0;
        var error = false;
        var price_empty = false;
		var pro_price_DY_price = false;// 促销价大于原价
		var discount_error = false;
		
        $('*[ectype="spec_item"]').each(function(){
            var obj_pro_price = $(this).find('input[ectype="pro_price"]');
            var pro_price = obj_pro_price.val();
			var obj_pro_type = $(this).find('select[ectype="pro_type"]');
			var price = $(this).find('li[ectype="price[]"]').text();
            qty++;
           
			if(pro_price == ''){
 				price_empty = obj_pro_price;
       			return false;
     		}
 			if(pro_price != '' && (!Number(pro_price) || pro_price < 0 || isNaN(pro_price))){
    			error = obj_pro_price;
				return false;
			}
			if(obj_pro_type.val()=='price' && (Number(pro_price) > Number(price))) {
				pro_price_DY_price = obj_pro_price;
				return false;
			}
			if(obj_pro_type.val()=='discount' && (pro_price=='' || (pro_price.split(".").length!=1 && pro_price.split(".")[1].length>=2) || Number(pro_price) < 0 || Number(pro_price) > 10 || (Math.round(Number(pro_price) * Number(price)*0.1*100)*0.01==Math.round(Number(price)*100)*0.01))){
				discount_error = obj_pro_price;
				return false;
			}
        })
        if(qty == 0){
            alert('请选择一个商品');
            return false;
        }
        if(error != false){
            error.focus();
            error.addClass('error');
			alert('请正确填写减价或折扣值，填写的数值代表，在原价的基础上减少多少价格或者打多少折扣');
            return false;
        }
        if(price_empty != false){
            price_empty.focus();
            price_empty.addClass('error');
			alert('优惠幅度不能为空！');
            return false;
        }
		if(pro_price_DY_price != false){
			pro_price_DY_price.focus();
			pro_price_DY_price.addClass('error');
			alert('优惠价不能大于原价');
			return false;
		}
		if(discount_error != false){
			discount_error.focus();
			discount_error.addClass('error');
			alert('折扣必须在0-10之间，而且最多只能有一个小数点');
			return false;
		}
    });
}

function query_spec(goods_id){
    $.getJSON('index.php?app=seller_limitbuy&act=query_goods_info',{
        'goods_id':goods_id
        },
        function(data){
            if(data.done){
                var goods = data.retval;
                $('#spec_name').html(goods.spec_name);
                $('ul[ectype="spec_item"]').remove();
                    $.each(goods._specs,function(i,item){
                        $('#pro_spec').append('<ul ectype="spec_item" class="td"><li class="distance2"><input name="spec_id[]" value="'+ item.spec_id +'" type="hidden" />'+ item.spec +'</li><li class="distance1">' + item.stock + '</li><li class="distance1" ectype="price[]">'+ item.price +'</li><li class="distance1"><input ectype="pro_price" name="pro_price'+item.spec_id+'" type="text" class="text width2" /></li><li class="distance1"><select ectype="pro_type" name="pro_type'+item.spec_id+'" ><option value="price">元</option><option value="discount">折</option></select></li></ul>');
                });
                check_spec();
            }
        });
}
//]]>
</script>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
    <?php echo $this->fetch('member.submenu.html'); ?>
    <div id="seller_limitbuy_form" class="wrap">
      <div class="public">
        <div class="promotool">
          <div class="bundle bundle-list"> 
            <?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
            <div class="notice-word">
              <p><?php echo $this->_var['appAvailable']['msg']; ?></p>
            </div>
            <?php else: ?>
            <div class="notice-word">
              <p class="yellow-big">温馨提示: 可设置某个商品某个时间段的折扣或减价价格。</p>
            </div>
            <form method="post" id="pro_form" enctype="multipart/form-data">
              <div class="information_index">
                <h4>限时打折基本信息</h4>
                <div class="add_wrap">
                  <div class="assort">
                    <p class="txt">促销名称:
                      <input type="text" name="pro_name" value="<?php echo htmlspecialchars($this->_var['limitbuy']['pro_name']); ?>" class="text" />
                      <span class="red">*</span></p>
                  </div>
                  <div class="assort">
                    <p class="txt">活动图片:
                      <input type="file" name="image" />
                      <span class="red">*</span>（上传一张图片 大小：300像素*200像素）</p>
                    <?php if ($this->_var['limitbuy']['image']): ?>
                    <p><img src="<?php echo $this->_var['limitbuy']['image']; ?>" height="30" /></p>
                    <?php endif; ?> 
                  </div>
                  <div class="assort">
                    <p class="txt" id="start_time"> 开始时间:
                      <input name="start_time" value="<?php echo local_date("Y-m-d",$this->_var['limitbuy']['start_time']); ?>" type="text" class="text width2" />
                    </p>
                    <p class="txt" id="end_time"> 结束时间:
                      <input name="end_time" value="<?php echo local_date("Y-m-d",$this->_var['limitbuy']['end_time']); ?>" type="text" class="text width2" />
                      <span class="red">*</span> </p>
                  </div>
                  <div class="assort">
                    <p class="txt"> 限时打折描述:
                      <textarea style="overflow-y: auto; width: 250px; vertical-align: top;" id="pro_desc" name="pro_desc" class="text"><?php echo htmlspecialchars($this->_var['limitbuy']['pro_desc']); ?></textarea>
                    </p>
                  </div>
                </div>
                <div class="add_wrap">
                  <div class="assort">
                    <p class="txt">选择商品:
                      <?php if (! $this->_var['goods']): ?>
                      <input gs_id="goods_id" gs_name="goods_name" gs_callback="gs_callback" gs_title="选择商品" gs_width="480" gs_type="store" gs_store_id="<?php echo $this->_var['store_id']; ?>" ectype="gselector" type="text" name="goods_name" id="goods_name" value="<?php echo htmlspecialchars($this->_var['limitbuy']['goods_name']); ?>" class="text" />
                      <span class="red">*</span> <?php else: ?>
                      <?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>
                      <?php endif; ?>
                      <input type="hidden" id="goods_id" name="goods_id" value="<?php echo $this->_var['limitbuy']['goods_id']; ?>" />
                    </p>
                  </div>
                  <div class="assort">
                    <p class="txt" style="float:left">价格策略: </p>
                    <div id="pro_spec" class="spec" style="float:left">
                      <ul class="th">
                        <li id="spec_name" class="distance2"><?php if ($this->_var['goods']): ?><?php echo $this->_var['goods']['spec_name']; ?><?php else: ?>规格<?php endif; ?></li>
                        <li class="distance1">库存</li>
                        <li class="distance1">原价</li>
                        <li class="distance2" style="width:130px;">优惠幅度</li>
                        <li class="distance1">减价或折扣</li>
                      </ul>
                      <?php $_from = $this->_var['goods']['_specs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
                      <ul ectype="spec_item" class="td">
                        <li class="distance2">
                          <input name="spec_id[]" value="<?php echo $this->_var['spec']['spec_id']; ?>" type="hidden" />
                          <?php echo $this->_var['spec']['spec']; ?> </li>
                        <li class="distance1"><?php echo $this->_var['spec']['stock']; ?></li>
                        <li class="distance1" ectype="price[]"><?php echo $this->_var['spec']['price']; ?></li>
                        <li class="distance2">
                          <input ectype="pro_price" name="pro_price<?php echo $this->_var['spec']['spec_id']; ?>" type="text" class="text width2" value="<?php echo $this->_var['spec']['pro_price']; ?>" />
                        </li>
                        <li>
                          <select ectype="pro_type" name="pro_type<?php echo $this->_var['spec']['spec_id']; ?>" >
                            <option value="price"<?php if ($this->_var['spec']['pro_type'] == 'price'): ?> selected="selected"<?php endif; ?>>元</option>
                            <option value="discount"<?php if ($this->_var['spec']['pro_type'] == 'discount'): ?> selected="selected"<?php endif; ?>>折</option>
                          </select>
                        </li>
                      </ul>
                      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> </div>
                  </div>
                  <div class="issuance" style="margin-left:70px; text-align:left">
                    <input id="submit_pro" type="submit" class="btn" value="提交" />
                  </div>
                </div>
              </div>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>