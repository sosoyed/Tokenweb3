<?php
// config/config.php

return [
    'rpc_url' => 'HTTP://127.0.0.1:7545',
    
    // Adresses des contrats déployés sur Sepolia
    'contracts' => [
        'MyTokencommander' => [
            'address' => '0x6417362D8B8062e37B4bc0bE74bE5008dF01A31D',
            'abi' => __DIR__ . '/../abi/MyTokencommanderABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenchefop' => [
            'address' => '0xB547aDb64c1E3e03e8204A952d7Ea3Ac5ADE392f',
            'abi' => __DIR__ . '/../abi/MyTokenchefopABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenPC' => [
            'address' => '0x1eF39aa30757D9c74C7c2ae363cf5EA805F8F6c3',
            'abi' => __DIR__ . '/../abi/MyTokenPCABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
    ],
];
