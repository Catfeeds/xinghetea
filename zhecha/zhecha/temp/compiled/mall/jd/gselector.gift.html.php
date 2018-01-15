<script type="text/javascript">
//<!CDATA[
$(function(){
	
	/* 初始化弹出层数据 */
	init();
 
	$('#gs_submit').click(function(){
		if(DATA_LIST_TEMP.length < 1 || DATA_LIST_TEMP.length > 10) {
			msg(getLangMessage('records_error'));
		}
		else {
			$('.J_RecordError').hide();
			gs_submit('<?php echo $_GET['id']; ?>','<?php echo $_GET['name']; ?>','<?php echo $_GET['callback']; ?>');
		}
	});
	$('#cancel_button').click(function(){
        DialogManager.close('gselector-gift');
    });
	
	$('.J_GselectorSearch').click(function(){
		showPage(1);
	});
	
});

//]]>
</script>
<div class="gselector-meal">
	<form>
		<div class="form-filter">
            <input type="text" name="gs_goods_name" id="gs_goods_name" value="" />
            <input type="button" value="提交" class="J_GselectorSearch btn-gselector-small" /> 
    	</div>
        <div class="form-products">
        	<div class="data-list-item">
                <div class="hd traderates-index-col">
                	<ul class="clearfix">
                    	<li class="col-1 center">宝贝名称</li>
                        <li class="col-2">价格</li>
                        <li class="col-3">库存</li>
                        <li class="col-4 center">操作</li>
                    </ul>
                </div>
                <div class="bd traderates-index-content">
           			<div class="item-list traderates-index-col" ectype="gselector-goods-list"></div>
        		</div>
            </div>
			<div class="mt20 clearfix" ectype="gselector-page-info"></div>
        </div>
        <div class="J_ListAdded lst-added clearfix">
        	<h3 class="float-left">已添加的赠品</h3>
            <ul class="sel-list float-left" ectype="sel-list"></ul>
			<div class="clear"></div>
        </div>
        <div class="J_Warning notice-word mt10 hidden"><p></p></div>
        <div class="submit mt20 mb20">
        	<input type="button" id="gs_submit" class="btn" value="提交" />
            <input type="button" id="cancel_button" class="btn2" value="取消" />
        </div>
    </form>

</div>