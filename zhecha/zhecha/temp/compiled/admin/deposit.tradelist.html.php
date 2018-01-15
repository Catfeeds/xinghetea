<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>预存款管理</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=deposit">管理</a></li>
    <li><span>交易记录</span></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=drawlist">提现管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=rechargelist">充值管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=setting">系统设置</a></li>
  </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=deposit&act=get_trade_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
    		{display: '操作', name : 'operation', width : 50, sortable : false, align: 'center', className: 'handle'},
			{display: '创建时间', name : 'add_time', width : 150, sortable : true, align: 'center'},
			{display: '商户订单号', name : 'bizOrderId', width : 150, sortable : true, align: 'center'},
			{display: '交易号', name : 'tradeNo', width : 150, sortable : true, align: 'center'},
			{display: '交易标题', name : 'title', width : 200, sortable : true, align: 'center'},
			{display: '交易方', name : 'buyer_name', width : 60, sortable : false, align: 'center'}, 
			{display: '对方', name : 'party', width : 60, sortable : false, align: 'center'}, 
    		{display: '金额(元)', name : 'amount', width : 100, sortable : true, align: 'center'},    		
			{display: '状态', name : 'status', width: 150, sortable : true, align : 'center'}  		
    		],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
			{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operate}
        ],
		searchitems : [
			{display: '商户订单号', name : 'bizOrderId'},
            {display: '交易号', name : 'tradeNo'}
        ],
    	sortname: "add_time",
    	sortorder: "desc",
    	title: '交易记录列表'
    });
});
function fg_operate(name, bDiv) {
	var itemlist = new Array();
	$('.trSelected',bDiv).each(function(){
		itemlist.push($(this).attr('data-id'));
	});
	if (name == 'del') {
	   if($('.trSelected',bDiv).length==0){
		   parent.layer.alert('没有选择操作项',{icon: 0});
			return false;
	   }
       fg_delete(itemlist,'deposit','drop_trade');
	}
	if(name == 'csv'){
		if($('.trSelected',bDiv).length==0){
		   parent.layer.confirm('您确定要下载全部数据吗？',{icon: 3, title:'提示'},function(index){
				fg_csv(itemlist,'export_trade_csv');
				parent.layer.close(index);
			},function(index){
				parent.layer.close(index);
			});
	   }else{
		   fg_csv(itemlist,'export_trade_csv');
	   }
	}
}
</script>
<?php echo $this->fetch('footer.html'); ?> 