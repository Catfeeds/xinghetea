<?php echo $this->fetch('header.html'); ?>
<div id="main" class="w-full">
<div id="page-order" class="w-full">
   <div class="order-form">
      <form method="post" id="order_form">
	     <?php echo $this->fetch('order.shipping.html'); ?>
         <?php echo $this->fetch('order.goods.html'); ?>
	     <?php echo $this->fetch('order.amount.html'); ?>
      </form>
   </div>
</div>
</div>
<iframe id='iframe_post' name="iframe_post" frameborder="0" width="0" height="0"></iframe>
<?php echo $this->fetch('footer.html'); ?>