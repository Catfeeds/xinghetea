<?php echo $this->fetch('header.html'); ?>
<script type="text/javascript">
$(function(){
	$('.attr-tabs .user-menu li').click(function(){
		var entype=$(this).attr('entype');
		$(this).addClass('active').siblings().removeClass('active');
		$('.tab-c').hide();
		$('.'+entype).show();
	})
});
</script>
<div id="page-goods-index">
    <?php echo $this->fetch('curlocal.html'); ?> <?php echo $this->fetch('groupbuyinfo.html'); ?>
    <div class="w-shop clearfix">
        <div class="col-sub w210">
            <?php echo $this->fetch('left.html'); ?>
        </div>
        <div style="overflow:\hidden;" class="col-main float-right w980">
            <div class="attr-tabs">
                <ul class="user-menu">
                    <li class="active" entype="description">
                        <a style="border-left:1px solid #ddd;" href="javascript:;">
                            <span>
                                商品详情
                            </span>
                        </a>
                    </li>
                    <li entype="group-qa">
                        <a href="javascript:;">
                            <span>
                                团购咨询
                            </span>
                        </a>
                    </li>
                    <li entype="logs">
                        <a href="javascript:;">
                            <span>
                                参团记录
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="option_box tab-c description">
                <div class="default">
                    <?php echo html_filter($this->_var['goods']['description']); ?>
                </div>
            </div>
            <div class="group-qa tab-c hidden">
            	<?php echo $this->fetch('qa.html'); ?>
            </div>
            <div class="logs tab-c mb10 pt10 hidden">
                <div class="list">
                    <table>
                        <?php if ($this->_var['join_list']): ?>
                        <tr>
                            <th>
                                用户名
                            </th>
                            <th>
                                商品规格
                            </th>
                            <th>
                                购买数量
                            </th>
                            <th>
                                参团时间
                            </th>
                        </tr>
                        <?php $_from = $this->_var['join_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'user');if (count($_from)):
    foreach ($_from AS $this->_var['user']):
?>
                        <tr>
                            <td>
                                <?php echo $this->_var['user']['user_name']; ?>
                            </td>
                            <td>
                                <?php $_from = $this->_var['user']['spec_quantity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');$this->_foreach['fe_spec'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_spec']['total'] > 0):
    foreach ($_from AS $this->_var['spec']):
        $this->_foreach['fe_spec']['iteration']++;
?>
                                <?php echo $this->_var['spec']['spec']; ?>
                                <br />
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </td>
                            <td>
                                <?php echo $this->_var['user']['quantity']; ?>
                            </td>
                            <td>
                                <?php echo local_date("Y-m-d H:i:s",$this->_var['user']['add_time']); ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4">
                                暂无参团记录
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('footer.html'); ?>
