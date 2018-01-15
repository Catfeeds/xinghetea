<?php echo $this->fetch('member.header.html'); ?>
<?php echo $this->_var['editor_upload']; ?>
<?php echo $this->_var['build_editor']; ?> 
<script type="text/javascript">
//<!CDATA[

function add_uploadedfile(file_data)
{
    if(file_data.instance == 'desc_image'){
        $('#desc_images').append('<li style="z-index:4" file_name="'+ file_data.file_name +'" file_path="'+ file_data.file_path +'" ectype="handle_pic" file_id="'+ file_data.file_id +'"><input type="hidden" name="desc_file_id[]" value="'+ file_data.file_id +'"><div class="pic" style="z-index: 2;"><img src="<?php echo $this->_var['site_url']; ?>/'+ file_data.file_path +'" width="80" height="80" alt="'+ file_data.file_name +'" /></div><div ectype="handler" class="bg" style="z-index: 3;display:none"><p class="operation"><a href="javascript:void(0);" class="cut_in" ectype="insert_editor" ecm_title="插入编辑器"></a><span class="delete" ectype="drop_image" ecm_title="删除"></span></p></div></li>');
                trigger_uploader();
        if(EDITOR_SWFU.getStats().progressNum == 0){
            window.setTimeout(function(){
                $('#editor_uploader').css('opacity', 0);
				$('*[ectype="handle_pic"]').css('z-index', 999);
            },5000);
        }
    }
}
function drop_image(goods_file_id)
{
    if (confirm(lang.uploadedfile_drop_confirm))
	{
		var url = SITE_URL + '/index.php?app=seller_fullgift&act=drop_image';
		$.getJSON(url, {'id':goods_file_id}, function(data){
			if (data.done)
			{
				$('*[file_id="' + goods_file_id + '"]').remove();
			}
			else
			{
				alert(data.msg);
			}
		});
	}
}

