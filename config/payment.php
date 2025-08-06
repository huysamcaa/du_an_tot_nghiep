<?php
// config/payment.php

return [
    'momo' => [
        'sandbox' => [
            'endpoint' => 'https://test-payment.momo.vn/v2/gateway/api/create',
            'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
            'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
            'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
            'request_type' => 'payWithATM',
        ],
        'production' => [
            'endpoint' => 'https://payment.momo.vn/v2/gateway/api/create',
            'partner_code' => env('MOMO_PARTNER_CODE_PROD'),
            'access_key' => env('MOMO_ACCESS_KEY_PROD'),
            'secret_key' => env('MOMO_SECRET_KEY_PROD'),
            'request_type' => 'payWithATM',
        ]
    ],

    'vnpay' => [
        'sandbox' => [
            'url' => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
            'tmn_code' => env('VNPAY_TMN_CODE', 'PBDJFA7H'),
            'hash_secret' => env('VNPAY_HASH_SECRET', 'ANBVL0AXYOROIENQ5A945WKXIATVQ3KL'),
            'return_url' => env('VNPAY_RETURN_URL', 'http://localhost:8000/checkout/vnpay/return'),
        ],
        'production' => [
            'url' => 'https://vnpayment.vn/paymentv2/vpcpay.html',
            'tmn_code' => env('VNPAY_TMN_CODE_PROD'),
            'hash_secret' => env('VNPAY_HASH_SECRET_PROD'),
            'return_url' => env('VNPAY_RETURN_URL_PROD'),
        ]
    ],

    // Timeout settings
    'timeout' => [
        'api_call' => 30, // seconds
        'session_expire' => 3600, // 1 hour
    ],

    // Retry settings
    'retry' => [
        'max_attempts' => 3,
        'delay_ms' => 1000,
    ],

    // Security settings
    'security' => [
        'verify_ip' => env('PAYMENT_VERIFY_IP', false),
        'allowed_ips' => [
            'momo' => [
                '118.107.79.0/24',
                '203.162.71.0/24',
            ],
            'vnpay' => [
                '113.161.69.0/24',
                '123.30.235.0/24',
            ]
        ]
    ]
];