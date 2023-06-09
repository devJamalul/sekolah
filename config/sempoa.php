<?php

return [
    /**
     * base URL for integration
     */
    'base_url' => env('SEMPOA_URL', 'http://erp.test/') . 'api/integration/',

    /**
     * check token
     * the value is fixed. do not change it for ever. ever.
     */
    'check_token' => 'sempoa',

    /**
     * authorized config
     */
    'config' => [
        'sempoa', // API Sempoa
    ],
];
