<?php

return array(
    'code'      => 'heepay',
    'name'      => Lang::get('heepay'),
    'desc'      => Lang::get('heepay_desc'),
    'is_online' => '1',
    'author'    => 'PwordC',
    'website'   => '',
    'version'   => '1.0',
    'currency'  => Lang::get('heepay_currency'),
    'config'    => array(
        'merchantId'   => array(        //账号
            'text'  => Lang::get('heepay_account'),
            'desc'  => Lang::get('heepay_account_desc'),
            'type'  => 'text',
        ),
        'datakey'       => array(        //密钥
            'text'  => Lang::get('heepay_key'),
            'desc'  => Lang::get('heepay_key_desc'),
            'type'  => 'text',
        ),
    ),
);

?>