<?php echo $this->fetch('header.html'); ?> 
<script type="text/javascript">
//<!CDATA[
$(function(){
    regionInit("region");
    $("#apply_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
			error_td.find('label').remove();
            error_td.siblings('dd').remove();
            error_td.append(error);
        },
        success: function(label){
            label.addClass('validate_right').removeClass('error');
        },
        onkeyup: false,
        rules: {
            owner_name: {
                required: true
            },
            store_name: {
                required: true,
                remote : {
                    url  : 'index.php?app=apply&act=check_name&ajax=1',
                    type : 'get',
                    data : {
                        store_name : function(){
                            return $('#store_name').val();
                        },
                        store_id : '<?php echo $this->_var['store']['store_id']; ?>'
                    }
                },
                maxlength: 20
            },
            tel: {
                required: true,
                minlength:6
            },
            image_1: {
                accept: "jpg|jpeg|png|gif"
            },
            image_2: {
                accept: "jpg|jpeg|png|gif"
            },
            notice: {
                required : true
            }
        },
        messages: {
            owner_name: {
                required: '请输入店主姓名'
            },
            store_name: {
                required: '请输入店铺名称',
                remote: '该店铺名称已存在，请您换一个',
                maxlength: '请控制在20个字以内'
            },
            tel: {
                required: '请输入联系电话',
                minlength: '请填写正确的电话号码'
            },
            image_1: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            image_2: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            notice: {
                required: '请阅读并同意入驻协议'
            }
        }
    });

	<?php if ($this->_var['store'] && $this->_var['store']['sgrade']): ?>
	$(".apply-submit li[sgid='<?php echo $this->_var['store']['sgrade']; ?>']").addClass('selected');
	<?php else: ?>
	$(".apply-submit .each:eq(0)").addClass('selected');
	$('input[name="sgrade_id"]').val($(".apply-submit .each:eq(0)").attr('sgid'));
	<?php endif; ?>

	$(".apply-submit .each").click(function(){
		$(this).addClass('selected');
		$(this).siblings().removeClass('selected');
		$('input[name="sgrade_id"]').val($(this).attr('sgid'));
	});
});
//]]>
</script>
<div id="main" class="w-full">
	<div class="page-apply">
		<div class="padding10 clearfix">
			<div class="apply-submit">
				<form method="post" enctype="multipart/form-data" action="<?php echo url('app=apply&step=2'); ?>" id="apply_form">
					<div class="sgrade clearfix">
						<div class="dt">店铺等级</div>
						<ul>
							<?php $_from = $this->_var['sgrades']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sgrade');$this->_foreach['fe_sgrade'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_sgrade']['total'] > 0):
    foreach ($_from AS $this->_var['sgrade']):
        $this->_foreach['fe_sgrade']['iteration']++;
?>
							<li class="each" sgid="<?php echo $this->_var['sgrade']['grade_id']; ?>" <?php if ($this->_foreach['fe_sgrade']['iteration'] % 2 == 0): ?> style="float:right;"<?php endif; ?>>
								<h2><?php echo $this->_var['sgrade']['grade_name']; ?></h2>
							</li>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</ul>
						<input name="sgrade_id" type="hidden" value="<?php echo $this->_var['store']['sgrade']; ?>" />
					</div>
					<dl>
						<dt>&nbsp;</dt>
						<dd class="widdt7">
							<input type="text" class="input" name="owner_name" placeholder="店主姓名" value="<?php echo htmlspecialchars($this->_var['store']['owner_name']); ?>"/>
						</dd>
					</dl>
					<dl>
						<dt>&nbsp;</dt>
						<dd>
							<input type="text" class="input" name="owner_card" placeholder="身份证号" value="<?php echo htmlspecialchars($this->_var['store']['owner_card']); ?>" />
						</dd>
					</dl>
					<dl>
						<dt>&nbsp;</dt>
						<dd>
							<input type="text" class="input" name="store_name" id="store_name" placeholder="店铺名称" value="<?php echo htmlspecialchars($this->_var['store']['store_name']); ?>"/>
						</dd>
					</dl>
					<dl>
						<dt class="mt10 mb5">所属分类</dt>
						<dd>
							<div class="select_add">
								<select name="cate_id">
									<option value="0">请选择...</option>
								   <?php echo $this->html_options(array('options'=>$this->_var['scategories'],'selected'=>$this->_var['scategory']['cate_id'])); ?>
								</select>
							</div>
						</dd>
					</dl>
					<dl>
						<dt class="mt10 mb5">所在地区</dt>
						<dd>
							<div class="select_add" id="region">
								<input type="hidden" name="region_id" value="<?php echo $this->_var['store']['region_id']; ?>" class="mls_id" />
								<input type="hidden" name="region_name" value="<?php echo $this->_var['store']['region_name']; ?>" class="mls_names" />
								<?php if ($this->_var['store']['region_name']): ?> <span><?php echo htmlspecialchars($this->_var['store']['region_name']); ?></span>
								<input type="button" value="编辑" class="edit_region" />
								<?php endif; ?>
								<select class="d_inline"<?php if ($this->_var['store']['region_name']): ?> style="display:none;"<?php endif; ?>>
									<option value="0">请选择...</option>
									 <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
								</select>
							</div>
						</dd>
					</dl>
					<dl>
						<dt>&nbsp;</dt>
						<dd>
							<input type="text" class="input" name="address" placeholder="详细地址" value="<?php echo htmlspecialchars($this->_var['store']['address']); ?>"/>
						</dd>
					</dl>
					<dl>
						<dt>&nbsp;</dt>
						<dd>
							<input type="text" class="input" name="zipcode" placeholder="邮政编码" value="<?php echo htmlspecialchars($this->_var['store']['zipcode']); ?>"/>
						</dd>
					</dl>
					<dl>
						<dt>&nbsp;</dt>
						<dd>
							<input type="text" class="input" name="tel" placeholder="联系电话" value="<?php echo htmlspecialchars($this->_var['store']['tel']); ?>"/>
						</dd>
					</dl>
					<dl class="clearboth">
						<dt  class="mt10 mb5">上传证件（不超过400KB）</dt>
						<dd>
							<input type="file" name="image_1" />
							<?php if ($this->_var['store']['image_1']): ?>
							<p class="d_inline"><img src="<?php echo $this->_var['store']['image_1']; ?>" width="50" style="vertical-align:middle;" /> <a href="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['store']['image_1']; ?>" target="_blank">查看</a></p>
							<?php endif; ?> </dd>
					</dl>
					<dl class="clearboth">
						<dt class="mt10 mb5">上传执照（不超过400KB）</dt>
						<dd>
							<input type="file" name="image_2" />
							<?php if ($this->_var['store']['image_2']): ?>
							<p class="d_inline"><img src="<?php echo $this->_var['store']['image_2']; ?>" width="50" style="vertical-align:middle;" /> <a href="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['store']['image_2']; ?>" target="_blank">查看</a></p>
							<?php endif; ?> </dd>
					</dl>
					<dl class="clearboth">
						<dt>&nbsp;</dt>
						<dd> <span class="btn-alipay">
							<input type="submit" value="提交" />
							</span> </dd>
					</dl>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $this->fetch('footer.html'); ?>