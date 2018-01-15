<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
  <p>管理员管理</p>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=admin&act=get_xml';
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
			{display: '登录次数', name : 'logins', width: 50, sortable : true, align : 'center'}		
    		],
        buttons : [
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate }
        ],
		searchitems : [
			{display: '用户名', name : 'user_name'},
			{display: '真实姓名', name : 'real_name'},
            {display: '电子邮箱', name : 'email'},
			{display: '手机', name : 'phone_mob'}
        ],
    	title: '管理员列表'
    });
});
function fg_operate(name, bDiv) {
	if($('.trSelected',bDiv).length>0){
        var itemlist = new Array();
		$('.trSelected',bDiv).each(function(){
			itemlist.push($(this).attr('data-id'));
		});
		if (name == 'del') {	
            fg_delete(itemlist,'admin');
		}
    } else {
		parent.layer.alert('没有选择操作项',{icon: 0});
        return false;
    }
}
</script>
<?php echo $this->fetch('footer.html'); ?> 