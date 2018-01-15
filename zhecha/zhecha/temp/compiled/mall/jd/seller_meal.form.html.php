<?php echo $this->fetch('member.header.html'); ?>
<?php echo $this->_var['editor_upload']; ?>
<?php echo $this->_var['build_editor']; ?>
<div class="content">
	<div class="totline"></div>
	<div class="botline"></div>
	<?php echo $this->fetch('member.menu.html'); ?>
	<div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
		<?php echo $this->fetch('member.submenu.html'); ?>
		<div class="wrap">
			<div class="public_select">
				<div class="promotool">
					<div class="bundle bundle-form">
                    	<?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
                        <div class="notice-word"><p><?php echo $this->_var['appAvailable']['msg']; ?></p></div>
                       	<?php else: ?>
						<form method="post" id="meal_form" enctype="multipart/form-data">
							<ul class="form-elem">
								<li class="clearfix">
									<label class="float-left">套餐标题：<span class="field-required">*</span></label>
									<p class="float-left">
										<input type="text" name="title" class="input-long" value="<?php echo $this->_var['meal']['title']; ?>" />
										<span class="field-notice">限定在30个汉字内（180个字符）</span></p>
								</li>
								<li class="clearfix" style="width:728px;">
									<label class="float-left">套餐宝贝：<span class="field-required">*</span></label>
									<div class="float-left lst-products clearfix">
										<div class="th clearfix">
											<p class="cell-thumb float-left">搭配宝贝</p>
											<p class="cell-title float-left">宝贝标题</p>
											<p class="cell-price float-left">原价</p>
											<p class="cell-action float-left">操作</p>
										</div>
										<div class="entity">
											<ul ectype="meal_goods_list">
												<?php $_from = $this->_var['meal']['meal_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
												<li class="clearfix">
													<p class="cell-input">
														<input type="hidden" name="selected_ids[]" value="<?php echo $this->_var['goods']['goods_id']; ?>" />
													</p>
													<p class="cell-thumb float-left"> <a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><img src="<?php echo $this->_var['goods']['default_image']; ?>" width="50" height="50" /></a> </p>
													<p class="cell-title float-left"><a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></p>
													<p class="J_getPrice cell-price float-left" price="<?php echo $this->_var['goods']['price']; ?>"><?php echo $this->_var['goods']['price']; ?></p>
													<p class="cell-action float-left"><a class="J_MealDel" href="javascript:;">删除</a></p>
												</li>
												<?php endforeach; else: ?>
												<div class="pt5 pb5 align2 gray-color">请添加搭配宝贝</div>
												<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
											</ul>
										</div>
										<p> <a href="javascript:;" gs_id="gselector-meal" gs_name="goods_name" gs_callback="gs_callback" gs_title="添加搭配宝贝" gs_width="690"  gs_type="meal" gs_store_id="<?php echo $this->_var['store_id']; ?>" ectype="gselector" gs_opacity="0.05" gs_class="simple-blue" name="gselector-meal" id="gselector-meal" class="btn-add-product">添加搭配宝贝</a> </p>
									</div>
								</li>
								<li class="clearfix">
									<label class="float-left">套餐原价：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<p class="float-left">
										<input class="J_priceTotal gray" type="text" readonly="readonly" value="" />
									</p>
								</li>
								<li class="clearfix">
									<label class="float-left">套餐一口价：<span class="field-required">*</span></label>
									<p class="float-left">
										<input type="text" name="price" value="<?php echo $this->_var['meal']['price']; ?>" />
										<span class="field-notice">搭配一口价不得高于单个宝贝原价总和。</span></p>
								</li>
								<li class="clearfix">
									<label class="float-left">套餐描述：<span class="field-required">*</span></label>
									<div class="float-left">
										<div class="editor" style="background:none;padding-top:0;margin-top:0;width:700px;">
											<div>
												<textarea name="description" id="description" style="width:100%; height:350px;"><?php echo htmlspecialchars($this->_var['meal']['description']); ?></textarea>
											</div>
											<div style=" position: relative; top: 10px; z-index: 5;"><a class="btn3" id="open_editor_uploader">上传文件</a>
												<div class="upload_con" id="editor_uploader" style=" opacity:0;">
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
														<div class="upload_txt"> <span>支持JPEG和静态的GIF格式图片，不支持GIF动画图片，上传图片大小不能超过2M.浏览文件时可以按住ctrl或shift键多选</span> </div>
													</div>
													<div class="upload_con_bottom"></div>
												</div>
											</div>
											<ul id="desc_images" class="preview  J_descriptioneditor">
												<?php $_from = $this->_var['files_belong_meal']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'file');if (count($_from)):
    foreach ($_from AS $this->_var['file']):
?>
												<li ectype="handle_pic" file_name="<?php echo htmlspecialchars($this->_var['file']['file_name']); ?>" file_path="<?php echo $this->_var['file']['file_path']; ?>" file_id="<?php echo $this->_var['file']['file_id']; ?>">
													<input type="hidden" name="file_id[]" value="<?php echo $this->_var['file']['file_id']; ?>">
													<div class="pic"> <img src="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['file']['file_path']; ?>" width="80" height="80" alt="<?php echo htmlspecialchars($this->_var['file']['file_name']); ?>" title="<?php echo htmlspecialchars($this->_var['file']['file_name']); ?>" /></div>
													<div ectype="handler" class="bg">
														<p class="operation"> <a href="javascript:void(0);" class="cut_in" ectype="insert_editor" ecm_title="插入编辑器"></a> <span class="delete" ectype="drop_image" ecm_title="删除"></span> </p>
													</div>
												</li>
												<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
											</ul>
											<div class="clear"></div>
										</div>
									</div>
								</li>
								<li class="clearfix">
									<label class="float-left">&nbsp;</label>
									<p class="wrap_btn wrap_btn_blue mt20 float-left" style="margin-top: 80px;">
										<input type="submit" class="btn1" value="提交" />
									</p>
								</li>
							</ul>
						</form>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<iframe name="iframe_post" id="iframe_post" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>