<?php
// public/chefop/set_token_commander.php

use App\Web3Client;
use App\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

$config = require_once __DIR__ . '/../../config/config.php';
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokenchefop']['address'],
    $config['contracts']['MyTokenchefop']['abi'],
    $config['contracts']['MyTokenchefop']['private_key']
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tokenCommanderAdresse = $_POST['token_commander_adresse'];

    if (Utils::isValidEthereumAddress($tokenCommanderAdresse)) {
        try {
            // Appeler la fonction setTokenCommanderAddress
            $txHash = $client->sendTransaction('setTokenCommanderAddress', [$tokenCommanderAdresse]);
            $message = "Adresse de TokenCommander mise à jour avec succès ! Hash : " . htmlspecialchars($txHash);
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
    <title>Définir l'Adresse de TokenCommander</title>
</head>
<body>
    <h1>Définir l'Adresse de TokenCommander dans MyTokenchefop</h1>
    <?php if (isset($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="token_commander_adresse">Adresse de TokenCommander :</label>
        <input type="text" id="token_commander_adresse" name="token_commander_adresse" required>
        <button type="submit">Définir</button>
    </form>
    <p><a href="index.php">Retour à la Gestion de MyTokenchefop</a></p>
</body>
</html>
