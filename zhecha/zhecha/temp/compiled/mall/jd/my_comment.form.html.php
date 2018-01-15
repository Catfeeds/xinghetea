<script type="text/javascript">
$(function(){
     $('#message').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        rules : {
                     content : {
                         required : true,
                         rangelength : [0,255,'<?php echo $this->_var['charset']; ?>']
                     }
                  },
        messages : {
                     content : {
                         required : '回复内容不能为空',
                         rangelength: '回复字数不得超过255字符'
                        }
                     }
         })
})
</script>
<style type="text/css">
.height26 {line-height:26px;}
.eject_con .add_float {width:340px;}
</style>
<div class="eject_con">
    <div class="add_float">
        <form id="message" target="pop_warning" method="post" action="index.php?app=my_comment&act=reply">
        <div id="warning"></div>
        <ul>
            <li>
                <h3>评价内容:</h3>
                 <p><label class="height26"><?php echo nl2br(htmlspecialchars($this->_var['my_comment_data']['comment'])); ?></label></p>
            </li>
            <li>
                <h3>回复评价:</h3>
                <p><textarea class="text width11" name="content"><?php echo $this->_var['my_comment_data']['reply_content']; ?></textarea><br /></p>
                <input type="hidden" name="rec_id" value="<?php echo $this->_var['my_comment_data']['rec_id']; ?>" />
            </li>
        </ul>
        <div class="submit"><input type="submit" class="btn" value="确认" /></div>
        </form>
    </div>
</div>