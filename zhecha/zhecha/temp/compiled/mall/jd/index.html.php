<?php echo $this->fetch('header.html'); ?>
<div id="main" class="w-full">
  <div id="page-home">
    <div class="col-1 clearfix">
      <div class="col-1-r-l" area="col-1-r-l" widget_type="area">
        <?php $this->display_widgets(array('page'=>'index','area'=>'col-1-r-l')); ?>
    </div>
    </div>
    <div class="col-2 w clearfix">
     <div class="col-2-r-c float-left" area="col-1-r-c" widget_type="area" >
        	<?php $this->display_widgets(array('page'=>'index','area'=>'col-1-r-c')); ?>
        </div>
        <div class="col-2-r-r float-right" area="col-1-r-r" widget_type="area" >
          <?php $this->display_widgets(array('page'=>'index','area'=>'col-1-r-r')); ?>
        </div>
    </div>
    <div class="col-3 w" area="col-3" widget_type="area">
      <?php $this->display_widgets(array('page'=>'index','area'=>'col-3')); ?>
    </div>

      <div class="j1">
        <h1>阶梯一</h1>
        <div class="j1_center j11">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>
        <div class="j1_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>
        <div class="j1_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>
      </div>

    <div class="j2">
      <h1>阶梯二</h1>
      <div class="j2_center j22">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
      <div class="j2_center">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
    </div>

    <div class="j3">
      <h1>阶梯三</h1>
      <div class="j3_center">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
    </div>

    <div class="j4">
      <h1>阶梯四</h1>
      <div class="j4_center">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
    </div>

    <div class="j5">
      <h1>阶梯五</h1>
      <div class="j5_center1">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
      <div class="j5_center2">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
    </div>

    <div class="j6">
      <h1>阶梯六</h1>
      <div class="j6_center1">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
      <div class="j6_center2">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
    </div>

    <div class="j7">
      <h1>阶梯七</h1>
      <div class="j7_left">

        <div class="j7_left_center j77">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j7_left_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j7_left_center j77">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j7_left_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

      </div>

      <div class="j7_right">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>

    </div>

    <div class="j8">
      <h1>阶梯八</h1>
      <div class="j8_left">
        <img src="../../../data/files/mall/template/201709261555394284.jpg">
      </div>
      <div class="j8_right">
        <div class="j8_right_center j88">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j8_right_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j8_right_center j88">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>

        <div class="j8_right_center">
          <img src="../../../data/files/mall/template/201709261555394284.jpg">
        </div>
      </div>
    </div>



  </div>
  <?php if ($this->_var['index']): ?>
  <div class="J_FloorNav floor-nav"></div>
  <?php endif; ?>
</div>
<?php echo $this->fetch('footer.html'); ?>