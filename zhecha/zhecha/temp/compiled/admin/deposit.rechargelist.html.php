<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>预存款管理</p>
  <ul class="subnav">
    <li><a class="btn1" href="index.php?app=deposit">管理</a></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=tradelist">交易记录</a></li>
    <li><a class="btn1" href="index.php?app=deposit&act=drawlist">提现管理</a></li>
    <li><span>充值管理</span></li>
    <li><a class="btn1" href="index.php?app=deposit&amp;act=setting">系统设置</a></li>
  </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=deposit&act=get_rechargelist_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
    		{display: '操作', name : 'operation', width : 100, sortable : false, align: 'center', className: 'handle'},
			{display: '创建时间', name : 'add_time', width : 120, sortable : true, align: 'center'},
			{display: '商户订单号', name : 'orderId', width : 100, sortable : false, align: 'center'},
    		{display: '交易号', name : 'tradeNo', width : 150, sortable : true, align: 'center'},
			{display: '用户名', name : 'user_name', width : 50, sortable : false, align: 'center'},
			{display: '名称', name : 'name', width : 50, sortable : false, align: 'center'},
			{display: '金额(元)', name : 'amount', width : 100, sortable : true, align: 'center'}, 
    		{display: '充值方式', name : 'is_online', width : 50, sortable : true, align: 'center'},
			{display: '状态', name : 'status', width: 100, sortable : true, align : 'center'}, 
			{display: '审批员', name : 'examine', width : 60, sortable : true, align: 'center'},     		 		
    		],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
			{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operate}
        ],
		searchitems : [
			{display: '商户订单号', name : 'orderId'},
			{display: '交易号', name : 'tradeNo'},
            {display: '用户名', name : 'user_name'}
        ],
    	sortname: "add_time",
    	sortorder: "desc",
    	title: '充值记录列表'
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
       fg_delete(itemlist,'deposit','drop_recharge');
	}
	if(name == 'csv'){
		if($('.trSelected',bDiv).length==0){
		   parent.layer.confirm('您确定要下载全部数据吗？',{icon: 3, title:'提示'},function(index){
				fg_csv(itemlist,'export_recharge_csv');
				parent.layer.close(index);
			},function(index){
				parent.layer.close(index);
			});
	   }else{
		   fg_csv(itemlist,'export_recharge_csv');
	   }
	}
}
function fg_recharge_verify(id,content){
	parent.layer.confirm('请您认真核对充值信息，并做审核操作！'+content,{icon: 3, title:'提示'},function(index){
		$.ajax({
			type: "GET",
			dataType: "json",
			url: 'index.php?app=deposit&act=recharge_verify&tradesn='+id,
			success: function(data){
				if (data.done){
					parent.layer.alert('审核成功!',{icon:1});
					$("#flexigrid").flexReload();
				} else {
					parent.layer.alert(data.msg);
				}
			}
		});
	},function(index){
		parent.layer.close(index);
	});	
}
</script>
<?php echo $this->fetch('footer.html'); ?> 