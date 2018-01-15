<?php echo $this->fetch('header.html'); ?> 
<style type="text/css">
.period input{vertical-align:middle;margin-right:3px; display:inline-block}
.period label{margin-right:10px; width:60px; display:inline-block;margin-bottom:5px;}
</style>
<script type="text/javascript">
//<!CDATA[
$(function(){
    $('#appmarket_form').validate({
        errorPlacement: function(error, element){
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
        success       : function(label){
            label.addClass('right').text('OK!');
        },
        rules : {
			appid  : {
                required : true
            },
            title : {
                required : true,
                maxlength: 100
            },
            logo  : {
                accept : 'png|jpe?g|gif'
            },
			"config[charge]" : {
				number     : true,
				required : true,
				min : 0
			}
        },
        messages : {
			appid  : {
                required : '应用名称不能为空'
            },
            title : {
                required : '标题不能为空',
                maxlength: '标题长度不能大于100个字符'
            },
            logo  : {
                accept   : 'logo格式错误，只支持gif,jpeg,jpg,png格式'
            },
			"config[charge]" : {
				number     : '此项仅能为数字',
				required : '收费不能为空',
                min : '收费金额必须大于或等于零'
			}
        }
    });
});

function add_uploadedfile(file_data)
{
    var newImg = '<tr id="' + file_data.file_id + '" class="tatr2" ectype="handle_pic" file_name="'+file_data.file_name+'" file_path="'+file_data.file_path+'" file_id="'+file_data.file_id+'"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><td><img width="40px" height="40px" src="' + SITE_URL + '/' + file_data.file_path + '" /></td><td>' + file_data.file_name + '</td><td><a ectype="insert_editor" href="javascript:;">插入编辑器</a> | <a href="javascript:drop_uploadedfile(' + file_data.file_id + ');">删除</a></td></tr>';
    $('#thumbnails').prepend(newImg);
}

function drop_uploadedfile(file_id)
{
    if(!window.confirm(lang.uploadedfile_drop_confirm)){
        return;
    }
    $.getJSON('index.php?app=appmarket&act=drop_uploadedfile&file_id=' + file_id, function(result){
        if(result.done){
            $('#' + file_id).remove();
        }else{
            alert('drop_error');
        }
    });
}
//]]>
</script> 
<?php echo $this->_var['build_editor']; ?>
<?php echo $this->_var['build_upload']; ?>
<div id="rightTop">
	<p>应用市场</p>
	<ul class="subnav">
		<li><a class="btn1" href="index.php?app=appmarket">管理</a></li>
		<li><span><?php if ($this->_var['appmarket']['aid']): ?>编辑<?php else: ?>新增<?php endif; ?></span></li>
	</ul>
</div>
<div class="info">
	<form method="post" enctype="multipart/form-data" id="appmarket_form">
		<table class="infoTable">
			<tr>
				<th class="paddingT15"> 应用名称:</th>
				<td class="paddingT15 wordSpacing5">
                	<?php if ($this->_var['appmarket']): ?>
                    <?php echo $this->_var['lang'][$this->_var['appmarket']['appid']]; ?>
                    <?php else: ?>
					<select name="appid">
						<option value="">请选择...</option>
						<?php $_from = $this->_var['applist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
						<option value="<?php echo $this->_var['item']['key']; ?>" <?php if ($this->_var['item']['key'] == $this->_var['appmarket']['appid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_var['item']['value']; ?></option>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</select>
                    <?php endif; ?>
				</td>
			</tr>
			<tr>
				<th class="paddingT15"> 应用分类:</th>
				<td class="paddingT15 wordSpacing5">
					<select name="category">
						<option value="1">营销工具</option>
					</select>
				</td>
			</tr>
			<tr>
				<th class="paddingT15"> 标题:</th>
				<td class="paddingT15 wordSpacing5"><input style="width:400px;" id="title" type="text" name="title" value="<?php echo $this->_var['appmarket']['title']; ?>" /></td>
			</tr>
			<tr>
				<th class="paddingT15"> 应用摘要:</th>
				<td class="paddingT15 wordSpacing5"><textarea class="infoTableInput" style="width:400px;height:34px;" id="summary" name="summary"><?php echo htmlspecialchars($this->_var['appmarket']['summary']); ?></textarea></td>
			</tr>
			<tr>
				<th class="paddingT15"> 图片标识:</th>
				<td class="paddingT15 wordSpacing5">
                    <div class="input-file-show">
                        <span class="show"><a href="javascript:;" class="show_image"><i class="fa fa-image"></i></a></span>
                        <span class="type-file-box">
                            <input type="text" name="textfield" class="type-file-text" />
                            <input type="button" name="button" value="选择上传..." class="type-file-button" />
                            <input class="type-file-file" name="logo" id="app_logo" type="file" size="30" hidefocus="true">
                            <label class="field_notice">该应用在前台显示的图片，建议尺寸400*400像素</label>
                        </span>
                        <?php if ($this->_var['appmarket']['logo']): ?>
                        <div class="show_img"><img src="<?php echo $this->_var['appmarket']['logo']; ?>" max_height="90" /></div>
                        <?php endif; ?>
                    </div>
                </td>
			</tr>
			<tr>
				<th class="paddingT15"> 收费标准:</th>
				<td class="paddingT15 wordSpacing5">
					<input type="text" class="width_short" id="config[charge]" type="text" name="config[charge]" value="<?php echo $this->_var['appmarket']['config']['charge']; ?>"> 元/月
				</td>
			</tr>
			
			<tr>
				<th class="paddingT15"> 允许购买期限:</th>
				<td class="paddingT15 wordSpacing5 period">
					<?php $_from = $this->_var['period']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['fe_item'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_item']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['fe_item']['iteration']++;
?>
					<label><input type="checkbox" name="config[period][]" value="<?php echo $this->_var['item']['key']; ?>" <?php if ($this->_var['appmarket']['config']['period'] && in_array ( $this->_var['item']['key'] , $this->_var['appmarket']['config']['period'] )): ?> checked="checked"<?php endif; ?> /><?php echo $this->_var['item']['value']; ?></label>
					<?php if ($this->_foreach['fe_item']['iteration'] % 7 == 0): ?><br /><?php endif; ?>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</td>
			</tr>
			
			<tr>
				<th class="paddingT15"> <label for="description">介绍:</label></th>
				<td class="paddingT15 wordSpacing5"><textarea id="description" name="description" style="width:650px;height:400px;"><?php echo htmlspecialchars($this->_var['appmarket']['description']); ?></textarea></td>
			</tr>
			<tr>
				<th>图片上传:</th>
				<td height="100" valign="middle">
                	<div id="divUploadTypeContainer">
						<input name="upload_types" id="bat_upload" type="radio" value="bat_upload" checked="checked" />
						<label for="bat_upload">批量上传</label>
						<input name="upload_types" id="com_upload" type="radio" value="com_upload" />
						<label for="com_upload">普通上传</label>
					</div>
					<div id="divSwfuploadContainer" class="WebUpload_filePicker">
						<div id="divButtonContainer"> <span id="spanButtonPlaceholder"></span> </div>
						<div id="divFileProgressContainer"></div>
					</div>
					<iframe id="divComUploadContainer" style="display:none;" src="index.php?app=comupload&act=view_iframe&id=<?php echo $this->_var['id']; ?>&belong=<?php echo $this->_var['belong']; ?>" width="500" height="46" scrolling="no" frameborder="0"> </iframe></td>
			</tr>
			<tr>
				<th>已传图片:</th>
				<td><div class="tdare">
						<table  width="600px" cellspacing="0" class="dataTable">
							<tbody id="thumbnails" class="J_descriptioneditor">
								<?php $_from = $this->_var['files_belong_appmarket']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'file');if (count($_from)):
    foreach ($_from AS $this->_var['file']):
?>
								<tr class="tatr2" id="<?php echo $this->_var['file']['file_id']; ?>" ectype="handle_pic" file_name="<?php echo htmlspecialchars($this->_var['file']['file_name']); ?>" file_path="<?php echo $this->_var['file']['file_path']; ?>" file_id="<?php echo $this->_var['file']['file_id']; ?>">
									<input type="hidden" name="file_id[]" value="<?php echo $this->_var['file']['file_id']; ?>" />
									<td><img alt="<?php echo $this->_var['file']['file_name']; ?>" src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['file']['file_path']; ?>" width="40px" height="40px" /></td>
									<td><?php echo $this->_var['file']['file_name']; ?></td>
									<td><a ectype="insert_editor" href="javascript:;">插入编辑器</a> | <a href="javascript:drop_uploadedfile(<?php echo $this->_var['file']['file_id']; ?>);">删除</a></td>
								</tr>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</tbody>
						</table>
					</div></td>
			</tr>
			<tr>
				<th class="paddingT15"> <label>开放订购:</label></th>
				<td class="paddingT15">
                    <span class="onoff">
                    <label class="cb-enable <?php if ($this->_var['appmarket']['status']): ?>selected<?php endif; ?>">是</label>
                    <label class="cb-disable <?php if (! $this->_var['appmarket']['status']): ?>selected<?php endif; ?>">否</label>
                    <input name="status" value="1" type="radio" <?php if ($this->_var['appmarket']['status']): ?>checked<?php endif; ?>>
                    <input name="status" value="0" type="radio" <?php if (! $this->_var['appmarket']['status']): ?>checked<?php endif; ?>>
                  </span>
                  <span class="grey notice"></span>
			</tr>
			<tr>
				<th></th>
				<td class="ptb20"><input class="formbtn" type="submit" value="提交" />
					<input class="formbtn" type="reset" value="重置" /></td>
			</tr>
		</table>
	</form>
</div>
<?php echo $this->fetch('footer.html'); ?> 