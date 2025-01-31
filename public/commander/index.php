<?php
// public/commander/index.php

use App\Web3Client;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger la configuration
$config = require_once __DIR__ . '/../../config/config.php';

// Instancier le client Web3 pour MyTokencommander
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokencommander']['address'],
    $config['contracts']['MyTokencommander']['abi'],
    $config['contracts']['MyTokencommander']['private_key']
);

try {
    // 1) Récupérer le propriétaire
    $ownerResult = $client->callFunction('owner');
    // En général, web3p renvoie la valeur de retour dans un tableau
    // Donc $owner = $ownerResult[0] ou directement $ownerResult si c’est un string
    $owner = is_array($ownerResult) && isset($ownerResult[0]) ? $ownerResult[0] : $ownerResult;

    // 2) Récupérer la liste complète des adresses autorisées via 'getAdressesAutorisees()'
    $adressesRaw = $client->callFunction('getAdressesAutorisees');

    // Selon la version de web3p, le retour peut être un tableau,
    // par exemple [ ["0x123...", "0xabc..."] ] ou similaire.
    // Souvent, on accède à la première clé pour avoir la liste
    // Faites un var_dump($adressesRaw) pour vérifier.

    // Suppose qu’il faille faire :
    $adressesAutorisees = isset($adressesRaw[0]) ? $adressesRaw[0] : [];

} catch (\Exception $e) {
    $erreur = $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion de MyTokencommander</title>
</head>
<body>
    <h1>MyTokencommander</h1>
    <?php if (isset($erreur)): ?>
        <p style="color:red;">Erreur : <?= htmlspecialchars($erreur) ?></p>
    <?php else: ?>
        <p><strong>Propriétaire :</strong> <?= htmlspecialchars($owner) ?></p>

        <h2>Adresses Autorisées</h2>
        <?php if (!empty($adressesAutorisees)): ?>
            <ul>
                <?php foreach ($adressesAutorisees as $adresse): ?>
                    <li><?= htmlspecialchars($adresse) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune adresse autorisée n’a été retournée.</p>
        <?php endif; ?>

        <ul>
            <li><a href="add_address.php">Ajouter une Adresse Autorisée</a></li>
            <li><a href="mint.php">Mint des Tokens</a></li>
            <li><a href="burn.php">Brûler des Tokens</a></li>
        </ul>
    <?php endif; ?>

    <p><a href="../index.php">Retour à l'accueil</a></p>
</body>
</html>
