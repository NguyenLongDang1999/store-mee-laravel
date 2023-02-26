<?php

return [
    'status' => [
        'active' => 1,
        'inactive' => 0,
    ],
    'popular' => [
        'active' => 1,
        'inactive' => 0,
    ],
    'message' => [
        'success' => 'success',
        'error' => 'error',
    ],
    'route' => [
        'dashboard' => 'dashboard',
        'category' => 'category',
        'brand' => 'brand',
        'slider' => 'slider',
        'attribute' => 'attribute',
        'variation' => 'variation'
    ],
    'bunny' => [
        'cdn_upload' => env('API_BUNNY_UPLOAD_CDN'),
        'access_key' => env('API_BUNNY_ACCESS_KEY'),
    ],
];
