<?php echo $this->fetch('member.header.html'); ?> 
<script>
$(function(){
	// 百度地图API功能
	var map = new BMap.Map("allmap");
	<?php if ($this->_var['store']['latlng']): ?>
	showMap(map, new BMap.Point(<?php echo $this->_var['store']['lng']; ?>,<?php echo $this->_var['store']['lat']; ?>));
	<?php else: ?>	
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			showMap(map, new BMap.Point(r.point.lng, r.point.lat));
		}
		else {
			layer.open({content: this.getStatus(), time: 3});
		}        
	},{enableHighAccuracy: true, maximumAge:60})	
	<?php endif; ?>	
});
function showMap(map, point)
{
	map.centerAndZoom(point, 15);
	map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
	map.addControl(new BMap.NavigationControl());// 左上角，添加比例尺   
	var marker = new BMap.Marker(point);// 创建标注
	map.addOverlay(marker);             // 将标注添加到地图中
	marker.enableDragging();
	marker.addEventListener("dragend", function(e){    
		$("input[name='latlng']").val(e.point.lat + "," + e.point.lng);
		
		var pt = new BMap.Point(e.point.lng, e.point.lat);
		var geoc = new BMap.Geocoder();    
		geoc.getLocation(pt, function(rs){
			var addComp = rs.addressComponents;
			var address = addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber;
			var infoWindow = new BMap.InfoWindow(address);  // 创建信息窗口对象   
			map.openInfoWindow(infoWindow, pt);      // 打开信息窗口
			
		});
	})
}


</script>
<div id="page-my_store-map" class="mb10">
	<article class="padding10 page-body">
	<div class="notice-word">
		<p>将标注拖放到您店铺所在的位置</p>
	</div>
	<div class="info">
		<form class="J_AjaxForm" method="post">
			<input class="J_AjaxFormFields" type="hidden" name="latlng" value="<?php echo $this->_var['store']['latlng']; ?>" />
			<input type="hidden" class="J_AjaxFormFields J_AjaxFormSuccessRet" name="ret_url" value="<?php echo $this->_var['site_url']; ?>/<?php echo url('app=my_store&act=map'); ?>" />
			<div id="allmap" style="height:356px;border:3px #ddd solid;"></div>
			<div class="mt10">
				<input type="submit" class="btn-alipay J_AjaxFormSubmit" value="提交" />
			</div>
		</form>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>