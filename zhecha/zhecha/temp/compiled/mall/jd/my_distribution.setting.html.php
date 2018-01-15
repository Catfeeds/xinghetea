<?php echo $this->fetch('member.header.html'); ?>
<script type="text/javascript">
$(function(){
    $('#my_distribution').validate({
	  errorPlacement: function(error, element){
		  $(element).next('span').find('.field_notice').hide();
		  $(element).next('span').after(error);
	  },
	  success       : function(label){
		  label.addClass('validate_right').text('OK!');
	  },
     rules : {
            distribution_1 : {
                required   : true,
                number : true
            },
			distribution_2 : {
                required   : true,
                number : true
            },
			distribution_3 : {
                required   : true,
                number : true
            }
        },
        messages : {
            distribution_1  : {
                required   : '分销比率不能为空',
				number : '只能为数值'
            },
			distribution_2 : {
                required   : '分销比率不能为空',
				number : '只能为数值'
            },
			distribution_3 : {
                required   : '分销比率不能为空',
				number : '只能为数值'
            }
        }
    });
});
</script>
<div class="content">
  <div class="totline"></div>
  <div class="botline"></div>
  <?php echo $this->fetch('member.menu.html'); ?>
  <div id="right"> <?php echo $this->fetch('member.curlocal.html'); ?>
    <?php echo $this->fetch('member.submenu.html'); ?>
    <div class="wrap">
      <div class="public">
        <div class="promotool">
          <div class="bundle bundle-list"> 
            <?php if ($this->_var['appAvailable'] != 'TRUE'): ?>
            <div class="notice-word">
              <p><?php echo $this->_var['appAvailable']['msg']; ?></p>
            </div>
            <?php else: ?>
            <div class="notice-word">
              <p class="yellow-big">店铺三级分销比率设置，若开启则按设置的比率分配分销利润。</p>
            </div>
            <div class="information">
              <form method="post" id="my_distribution">
                <div class="setup info shop">
                  <table style="width: 100%">
                    <tr>
                      <th width="60">开启分销：</th>
                      <td><input type="radio" name="enable_distribution" checked value="1" />
                        <label>是</label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="enable_distribution" <?php if (! $this->_var['store']['enable_distribution']): ?>checked<?php endif; ?> value="0" />
                        <label>否</label></td>
                    </tr>
                    <tr>
                      <th>一级分销：</th>
                      <td><input type="text" name="distribution_1" class="text width_short"  value="<?php echo $this->_var['store']['distribution_1']; ?>" /> % 
                         <span><label class="field_notice">分销佣金比率，例如10%</label></span></td>
                    </tr>
                    <tr>
                      <th>二级分销：</th>
                      <td><input type="text" name="distribution_2" class="text width_short"  value="<?php echo $this->_var['store']['distribution_2']; ?>" />
                        % <span><label class="field_notice">分销佣金比率，例如10%</label></span></td>
                    </tr>
                    <tr>
                      <th>三级分销：</th>
                      <td><input type="text" name="distribution_3" class="text width_short"  value="<?php echo $this->_var['store']['distribution_3']; ?>" />
                        % <span><label class="field_notice">分销佣金比率，例如10%</label></span></td>
                    </tr>
                  </table>
                  <div class="issuance" style="text-align:left; margin-left:80px; margin-top:20px;">
                    <input type="submit" class="btn" value="提交" />
                  </div>
                </div>
              </form>
            </div>
            
            <?php endif; ?> 
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<?php echo $this->fetch('footer.html'); ?>