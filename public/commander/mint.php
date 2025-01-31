<?php
// public/commander/mint.php

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['server_mint'])) {
    try {
        // Appeler la fonction mintcommander
        $txHash = $client->sendTransaction('mintTokencommander');
        $message = "Transaction de mint envoyée ! Hash : " . htmlspecialchars($txHash);
    } catch (\Exception $e) {
        $erreur = $e->getMessage();
    }
}


//Appel metamask

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mint des Tokens MyTokencommander</title>
</head>
<body>
    <h1>Mint des Tokens dans MyTokencommander</h1>

    <!-- Zone d'affichage de message -->
    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <!-- Formulaire de mint côté serveur (PHP) -->
    <form method="POST">
        <button type="submit" name="server_mint">Mint 1 TC (via PHP)</button>
    </form>

    <hr>

    <!-- Bouton pour déclencher la fonction JavaScript (signature via MetaMask) -->
    <button type="button" onclick="mintWithMetaMask()">Mint 1 TC (via MetaMask)</button>

    <p><a href="index.php">Retour à la Gestion de MyTokencommander</a></p>

    <!-- ----------------------------------------------------------------
         PARTIE JAVASCRIPT (à la fin du body)
         ---------------------------------------------------------------- -->
    <!-- 1) On inclut la bibliothèque web3.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
    
    <!-- 2) Notre script qui appelle la fonction mintcommander() via MetaMask -->
    <script>
    // L'ABI : définition (minimum) de la fonction mintcommander()
    const contractABI = [
        {
		"inputs": [],
		"name": "mintTokencommander",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}
    ];

    // L'adresse de déploiement du contrat MyTokencommander (à adapter !)
    const contractAddress = "0x6417362D8B8062e37B4bc0bE74bE5008dF01A31D";

    // Fonction déclenchée au clic sur le bouton "Mint 1 TC (via MetaMask)"
    async function mintWithMetaMask() {
        // Vérifier la présence de MetaMask
        if (typeof window.ethereum === 'undefined') {
            alert('MetaMask non détecté. Veuillez installer MetaMask.');
            return;
        }

        // Créer un objet Web3 lié à la fenêtre Ethereum
        const web3 = new Web3(window.ethereum);

        // Demander l'autorisation de récupérer les comptes
        try {
            await window.ethereum.request({ method: 'eth_requestAccounts' });
        } catch (error) {
            alert('Demande d\'accès MetaMask refusée.');
            return;
        }

        // Obtenir le compte sélectionné dans MetaMask
        const accounts = await web3.eth.getAccounts();
        const sender = accounts[0];
        if (!sender) {
            alert("Impossible de récupérer l'adresse depuis MetaMask.");
            return;
        }

        // Instancier le contrat
        const contract = new web3.eth.Contract(contractABI, contractAddress);

        // Appeler mintcommander() côté client, signature par MetaMask
        try {
            const txReceipt = await contract.methods.mintTokencommander().send({ from: sender });
            alert('Transaction réussie ! Hash : ' + txReceipt.transactionHash);
        } catch (err) {
            alert('Erreur de transaction : ' + err.message);
        }
    }
    </script>
</body>
</html>

