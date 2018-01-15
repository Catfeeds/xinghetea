<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
	<p>积分管理</p>
	<ul class="subnav">
    	<li><a class="btn1" href="index.php?app=integral">积分列表</a></li>
        <li><a class="btn1" href="index.php?app=integral&act=setting">积分设置</a></li>
        <li><span>积分记录</span></li>
    </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
	var user_id = '<?php echo $this->_var['user_info']['user_id']; ?>';
	var data_url = 'index.php?app=integral&act=get_record_xml&user_id='+user_id;
    $("#flexigrid").flexigrid({
    	url: data_url,
    	colModel : [
			{display: '来源/用途', name : 'type', width : 200, sortable : true, align: 'center'},
			{display: '积分变化', name : 'changes', width : 100, sortable : true, align: 'center'},
			{display: '余额', name : 'balance', width : 100, sortable : true, align: 'center'},
			{display: '状态', name : 'state', width : 100, sortable : true, align: 'center'},
			{display: '日期', name : 'add_time', width : 200, sortable : true, align: 'center'},
			{display: '备注', name : 'flag', width : 300, sortable : true, align: 'center'},		
    		],
    	sortname: "add_time",
    	sortorder: "desc",
    	title: '用户名 : <em class="red"><?php echo $this->_var['user_info']['user_name']; ?></em>'+' 积分记录列表'
    });
});
</script>
<?php echo $this->fetch('footer.html'); ?> 