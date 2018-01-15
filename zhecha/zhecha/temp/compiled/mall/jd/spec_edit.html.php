<div class="eject_con">
    <div id="warning"></div>
    <div class="edit_table">
        <p><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></p>
        <form action="index.php?app=my_goods&amp;act=spec_edit&amp;id=<?php echo $this->_var['goods']['goods_id']; ?>" method="POST" target="iframe_post">
        <div class="edit_table_box">
            <table>
                <tr>
                    <?php if ($this->_var['goods']['spec_name_1']): ?><th class="width2"><?php echo htmlspecialchars($this->_var['goods']['spec_name_1']); ?></th><?php endif; ?>
                    <?php if ($this->_var['goods']['spec_name_2']): ?><th><?php echo htmlspecialchars($this->_var['goods']['spec_name_2']); ?></th><?php endif; ?>
                    <th>价格</th>
                    
                    <?php if (! $this->_var['vip_price_status_off']): ?>
                    <?php if ($this->_var['price_1']): ?>
                    <th>会员价1</th>
                    <?php endif; ?>
					<?php if ($this->_var['price_2']): ?>
                    <th>会员价2</th>
                    <?php endif; ?>
					<?php if ($this->_var['price_3']): ?>
                    <th>会员价3</th>
                    <?php endif; ?>
					<?php if ($this->_var['price_4']): ?>
                    <th>会员价4</th>
                    <?php endif; ?>
					<?php if ($this->_var['price_5']): ?>
                    <th>会员价5</th>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <th>库存</th>
                </tr>
                <?php $_from = $this->_var['goods']['gs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
                <tr>
                    <?php if ($this->_var['goods']['spec_name_1']): ?><td class="align2"><?php echo htmlspecialchars($this->_var['spec']['spec_1']); ?></td><?php endif; ?>
                    <?php if ($this->_var['goods']['spec_name_2']): ?><td class="align2"><?php echo htmlspecialchars($this->_var['spec']['spec_2']); ?></td><?php endif; ?>
                    <td class="align2"><input type="text" class="text width1" name="price[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price']; ?>" /></td>
                    
                    <?php if (! $this->_var['vip_price_status_off']): ?>
                    <?php if ($this->_var['price_1']): ?>
                    <td class="align2"><input type="text" class="text width1" name="price_1[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price_1']; ?>" /></td>
                    <?php endif; ?>
					<?php if ($this->_var['price_2']): ?>
                    <td class="align2"><input type="text" class="text width1" name="price_2[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price_2']; ?>" /></td>
                    <?php endif; ?>
					<?php if ($this->_var['price_3']): ?>
                    <td class="align2"><input type="text" class="text width1" name="price_3[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price_3']; ?>" /></td>
                    <?php endif; ?>
					<?php if ($this->_var['price_4']): ?>
                    <td class="align2"><input type="text" class="text width1" name="price_4[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price_4']; ?>" /></td>
                    <?php endif; ?>
					<?php if ($this->_var['price_5']): ?>
                    <td class="align2"><input type="text" class="text width1" name="price_5[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['price_5']; ?>" /></td>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <td class="align2"><input type="text" class="text width1" name="stock[<?php echo $this->_var['spec']['spec_id']; ?>]" value="<?php echo $this->_var['spec']['stock']; ?>" /></td>
                </tr>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </table>
        </div>
        <div class="submit"><input type="submit" class="btn" value="保存" /></div>
        </form>
    </div>
</div>