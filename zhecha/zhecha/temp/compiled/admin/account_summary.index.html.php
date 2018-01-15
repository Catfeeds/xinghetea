<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
	<p>账户统计</p>
	<ul class="subnav">
    	<li><a class="btn1" href="index.php?app=daily_summary">每日结转</a></li>
    	<li><a class="btn1" href="index.php?app=balance_account">消费对账</a></li>
    	<li><span>账户统计</span></li>
    </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=account_summary&act=get_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
			{display: '类型', name : 'type', width : 200, sortable : true, align: 'center'},
			{display: '昨日余额', name : 'yesterday_account', width : 200, sortable : true, align: 'center'},
			{display: '实际余额', name : 'real_account', width : 200, sortable : true, align: 'center'},		
			{display: '状态', name : 'status', width : 200, sortable : true, align: 'center'},
			{display: '日期', name : 'date', width : 200, sortable : true, align: 'center'},

    		],
        buttons : [
			{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operate}
        ],
		searchitems : [
			{display: '日期', name : 'date'}           
        ],
    	sortname: "date",
    	sortorder: "desc",
    	title: '账户统计'
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
       fg_delete(itemlist,'integral');
	}
	if(name == 'csv'){
		if($('.trSelected',bDiv).length==0){
		   parent.layer.confirm('您确定要下载全部数据吗？',{icon: 3, title:'提示'},function(index){
				fg_csv(itemlist);
				parent.layer.close(index);
			},function(index){
				parent.layer.close(index);
			});
	   }else{
		   fg_csv(itemlist);
	   }
	}
}
</script>
<?php echo $this->fetch('footer.html'); ?> 