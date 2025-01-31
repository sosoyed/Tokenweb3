<?php
// public/commander/add_address.php

use App\Web3Client;
use App\Utils;

require_once __DIR__ . '/../../vendor/autoload.php';

$config = require_once __DIR__ . '/../../config/config.php';
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokencommander']['address'],
    $config['contracts']['MyTokencommander']['abi'],
    $config['contracts']['MyTokencommander']['private_key']
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouvelleAdresse = $_POST['adresse'];

    if (Utils::isValidEthereumAddress($nouvelleAdresse)) {
        try {
            // Appeler la fonction addAdressCommander
            $txHash = $client->sendTransaction('addAdressCommander', [$nouvelleAdresse]);
            $message = "Transaction envoyée avec succès ! Hash : " . htmlspecialchars($txHash);
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
    <title>Ajouter une Adresse Autorisée</title>
</head>
<body>
    <h1>Ajouter une Adresse Autorisée à MyTokencommander</h1>
    <?php if (isset($message)): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if (isset($erreur)): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="adresse">Nouvelle Adresse Ethereum :</label>
        <input type="text" id="adresse" name="adresse" required>
        <button type="submit">Ajouter</button>
    </form>
    <p><a href="index.php">Retour à la Gestion de MyTokencommander</a></p>
</body>
</html>
