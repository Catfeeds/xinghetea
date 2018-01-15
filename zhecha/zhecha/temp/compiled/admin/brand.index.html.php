<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>商品品牌</p>
    <ul class="subnav">
        <li><span>管理</span></li>
        <li><a class="btn1" href="index.php?app=brand&amp;act=apply">待审核</a></li>
    </ul>
</div>
<div id="flexigrid"></div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
    	url: 'index.php?app=brand&act=get_xml',
    	colModel : [
    		{display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
			{display: '品牌ID', name : 'brand_id', width : 50, sortable : true, align: 'center'},
    		{display: '品牌名称', name : 'brand_name', width : 200, sortable : true, align: 'center'},
			{display: '类别', name : 'tag', width : 100, sortable : true, align: 'left'},
    		{display: '图片标识', name : 'brand_logo', width : 150, sortable : true, align: 'center'},    		
			{display: '排序', name : 'sort_order', width: 50, sortable : true, align : 'center'},
			{display: '推荐', name : 'recommended', width: 50, sortable : true, align : 'center'},  
			{display: '通过审核', name : 'if_show', width: 50, sortable : true, align : 'center'} 		
    		],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operate},
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'del', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate }
        ],
		searchitems : [
            {display: '品牌名称', name : 'brand_name'},
            {display: '类别', name : 'tag'}
        ],
    	sortname: "sort_order",
    	sortorder: "asc",
    	title: '品牌列表'
    });
});
function fg_operate(name, bDiv) {
	if(name == 'add'){
		window.location.href = 'index.php?app=brand&act=add';
		return false;
	}
	if($('.trSelected',bDiv).length>0){
        var itemlist = new Array();
		$('.trSelected',bDiv).each(function(){
			itemlist.push($(this).attr('data-id'));
		});
		if (name == 'del') {	
            fg_delete(itemlist,'brand');
		}
    } else {
		parent.layer.alert('没有选择操作项',{icon: 0});
       return false;
    }
}
</script>
<?php echo $this->fetch('footer.html'); ?> 
