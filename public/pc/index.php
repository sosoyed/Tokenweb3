<?php
// public/pc/index.php

use App\Web3Client;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger la configuration
$config = require_once __DIR__ . '/../../config/config.php';

// Instancier le client Web3 pour MyTokenPC
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokenPC']['address'],
    $config['contracts']['MyTokenPC']['abi'],
    $config['contracts']['MyTokenPC']['private_key']
);

/*try {
    // Récupérer le propriétaire
    $owner = $client->callFunction('owner');

    // Récupérer la liste des adresses autorisées
    $adressesAutoriseesPC = $client->callFunction('adressesAutoriseesPC'); // Adapter selon l'ABI

    // Récupérer l'adresse du contrat TokenChefop
    $tokenCOAddress = $client->callFunction('tokenCO');
} catch (\Exception $e) {
    $erreur = $e->getMessage();
}*/
try {
    // 1) Récupérer le propriétaire
    $ownerResult = $client->callFunction('owner');
    // En général, web3p renvoie la valeur de retour dans un tableau
    // Donc $owner = $ownerResult[0] ou directement $ownerResult si c’est un string
    $owner = is_array($ownerResult) && isset($ownerResult[0]) ? $ownerResult[0] : $ownerResult;
    $tokenCOAddressResult = $client->callFunction('tokenCO');
    $tokenCOAddress = is_array($tokenCOAddressResult) && isset($tokenCOAddressResult[0]) ? $tokenCOAddressResult[0] : $tokenCOAddressResult;

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

// Initialiser les variables comme tableaux vides
$adressesAutoriseesPC = [];

try {
    // Appeler la fonction du contrat pour récupérer les adresses autorisées
    $adressesAutoriseesPC = $client->callFunction('getAdressesAutoriseesPC'); // Ajustez le nom de la fonction si nécessaire
} catch (\Exception $e) {
    $erreur = $e->getMessage();
    // Vous pouvez également enregistrer cette erreur dans un log ou l'afficher
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion de MyTokenPC</title>
</head>
<body>
    <h1>MyTokenPC</h1>
    <?php ?>
        <p><strong>Propriétaire :</strong> <?= htmlspecialchars($owner) ?></p>
        <p><strong>Adresse de TokenChefop :</strong> <?= htmlspecialchars($tokenCOAddress) ?></p>
        <h2>Adresses Autorisées</h2>
        <ul>
            <?php foreach ($adressesAutoriseesPC as $adresse): ?>
                <li><?= htmlspecialchars($adresse) ?></li>
            <?php endforeach; ?>
        </ul>
        <ul>
            <li><a href="add_address.php">Ajouter une Adresse Autorisée</a></li>
            <li><a href="mint.php">Mint des Tokens</a></li>
            <li><a href="set_token_chefop.php">Définir l'Adresse de TokenChefop</a></li>
            <li><a href="burn.php">Brûler des Tokens</a></li>
        </ul>
    <?php ?>
    <p><a href="../index.php">Retour à l'accueil</a></p>
</body>
</html>
