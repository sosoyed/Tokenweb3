<?php
// test_autoload.php

require_once __DIR__ . '/vendor/autoload.php';

use App\Web3Client;

try {
    $client = new Web3Client(
        'https://sepolia.infura.io/v3/6c3391e148bf4a2e9e1f568d48171c61',
        '0x49a432416962A66735AFC161D8952c55B46a5FBD',
        __DIR__ . '/abi/MyTokencommanderABI.json',
        'e1b48b3b9c13ce024fd3dd33de7bc9197929824764f270ecd2e1e5311a19eccf'
    );
    echo "Web3Client instance created successfully.\n";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
