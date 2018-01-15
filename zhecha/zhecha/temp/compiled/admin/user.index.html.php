<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>会员管理</p>
  <ul class="subnav">
    <li><span>管理</span></li>
    <li><a class="btn1" href="index.php?app=user&amp;act=add">新增</a></li>
  </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=user&act=get_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
    		{display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
			{display: '用户名', name : 'user_name', width : 50, sortable : true, align: 'center'},
			{display: '真实姓名', name : 'real_name', width : 100, sortable : true, align: 'center'},
    		{display: '电子邮箱', name : 'email', width : 150, sortable : true, align: 'center'},
			{display: '手机', name : 'phone_mob', width : 80, sortable : true, align: 'center'},
			{display: '注册时间', name : 'reg_time', width: 100, sortable : true, align : 'center'},    		
			{display: '最后登录时间', name : 'last_login', width: 150, sortable : true, align : 'center'},
			{display: '最后登录IP', name : 'last_ip', width: 100, sortable : true, align : 'center'},  
			{display: '登录次数', name : 'logins', width: 50, sortable : true, align : 'center'},
			{display: '管理员', name : 'if_admin', width: 100, sortable : true, align : 'center'} 		
    		],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operate},
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
			{display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operate}	
        ],
		searchitems : [
			{display: '用户名', name : 'user_name'},
			{display: '真实姓名', name : 'real_name'},
            {display: '电子邮箱', name : 'email'},
			{display: '手机', name : 'phone_mob'}
        ],
    	title: '会员列表'
    });
});
function fg_operate(name, bDiv) {
	if(name == 'add'){
		window.location.href = 'index.php?app=user&act=add';
		return false;
	}
	var itemlist = new Array();
	$('.trSelected',bDiv).each(function(){
		itemlist.push($(this).attr('data-id'));
	});
	if (name == 'del') {
	   if($('.trSelected',bDiv).length==0){
		   parent.layer.alert('没有选择操作项',{icon: 0});
			return false;
	   }
       fg_delete(itemlist,'user');
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