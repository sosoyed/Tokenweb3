<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/config/config.php';
use App\Web3Client;

$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokencommander']['address'],
    $config['contracts']['MyTokencommander']['abi'],
    $config['contracts']['MyTokencommander']['private_key']
);

try {
    // Vérifier la fonction 'owner'
    $owner = $client->callFunction('owner');
    var_dump("owner() => ", $owner);

    // Vérifier la fonction 'mintcommander'
    $mintResult = $client->callFunction('mintcommander');
    var_dump("mintcommander() => ", $mintResult);

} catch (Exception $e) {
    echo "Erreur : ", $e->getMessage(), "\n";
}
