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
            'address' => '0xa951A4Aa1246122eaD2Dcd6E28F3134BA31F12BE',
            'abi' => __DIR__ . '/../abi/MyTokenPCABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokencreationpersonnel' => [
            'address' => '0x02e1EE0e445a720e121D6A2b81ceD62cfc6aA7c9',
            'abi' => __DIR__ . '/../abi/MyTokencreationpersonnelABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenlogistique' => [
            'address' => '0xb77C42079221aF02Eb006da5B0AdA4Bb7c02312e',
            'abi' => __DIR__ . '/../abi/MyTokenlogistiqueABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenFamas' => [
            'address' => '0x992aF457B7EdB6CCf85223980B86d96f83d52945',
            'abi' => __DIR__ . '/../abi/MyTokenFamasABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenGlock' => [
            'address' => '0x8a93FB771A5341E7dAc6334Af77eb533ab85095F',
            'abi' => __DIR__ . '/../abi/MyTokenGlockABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        'MyTokenHolosun' => [
            'address' => '0xbA22b48D22c481e26F28F298f50B91c948283D9A',
            'abi' => __DIR__ . '/../abi/MyTokenHolosunABI.json',
            'private_key' => '0x265c4512449ab385b251b021b5b57ec0982d85781dd9dedada2dcaf11ff3b68c',
        ],
        
    ],
];