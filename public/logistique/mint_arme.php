<?php
// public/mint.php

use App\Web3Client;

require_once __DIR__ . '/../../vendor/autoload.php';

// Charger la configuration
$config = require_once __DIR__ . '/../../config/config.php';

// Liste des contrats disponibles
$contracts = [
    'Famas' => [
        'name' => 'MyTokenFamas',
        'function' => 'mintFamas',
    ],
    'Glock' => [
        'name' => 'MyTokenGlock',
        'function' => 'mintGlock',
    ],
    'Holosun' => [
        'name' => 'MyTokenHolosun',
        'function' => 'mintHolosun',
    ]
];

$message = null;
$erreur = null;

// Vérifier si une requête POST a été envoyée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token_type'])) {
    $tokenType = $_POST['token_type'];
    $amount = intval($_POST['amount']);

    if (isset($contracts[$tokenType]) && $amount>0) {
        // Récupérer les informations du contrat
        $contractData = $contracts[$tokenType];

        // Instancier le client Web3 pour ce contrat
        $client = new Web3Client(
            $config['rpc_url'],
            $config['contracts'][$contractData['name']]['address'],
            $config['contracts'][$contractData['name']]['abi'],
            $config['contracts'][$contractData['name']]['private_key']
        );

        try {
            // Exécuter la transaction de mint spécifique
            $txHash = $client->sendTransaction($contractData['function']);
            $message = "Transaction (PHP) réussie ! Hash : " . htmlspecialchars($txHash);
        } catch (\Exception $e) {
            $erreur = $e->getMessage();
        }
    } else {
        $erreur = "Type de token inconnu.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mint des Tokens</title>
</head>
<body>
    <h1>Mint des Tokens</h1>

    <!-- Affichage des messages -->
    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>
    <?php if ($erreur): ?>
        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <h2>Mint via PHP (serveur)</h2>
    <form method="POST">
        <label for="amount">Nombre de tokens :</label>
        <input type="number" name="amount" id="amount" min="1" required>
        <br><br>
        <button type="submit" name="token_type" value="Famas">Mint 1 Famas</button>
        <button type="submit" name="token_type" value="Glock">Mint 1 Glock</button>
        <button type="submit" name="token_type" value="Holosun">Mint 1 Holosun</button>
    </form>

    <hr>

    <h2>Mint via MetaMask</h2>
    <label for="amountMetaMask">Nombre de tokens :</label>
    <input type="number" id="amountMetaMask" min="1" required>
    <br><br>
    <button onclick="mintWithMetaMask('Famas')">Mint 1 Famas (MetaMask)</button>
    <button onclick="mintWithMetaMask('Glock')">Mint 1 Glock (MetaMask)</button>
    <button onclick="mintWithMetaMask('Holosun')">Mint 1 Holosun (MetaMask)</button>

    <p><a href="index.php">Retour à l'accueil</a></p>

    <!-- ----------------------------------------------------------------
         PARTIE JAVASCRIPT (Gestion de MetaMask pour les 3 contrats)
         ---------------------------------------------------------------- -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
    
    <script>
    // Définition des contrats (adresse + ABI minimum)
    const contracts = {
        Famas: {
            address: "0x992aF457B7EdB6CCf85223980B86d96f83d52945",
            function: "mintFamas",
            abi: [{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_amount",
				"type": "uint256"
			}
		],
		"name": "mintFamas",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}]
        },
        Glock: {
            address: "0x8a93FB771A5341E7dAc6334Af77eb533ab85095F",
            function: "mintGlock",
            abi: [{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_amount",
				"type": "uint256"
			}
		],
		"name": "mintGlock",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}]
        },
        Holosun: {
            address: "0xbA22b48D22c481e26F28F298f50B91c948283D9A",
            function: "mintHolosun",
            abi: [{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_amount",
				"type": "uint256"
			}
		],
		"name": "mintHolosun",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}]
        }
    };

    async function mintWithMetaMask(tokenType) {
        // Vérifier si MetaMask est présent
        if (typeof window.ethereum === 'undefined') {
            alert('MetaMask non détecté. Veuillez installer MetaMask.');
            return;
        }

        // Vérifier si le contrat existe
        if (!contracts[tokenType]) {
            alert('Contrat inconnu.');
            return;
        }

        const { address, function: functionName, abi } = contracts[tokenType];

         // Obtenir la quantité entrée par l'utilisateur
         const amountInput = document.getElementById("amountMetaMask");
         const amount = parseInt(amountInput.value);
         if (!amount || amount < 1) {
            alert("Veuillez entrer un nombre valide de tokens.");
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
        const contract = new web3.eth.Contract(abi, address);

        // Appeler la fonction de mint spécifique
        try {
            const txReceipt = await contract.methods[functionName]().send({ from: sender });
            alert(`Transaction ${tokenType} réussie ! Hash : ` + txReceipt.transactionHash);
        } catch (err) {
            alert('Erreur de transaction : ' + err.message);
        }
    }
    </script>
</body>
</html>
