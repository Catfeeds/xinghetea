<?php echo $this->fetch('member.header.html'); ?>
<div id="page-member-setting">
	<div class="fun-list">
    	<div class="fun-row mt10">
            <a href="<?php echo url('app=member&act=profile'); ?>" class="clearfix block">
               <p class="float-left title basic-information"><i></i>基本信息</p>
               <p class="float-right view mr10"></p>  
           </a>
        </div>
         <div class="fun-row">
            <a href="<?php echo url('app=member&act=password'); ?>" class="clearfix block">
               <p class="float-left title edit-password"><i></i>修改密码</p>
               <p class="float-right view mr10"></p>  
           </a>
        </div>
        <div class="fun-row">
            <a href="<?php echo url('app=member&act=email'); ?>" class="clearfix block">
               <p class="float-left title edit-email"><i></i>修改电子邮箱</p>
               <p class="float-right view mr10"></p>  
           </a>
        </div>
        <div class="fun-row">
            <a href="<?php echo url('app=member&act=phone'); ?>" class="clearfix block">
               <p class="float-left title edit-phone"><i></i>修改手机号码</p>
               <p class="float-right view mr10"></p>  
           </a>
        </div>
        <div class="fun-row mt10">
            <a href="<?php echo url('app=member&act=logout'); ?>" class="clearfix block">
               <p class="float-left title logout"><i></i>退出当前账户</p>
               <p class="float-right view mr10"></p>  
           </a>
        </div>
    </div>
</div>


<?php echo $this->fetch('footer.html'); ?> 