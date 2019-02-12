<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | Paytm Mode
     * |--------------------------------------------------------------------------
     * | Supported: "sandbox", "live"
     */
    'default' => env('PAYTM_SANDBOX', 'sandbox'),
    /*
    * |--------------------------------------------------------------------------
    * | Paytm Transaction, Refund, Refund Status urls
    * |--------------------------------------------------------------------------
    */
    'urls' => [
        'sandbox' => [
            'txn_url' => 'https://securegw-stage.paytm.in/theia/processTransaction',
            'txn_status_url' => 'https://securegw-stage.paytm.in/merchant-status/getTxnStatus',
            'refund_url' => 'https://securegw-stage.paytm.in/refund/HANDLER_INTERNAL/REFUND',
            'refund_status_url' => 'https://securegw-stage.paytm.in/refund/HANDLER_INTERNAL/getRefundStatus'
        ],
        'live' => [
            'txn_url' => 'https://securegw.paytm.in/theia/processTransaction',
            'txn_status_url' => 'https://securegw.paytm.in/merchant-status/getTxnStatus',
            'refund_url' => 'https://securegw.paytm.in/refund/HANDLER_INTERNAL/REFUND',
            'refund_status_url' => 'https://securegw.paytm.in/refund/HANDLER_INTERNAL/getRefundStatus'
        ],
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Paytm Additional settings
     * |--------------------------------------------------------------------------
     * |
     */
    'industry_type' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),
    'channel' => env('PAYTM_CHANNEL', 'WEBSTAGING'),
    'order_prefix' => env('PAYTM_ORDER_PREFIX', 'paytm_'),
    'website' => env('PAYTM_WEBSITE', 'WEBSITE'),

    /*
    |--------------------------------------------------------------------------
    | Paytm Credentials
    |--------------------------------------------------------------------------
    */
    'credentials' => [
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_mid' => env('PAYTM_MERCHANT_ID'),
        'callback_url' => env('CALLBACK_URL')
    ],
];
