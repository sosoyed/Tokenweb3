<?php
// public/chefop/index.php

use App\Web3Client;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger la configuration
$config = require_once __DIR__ . '/../../config/config.php';

// Instancier le client Web3 pour MyTokenchefop
$client = new Web3Client(
    $config['rpc_url'],
    $config['contracts']['MyTokenchefop']['address'],
    $config['contracts']['MyTokenchefop']['abi'],
    $config['contracts']['MyTokenchefop']['private_key']
);

/*try {
    // Récupérer le propriétaire
    $ownerResult = $client->callFunction('owner');

    // Récupérer la liste des adresses autorisées
    $adressesRaw = $client->callFunction('mintTokenchefop'); // Adapter selon l'ABI

    
    $adressesAutorisees = isset($adressesRaw[0]) ? $adressesRaw[0] : [];

} catch (\Exception $e) {
    $erreur = $e->getMessage();
}*/
try {
    // 1) Récupérer le propriétaire
    $ownerResult = $client->callFunction('owner');
    // En général, web3p renvoie la valeur de retour dans un tableau
    // Donc $owner = $ownerResult[0] ou directement $ownerResult si c’est un string
    $owner = is_array($ownerResult) && isset($ownerResult[0]) ? $ownerResult[0] : $ownerResult;
    $tokenCommanderAddressResult = $client->callFunction('tokenCommander');
    $tokenCommanderAddress = is_array($tokenCommanderAddressResult) && isset($tokenCommanderAddressResult[0]) ? $tokenCommanderAddressResult[0] : $tokenCommanderAddressResult;

    // 2) Récupérer la liste complète des adresses autorisées via 'getAdressesAutorisees()'
    $adressesRaw = $client->callFunction('getAdressesAutoriseesCO');

    // Selon la version de web3p, le retour peut être un tableau,
    // par exemple [ ["0x123...", "0xabc..."] ] ou similaire.
    // Souvent, on accède à la première clé pour avoir la liste
    // Faites un var_dump($adressesRaw) pour vérifier.

    // Suppose qu’il faille faire :
    $adressesAutoriseesCO = isset($adressesRaw[0]) ? $adressesRaw[0] : [];

} catch (\Exception $e) {
    $erreur = $e->getMessage();
}
// Initialiser les variables comme tableaux vides
/*$adressesAutoriseesCO = [];

try {
    // Appeler la fonction du contrat pour récupérer les adresses autorisées
    $adressesAutoriseesCO = $client->callFunction('getAdressesAutoriseesCO'); // Ajustez le nom de la fonction si nécessaire
} catch (\Exception $e) {
    $erreur = $e->getMessage();
    print_r($erreur);
    // Vous pouvez également enregistrer cette erreur dans un log ou l'afficher
}*/
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion de MyTokenchefop</title>
</head>
<body>
    <h1>Bienvenue chef op</h1>

        <p><strong>Propriétaire :</strong> <?= htmlspecialchars($owner) ?></p>
        <p><strong>Adresse de TokenCommander :</strong> <?= htmlspecialchars($tokenCommanderAddress) ?></p>
        <h2>Adresses Autorisées</h2>
        <?php if (!empty($adressesAutoriseesCO)): ?>
            <ul>
                <?php foreach ($adressesAutoriseesCO as $adresse): ?>
                    <li><?= htmlspecialchars($adresse); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune adresse autorisée n’a été retournée.</p>
        <?php endif; ?>

        <ul>
            <li><a href="add_address.php">Ajouter une Adresse Autorisée</a></li>
            <li><a href="mint.php">Mint des Tokens missions complétées</a></li>
            <li><a href="mint_perso.php">Mint des Tokens création personnel</a></li>            
            <li><a href="set_token_commander.php">Définir l'Adresse de TokenCommander</a></li>
            <li><a href="burn.php">Brûler des Tokens</a></li>
        </ul>
    <p><a href="../index.php">Retour à l'accueil</a></p>
</body>
</html>
