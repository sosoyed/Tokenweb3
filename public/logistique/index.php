<?php
// public/logistique/index.php
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
    $adressesAutoriseesL = $client->callFunction('adressesAutoriseesL'); // Adapter selon l'ABI
    // Récupérer l'adresse du contrat TokenChefop
    $tokencommandant = $client->callFunction('tokenCommandant');
} catch (\Exception $e) {
    $erreur = $e->getMessage();
}*/
try {
    // 1) Récupérer le propriétaire
    $ownerResult = $client->callFunction('owner');
    // En général, web3p renvoie la valeur de retour dans un tableau
    // Donc $owner = $ownerResult[0] ou directement $ownerResult si c’est un string
    $owner = is_array($ownerResult) && isset($ownerResult[0]) ? $ownerResult[0] : $ownerResult;
    $tokencommandantResult = $client->callFunction('tokenCO');
    $tokencommandant = is_array($tokencommandantResult) && isset($tokencommandantResult[0]) ? $tokencommandantResult[0] : $tokencommandantResult;
    // 2) Récupérer la liste complète des adresses autorisées via 'getAdressesAutoriseesL()'
    $adressesRaw = $client->callFunction('getAdressesAutoriseesL');
    // Selon la version de web3p, le retour peut être un tableau,
    // par exemple [ ["0x123...", "0xabc..."] ] ou similaire.
    // Souvent, on accède à la première clé pour avoir la liste
    // Faites un var_dump($adressesRaw) pour vérifier.
    // Suppose qu’il faille faire :
    $adressesAutoriseesL = isset($adressesRaw[0]) ? $adressesRaw[0] : [];
} catch (\Exception $e) {
    $erreur = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bienvenue à la logistique</title>
</head>
<body>
<h1>Bienvenue à la logistique</h1>
<?php ?>
<p><strong>Propriétaire :</strong> <?= htmlspecialchars($owner) ?></p>
<p><strong>Adresse de commander :</strong> <?= htmlspecialchars($tokencommandant) ?></p>
<h2>Adresses Autorisées</h2>
<ul>
<?php foreach ($adressesAutoriseesL as $adresse): ?>
<li><?= htmlspecialchars($adresse) ?></li>
<?php endforeach; ?>
</ul>
<ul>
<li><a href="swap_arme.php">Swap des tokens armes</a></li>
<li><a href="mint_L.php">Mint des Tokens logistique</a></li>
<li><a href="mint_arme.php">Mint des Tokens armes</a></li>
<li><a href="burn_tl.php">Brûler des Tokens</a></li>
</ul>
<?php ?>
<p><a href="../index.php">Retour à l'accueil</a></p>
</body>
</html>