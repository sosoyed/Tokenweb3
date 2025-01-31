<?php
// public/pc/burn.php

use App\Web3Client;
use App\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

$config = require_once __DIR__ . '/../../config/config.php';
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokenPC']['address'],
    $config['contracts']['MyTokenPC']['abi'],
    $config['contracts']['MyTokenPC']['private_key']
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Appeler la fonction burnPC
        $txHash = $client->sendTransaction('burnPC', []);
        $message = "Transaction de burn envoyée ! Hash : " . htmlspecialchars($txHash);
    } catch (\Exception $e) {
        $erreur = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Brûler des Tokens</title>
</head>
<body>
    <h1>Brûler des Tokens dans MyTokenPC</h1>
    <?php if (isset($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
    <form method="POST">
        <button type="submit">Brûler 1 TPC</button>
    </form>
    <p><a href="index.php">Retour à la Gestion de MyTokenPC</a></p>
</body>
</html>
