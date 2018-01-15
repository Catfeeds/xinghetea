<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
	<p>每日结转</p>
	<ul class="subnav">
    	<li><span>每日结转</span></li>  	
    	<li><a class="btn1" href="index.php?app=balance_account">消费对账</a></li>
    </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=daily_summary&act=get_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
			{display: '电子币', name : 'deduct', width : 200, sortable : true, align: 'center'},
			{display: '积分', name : 'integral', width : 200, sortable : true, align: 'center'},
			{display: '日期', name : 'date', width : 200, sortable : true, align: 'center'}		

    		],
        buttons : [
			{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operate}
        ],
		searchitems : [
			{display: '日期', name : 'date'}           
        ],
    	sortname: "date",
    	sortorder: "desc",
    	title: '每日结转'
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