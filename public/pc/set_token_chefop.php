<?php
// public/pc/set_token_chefop.php

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
    $tokenChefopAdresse = $_POST['token_chefop_adresse'];

    if (Utils::isValidEthereumAddress($tokenChefopAdresse)) {
        try {
            // Appeler la fonction setTokenCOAddress
            $txHash = $client->sendTransaction('setTokenCOAddress', [$tokenChefopAdresse]);
            $message = "Adresse de TokenChefop mise à jour avec succès ! Hash : " . htmlspecialchars($txHash);
        } catch (\Exception $e) {
            $erreur = $e->getMessage();
        }
    } else {
        $erreur = "Adresse Ethereum invalide.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Définir l'Adresse de TokenChefop</title>
</head>
<body>
    <h1>Définir l'Adresse de TokenChefop dans MyTokenPC</h1>
    <?php if (isset($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="token_chefop_adresse">Adresse de TokenChefop :</label>
        <input type="text" id="token_chefop_adresse" name="token_chefop_adresse" required>
        <button type="submit">Définir</button>
    </form>
    <p><a href="index.php">Retour à la Gestion de MyTokenPC</a></p>
</body>
</html>
