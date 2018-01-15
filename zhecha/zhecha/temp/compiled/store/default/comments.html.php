<div <?php if (! $_GET['act']): ?>style="border-top:2px solid #999999;"<?php endif; ?> class="statistics clearfix">
    <div class="rate">
        <p>
            <strong>
                <?php echo ($this->_var['statistics']['goods_rate'] == '') ? '0' : $this->_var['statistics']['goods_rate']; ?>
            </strong>
            <span>
                %
            </span>
            <i>
                好评
            </i>
        </p>
        <em>
            总共<?php echo $this->_var['statistics']['total_count']; ?>人参加评分
        </em>
    </div>
    <div class="per-evaluation">
        <dl class="clearfix">
            <dt>
                好评
                <em>
                    (<?php echo ($this->_var['statistics']['goods_rate'] == '') ? '0' : $this->_var['statistics']['goods_rate']; ?>%)
                </em>
            </dt>
            <dd>
                <b style="width:<?php echo ($this->_var['statistics']['goods_rate'] == '') ? '0' : $this->_var['statistics']['goods_rate']; ?>%;">
                </b>
            </dd>
        </dl>
        <dl class="clearfix">
            <dt>
                中评
                <em>
                    (<?php echo ($this->_var['statistics']['middle_rate'] == '') ? '0' : $this->_var['statistics']['middle_rate']; ?>%)
                </em>
            </dt>
            <dd>
                <b style="width:<?php echo ($this->_var['statistics']['middle_rate'] == '') ? '0' : $this->_var['statistics']['middle_rate']; ?>%;">
                </b>
            </dd>
        </dl>
        <dl class="clearfix">
            <dt>
                差评
                <em>
                    (<?php echo ($this->_var['statistics']['bad_rate'] == '') ? '0' : $this->_var['statistics']['bad_rate']; ?>%)
                </em>
            </dt>
            <dd>
                <b style="width:<?php echo ($this->_var['statistics']['bad_rate'] == '') ? '0' : $this->_var['statistics']['bad_rate']; ?>%;">
                </b>
            </dd>
        </dl>
    </div>
    <div class="i-want-comment">
        <span>
            您可对已购商品进行评价
        </span>
        <p class="mt5">
            <a href="<?php echo url('app=buyer_order'); ?>">
                我要评价
            </a>
        </p>
    </div>
</div>
<div class="attr-tabs">
    <ul class="user-menu">
        <li <?php if ($_GET['eval'] == ''): ?>class="active" <?php endif; ?>>
            <a style="border-left:1px solid #ddd;" href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. ''); ?>#module">
                <span>
                    全部评价（<?php echo $this->_var['statistics']['total_count']; ?>）
                </span>
            </a>
        </li>
        <li <?php if ($_GET['eval'] == 3): ?>class="active" <?php endif; ?>>
            <a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&eval=3'); ?>#module">
                <span>
                    好评（<?php echo $this->_var['statistics']['goods_count']; ?>）
                </span>
            </a>
        </li>
        <li <?php if ($_GET['eval'] == 2): ?>class="active" <?php endif; ?>>
            <a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&eval=2'); ?>#module">
                <span>
                    中评（<?php echo $this->_var['statistics']['middle_count']; ?>）
                </span>
            </a>
        </li>
        <li <?php if ($_GET['eval'] == 1): ?>class="active" <?php endif; ?>>
            <a href="<?php echo url('app=goods&act=comments&id=' . $this->_var['goods']['goods_id']. '&eval=1'); ?>#module">
                <span>
                    差评（<?php echo $this->_var['statistics']['bad_count']; ?>）
                </span>
            </a>
        </li>
    </ul>
</div>
<div class="eval-list">
    <?php $_from = $this->_var['goods_comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');$this->_foreach['fe_comment'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_comment']['total'] > 0):
    foreach ($_from AS $this->_var['comment']):
        $this->_foreach['fe_comment']['iteration']++;
?>
    <script type="text/javascript">
        $(function() {
            $('#comment_evaluation_<?php echo $this->_foreach['fe_comment']['iteration']; ?>').raty({
                readOnly: true,
                score: '<?php echo $this->_var['comment']['goods_evaluation']; ?>'
            });
        })
    </script>
    <div class="list mt10">
        <div class="user_avatar">
            <a href="<?php echo url('app=message&act=send&to_id=' . $this->_var['comment']['buyer_id']. ''); ?>" target="_blank"
            title="给他（她）发站内信？">
                <img src="<?php echo $this->_var['comment']['portrait']; ?>" />
            </a>
        </div>
        <dl class="comment-detail">
            <dt class="clearfix">
                <a href="<?php echo url('app=message&act=send&to_id=' . $this->_var['comment']['buyer_id']. ''); ?>" target="_blank"
                title="给他（她）发站内信？" class="name">
                    <?php echo $this->_var['comment']['buyer_name']; ?>
                </a>
                <i>
                    <?php echo local_date("Y-m-d H:i:s",$this->_var['comment']['evaluation_time']); ?>
                </i>
            </dt>
            <dd>
                <span>
                    用户评分：
                </span>
                <span id="comment_evaluation_<?php echo $this->_foreach['fe_comment']['iteration']; ?>">
                </span>
            </dd>
            <dd>
                <span>
                    评价详情：
                </span>
                <span class="content">
                    <?php echo nl2br(htmlspecialchars($this->_var['comment']['comment'])); ?>
                </span>
            </dd>
            <?php if ($this->_var['comment']['reply_content']): ?>
            <dd class="reply w-full clearfix">
                <span>
                    商家回复：
                </span>
                <span class="content">
                    <?php echo htmlspecialchars($this->_var['comment']['reply_content']); ?>
                </span>
            </dd>
            <?php endif; ?>
        </dl>
    </div>
    <?php endforeach; else: ?>
    <div class="no-access">
        没有符合条件的记录
    </div>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php if ($this->_var['goods_comments']): ?><?php echo $this->fetch('page.bottom.html'); ?><?php endif; ?>