$(function(){
     $('#goods_form').validate({
        errorPlacement: function(error, element){
            $(element).next('label').remove();
            $(element).after(error);
        },
        success       : function(label){
			label.removeClass('error');
            label.addClass('validate_right').text('OK!');
        },
        onkeyup : false,
        rules : {
            goods_name : {
                required   : true
            },
            price      : {
                number     : true,
                required : true,
                min : 0
            }
        },
        messages : {
            goods_name  : {
                required   : '标题不能为空'
            },
            price       : {
                number   : '价格必须是数值',
                required : '价格不能为空',
                min : '价格必须大于等于零'
            }
        }
    });
});
//]]>
</script>
<div id="page-promotool" class="page-promotool">
  <div class="content">
    <div class="totline"></div>
    <div class="botline"></div>
    <?php echo $this->fetch('member.menu.html'); ?>
    <div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
      <?php echo $this->fetch('member.submenu.html'); ?>
      <div  class="wrap fullgift-add"> 
        <?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
        <div class="notice-word">
          <p><?php echo $this->_var['appAvailable']['msg']; ?></p>
        </div>
        <?php else: ?>
        <div class="publish public" style="margin-top:-21px;">
        <form method="post" id="goods_form" enctype="multipart/form-data">
          <h5 class="box-title">1.赠品基本信息</h5>
          <ul class="box-body">
            <li class="subbox clearfix">
              <label class="subbox-title">赠品标题：</label>
              <div class="subbox-body">
                <input style="width:500px;" type="text" name="goods_name" value="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" class="text width_normal" />
              </div>
            </li>
            <li class="subbox clearfix">
              <label class="subbox-title">赠品价格：</label>
              <div class="subbox-body">
                <input name="price" value="<?php echo $this->_var['goods']['price']; ?>" type="text" class="text width_short" />
              </div>
            </li>
          </ul>
          <h5 class="box-title">2.赠品图片及描述</h5>
          <ul class="box-body">
            <li class="subbox clearfix">
              <label class="subbox-title">赠品图片：</label>
              <div class="subbox-body">
                <div class="multimage-wrapper">
                  <div class="multimage-panels clearfix">
                    <div class="image-fields float-left">
                      <div class="image-demo"> 
                        <?php if ($this->_var['goods']['default_image']): ?> 
                        <img src="<?php echo $this->_var['goods']['default_image']; ?>" width="120" /> 
                        <?php else: ?> 
                        <span class="red">* 主图</span>
                        <p class="gray">建议350*350</p>
                        <?php endif; ?> 
                      </div>
                    </div>
                    <div class="upload_btn float-left"> <span class="btn-txt">图片上传</span>
                      <input name="goods_image" id="goods_image" type="file" class="btn-file-input">
                      <div class="text">支持JPEG和静态的GIF格式图片，不支持GIF动画图片，上传图片大小不能超过2M。建议尺寸350*350</div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="subbox clearfix">
              <label class="subbox-title">赠品描述：</label>
              <div class="subbox-body">
                <div class="add_wrap">
                  <div class="editor">
                    <div>
                      <textarea name="description" id="description"  style="width:713px; height:400px;"><?php echo htmlspecialchars($this->_var['goods']['description']); ?></textarea>
                    </div>
                    <div class="multimage-wrapper descimage-wrapper">
                      <ul class="multimage-tabs clearfix">
                        <li class="selected">插入描述图片</li>
                      </ul>
                      <div class="multimage-panels clearfix">
                        <div style=" position: relative; top: 10px; z-index: 5;"> <a class="btn-upload-image" id="open_editor_uploader"><b>上传文件</b></a>
                          <div class="upload_con" id="editor_uploader" style=" opacity:0; left:150px;">
                            <div class="upload_con_top"></div>
                            <div class="upload_wrap">
                              <ul>
                                <li class="EDITOR_SWFU_filePicker">
                                  <div id="divSwfuploadContainer">
                                    <div id="divButtonContainer"> <span id="editor_upload_button"></span> </div>
                                  </div>
                                </li>
                                <li>
                                  <iframe src="index.php?app=comupload&act=view_iframe&id=<?php echo $this->_var['id']; ?>&belong=<?php echo $this->_var['belong']; ?>&instance=desc_image" width="86" height="30" scrolling="no" frameborder="0"></iframe>
                                </li>
                                <li id="open_editor_remote" class="btn2">远程地址</li>
                              </ul>
                              <div id="editor_remote" class="upload_file" style="display:none">
                                <iframe src="index.php?app=comupload&act=view_remote&id=<?php echo $this->_var['id']; ?>&belong=<?php echo $this->_var['belong']; ?>&instance=desc_image" width="272" height="39" scrolling="no" frameborder="0"></iframe>
                              </div>
                              <div id="editor_upload_progress"></div>
                            </div>
                            <div class="upload_con_bottom"></div>
                          </div>
                          <div class="text">支持JPEG和静态的GIF格式图片，不支持GIF动画图片，上传图片大小不能超过2M.浏览文件时可以按住ctrl或shift键多选</div>
                        </div>
                      </div>
                      <div class="multimage-piclist clearfix">
                        <ul id="desc_images" class="preview J_descriptioneditor">
                          <?php $_from = $this->_var['desc_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'desc_image');if (count($_from)):
    foreach ($_from AS $this->_var['desc_image']):
?>
                          <li ectype="handle_pic" file_name="<?php echo htmlspecialchars($this->_var['desc_image']['file_name']); ?>" file_path="<?php echo $this->_var['desc_image']['file_path']; ?>" file_id="<?php echo $this->_var['desc_image']['file_id']; ?>">
                            <input type="hidden" name="desc_file_id[]" value="<?php echo $this->_var['desc_image']['file_id']; ?>">
                            <div class="pic"> <img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['desc_image']['file_path']; ?>" width="80" height="80" alt="<?php echo htmlspecialchars($this->_var['desc_image']['file_name']); ?>" title="<?php echo htmlspecialchars($this->_var['desc_image']['file_name']); ?>" /> </div>
                            <div ectype="handler" class="bg">
                              <p class="operation"> <a class="cut_in" ectype="insert_editor" href="javascript:void(0);" ecm_title="插入编辑器"></a> <span class="delete" ectype="drop_image" ecm_title="删除"></span> </p>
                            </div>
                          </li>
                          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                      </div>
                      <?php if ($this->_var['desc_images']): ?>
                      <div class="notice-word" style="padding:14px;">
                        <p class="yellow">插入方法：将鼠标移至需要插入的图片上面，然后点击插入按钮，即可将图片插入到编辑器中。</p>
                      </div>
                      <?php endif; ?> 
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
          <h5 class="box-title">4.其他设置</h5>
          <ul class="box-body">
            <li class="subbox clearfix">
              <label class="subbox-title">是否上架：</label>
              <div class="subbox-body"> <span class="slide-checkbox-radio clearfix">
                <input type="checkbox" name="if_show" value="1" id="check_1" class="slide-box slide-checkbox" <?php if ($this->_var['goods']['if_show'] || ! $this->_var['goods']): ?> checked="checked" <?php endif; ?>/>
                <label for="check_1" class="slide-trigger" style="height:24px;"></label>
                </span> </div>
            </li>
          </ul>
          <div class="issuance">
            <input type="submit" class="btn" value="提交" />
          </div>
        </form>
      </div>
      <?php endif; ?> 
    </div>
    <div class="clear"></div>
    <div class="adorn_right1"></div>
    <div class="adorn_right2"></div>
    <div class="adorn_right3"></div>
    <div class="adorn_right4"></div>
  </div>
    <div class="clear"></div>
</div>
</div>
<?php echo $this->fetch('footer.html'); ?> 