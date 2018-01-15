<div id="container_<?php echo $this->_var['stattype']; ?>"></div>
<script>
$(function () {
	$('#container_<?php echo $this->_var['stattype']; ?>').highcharts(<?php echo $this->_var['stat_json']; ?>);
});
</script>