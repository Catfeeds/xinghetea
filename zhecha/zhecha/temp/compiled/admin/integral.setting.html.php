<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>积分管理</p>
    <ul class="subnav">
    	<li><a class="btn1" href="index.php?app=integral">积分列表</a></li>
        <li><span>积分设置</span></li>
    </ul>
</div>
<div class="info">
  <form method="post">
  	<table class="infoTable">
            <tr>
                <th class="paddingT15">
                    是否启用积分功能:</th>
                <td class="paddingT15">
                  <span class="onoff">
                    <label class="cb-enable <?php if ($this->_var['setting']['integral_enabled']): ?>selected<?php endif; ?>">开启</label>
                    <label class="cb-disable <?php if (! $this->_var['setting']['integral_enabled']): ?>selected<?php endif; ?>">关闭</label>
                    <input name="integral_enabled" value="1" type="radio" <?php if ($this->_var['setting']['integral_enabled']): ?>checked<?php endif; ?>>
                    <input name="integral_enabled" value="0" type="radio" <?php if (! $this->_var['setting']['integral_enabled']): ?>checked<?php endif; ?>>
                  </span>
                  <span class="grey notice"></span>      
          		</td>
            </tr>
            <tr style="display:none">
                <th class="paddingT15">
                    积分兑换比率:</th>
                <td class="paddingT15 wordSpacing5">
                    <input class="infoTableInput" type="text" name="exchange_rate" value="1"/>
                <span class="grey">例如：金钱：积分=1:1，则填写 1，金钱：积分=1:100，则填写 0.01。默认为0.1</span>
                </td>
            </tr>
            <tr style="display:none">
                <th class="paddingT15">
                    注册积分:</th>
                <td class="paddingT15 wordSpacing5">
                    <input class="infoTableInput" type="text" name="register_integral" value="0"/>
                    <span class="grey">用户注册后免费赠送积分值，默认为0</span>
                </td>
            </tr>
             <tr style="display:none">
                <th class="paddingT15">
                    签到积分:</th>
                <td class="paddingT15 wordSpacing5">
                    <input class="infoTableInput" type="text" name="sign_in_integral" value="0"/>
					<span class="grey">用户每天登陆获得的积分值，默认为0</span>
                </td>
            </tr>
            <tr style="display:none">
                <th class="paddingT15">
                    开店赠送积分:</th>
                <td class="paddingT15 wordSpacing5">
                    <input class="infoTableInput" type="text" name="open_integral" value="0"/>
                    <span class="grey">用户开店后免费赠送积分值，默认为0</span>
                </td>
            </tr>
            <tr style="display:none">
                <th class="paddingT15">
                    购买商品送积分:</th>
                <td class="paddingT15 wordSpacing5">
                    <span class="grey" style="width:70px;margin-right:10px;display:inline-block;">店铺等级</span>
                    <span class="grey">赠送积分比率（所赠积分即商品价格乘以该比率）</span>
                </td>
            </tr>
            <?php $_from = $this->_var['sgrades']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
            <tr style="display:none">
                <th class="paddingT15">
                 </th>
                <td class="paddingT15 wordSpacing5">
                	<span style="width:70px;margin-right:10px;display:inline-block;"><?php echo $this->_var['item']['grade_name']; ?>:</span>
                    <input style="width:55px;" type="text" name="buying_integral[<?php echo $this->_var['item']['grade_id']; ?>]" value="<?php echo ($this->_var['item']['buying_integral'] == '') ? '0' : $this->_var['item']['buying_integral']; ?>"/>
                </td>
            </tr>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			 <tr>            
			 <th></th>
            <td class="ptb20">
                <input class="formbtn J_FormSubmit" type="submit" name="Submit" value="提交" />
                <input class="formbtn" type="reset" name="Submit2" value="重置" />
            </td>
        </tr>
        </table>
  </form>
</div>
<?php echo $this->fetch('footer.html'); ?>