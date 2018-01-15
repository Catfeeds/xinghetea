<?php echo $this->fetch('header.html'); ?>
<div id="rightTop">
    <p>积分管理</p>
    <ul class="subnav">
    	<li><a class="btn1" href="index.php?app=integral">积分列表</a></li>
        <li><a class="btn1" href="index.php?app=integral&act=setting">积分设置</a></li>
        <li><span>积分充值</span></li>
    </ul>
</div>
<div class="info">
 <form method="post">
  <table class="infoTable">
  	  <tr>
        <th class="paddingT15">用户ID:</th>
        <td class="paddingT15 wordSpacing5">
        	<?php echo $this->_var['user']['user_id']; ?>
        </td>
     </tr>
      <tr>
        <th class="paddingT15">用户名:</th>
        <td class="paddingT15 wordSpacing5">
        	<?php echo htmlspecialchars($this->_var['user']['user_name']); ?>
        </td>
     </tr>
    <tr>
      <th class="paddingT15">操作类型</th>
   	  <td class="paddingT15 wordSpacing5">
      	<select name="flow">
        	<option value="add">充值</option>
            <option value="minus">减扣</option>
        </select>
      </td>
     </tr>
     <tr>
      <th class="paddingT15">积分金额</th>
      <td class="paddingT15 wordSpacing5"><input type="text" name="amount"  /></td>
     </tr>
     <tr>
      <th class="paddingT15">操作备注</th>
      <td class="paddingT15 wordSpacing5">
      	<textarea name="flag" style="width:250px;height:100px;"></textarea>
      </td>
     </tr>
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