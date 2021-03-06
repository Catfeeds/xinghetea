<?php echo $this->fetch('header.html'); ?> 
<div id="rightTop">
	<p>手机短信管理</p>
    <ul class="subnav" style="margin-left:0px;">
        <li><a class="btn1" href="index.php?app=msg">发送记录</a></li>
        <li><span>短信用户</span></li>
        <li><a class="btn1" href="index.php?app=msg&act=add">分配短信</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=send">短信发送</a></li>
        <li><a class="btn1" href="index.php?app=msg&act=setting">设置</a></li>
    </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var data_url = 'index.php?app=msg&act=get_user_xml';
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
    		{display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
			{display: 'ID', name : 'user_id', width : 50, sortable : false, align: 'center'},
			{display: '用户名', name : 'user_name', width : 150, sortable : true, align: 'center'},
    		{display: '手机号码', name : 'phone_mob', width : 100, sortable : true, align: 'center'},
			{display: '开启功能', name : 'functions', width : 550, sortable : true, align: 'center'},
    		{display: '剩余数量', name : 'num', width : 100, sortable : true, align: 'center'}, 
			{display: '状态', name : 'state', width : 50, sortable : true, align: 'center'}	
    		],
		buttons : [
            {display: '<i class="fa fa-plus"></i>分配短信', name : 'add', bclass : 'add', title : '分配短信', onpress : fg_operate}
        ],
		searchitems : [
            {display: '用户名', name : 'user_name'}
        ],
    	title: '短信用户列表'
    });
});
function fg_operate(name, bDiv) {
	if(name == 'add'){
		window.location.href = 'index.php?app=msg&act=add';
		return false;
	}
}
</script>
<?php echo $this->fetch('footer.html'); ?>