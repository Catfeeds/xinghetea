<div class="order-confirm-extra clearfix" style="margin:20px 0 65px 0;">
		<div class="extra-list use-integral clearfix">
			<div class="title float-left">使用积分：</div>
			<div class="content float-left clearfix">
				<label class="mr10">
                	<input type="checkbox" class="J_UseIntegralCheckbox" <?php if (! $this->_var['goods_info']['allow_integral']): ?> disabled="disabled"<?php endif; ?> style="vertical-align:middle" />使用积分
                </label>
				<input type="text" name="exchange_integral" class="integral-input J_IntegralAmount" disabled="disabled" /> 点
			</div>
			<div class="fee float-right f66">-<em class="J_IntegralPrice">0.00</em></div>
		</div>
	</div>
</div>