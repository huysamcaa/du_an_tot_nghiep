<?php

return [
    'momo' => [
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
        'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
        'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
        'request_type' => env('MOMO_REQUEST_TYPE', 'payWithATM'),
        'store_id' => env('MOMO_STORE_ID', 'store001'),
        'redirect_url' => env('MOMO_REDIRECT_URL', '/checkout/momo/return'),
        'ipn_url' => env('MOMO_IPN_URL', '/checkout/momo/ipn'),
    ],
    
    'vnpay' => [
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'tmn_code' => env('VNPAY_TMN_CODE', 'PBDJFA7H'),
        'hash_secret' => env('VNPAY_HASH_SECRET', 'ANBVL0AXYOROIENQ5A945WKXIATVQ3KL'),
        'return_url' => env('VNPAY_RETURN_URL', '/checkout/vnpay/return'),
        'ipn_url' => env('VNPAY_IPN_URL', '/checkout/vnpay/ipn'),
    ],
];