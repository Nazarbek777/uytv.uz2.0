<?php

return [
    'price_analysis' => [
        'default_currency' => env('SCRAPER_DEFAULT_CURRENCY', 'UZS'),
        'usd_to_uzs_rate' => (float) env('SCRAPER_USD_TO_UZS_RATE', 12500),
        'min_price_per_m2' => [
            'UZS' => (float) env('SCRAPER_MIN_PRICE_PER_M2_UZS', 4000000),
            'USD' => (float) env('SCRAPER_MIN_PRICE_PER_M2_USD', 320),
        ],
        'max_price_per_m2' => [
            'UZS' => (float) env('SCRAPER_MAX_PRICE_PER_M2_UZS', 40000000),
            'USD' => (float) env('SCRAPER_MAX_PRICE_PER_M2_USD', 3200),
        ],
        'min_area' => (float) env('SCRAPER_MIN_AREA', 12),
        'max_area' => (float) env('SCRAPER_MAX_AREA', 400),
        'min_price_absolute' => [
            'UZS' => (float) env('SCRAPER_MIN_PRICE_ABS_UZS', 50000000),
            'USD' => (float) env('SCRAPER_MIN_PRICE_ABS_USD', 40000),
        ],
    ],
];

