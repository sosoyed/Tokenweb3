<?php

namespace App;

use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\Utils;
use Web3p\EthereumTx\Transaction as EthereumTransaction;

// kornrunner pour dériver la clé publique
use kornrunner\Secp256k1;

class Web3Client
{
    private $web3;
    private $contract;
    private $privateKey; // Sans "0x"
    private $account;    // Adresse (ex: "0x1234...")

    /**
     * Constructeur
     *
     * @param string $rpcUrl          Adresse du node (ex: "http://127.0.0.1:7545" pour Ganache)
     * @param string $contractAddress Adresse Ethereum du contrat (ex: "0xAbCd...")
     * @param string $contractAbiPath Chemin vers le fichier .abi (JSON)
     * @param string $privateKey      Clé privée (avec ou sans "0x")
     */
    public function __construct($rpcUrl, $contractAddress, $contractAbiPath, $privateKey)
    {
        // 1) Initialiser le provider HTTP
        $provider = new HttpProvider($rpcUrl);
        $this->web3 = new Web3($provider);

        // 2) Charger l'ABI du contrat
        $abiJson = file_get_contents($contractAbiPath);

        // 3) Initialiser l'objet Contrat
        $this->contract = new Contract($provider, $abiJson);
        $this->contract->at($contractAddress);

        // 4) Stocker la clé privée et dériver l'adresse
        $this->privateKey = $this->sanitizePrivateKey($privateKey);
        /*$this->account    = $this->privateKeyToAddress($this->privateKey);*/
    }

    // ----------------------------------------------------------------
    //                          FONCTIONS PUBLIQUES
    // ----------------------------------------------------------------

    /**
     * Appeler une fonction de lecture (view/pure) du contrat
     *
     * @param string $functionName
     * @param array  $params
     * @return mixed
     * @throws \Exception
     */
    public function callFunction($functionName, $params = [])
    {
        $data = null;

        // web3p => call($function, $params, $callback)
        $this->contract->call($functionName, $params, function ($err, $result) use (&$data) {
            if ($err !== null) {
                throw new \Exception($err->getMessage());
            }
            $data = $result;
        });

        return $data;
    }

    /**
     * Appeler une fonction d'écriture (transaction) => signer et envoyer
     * @param string $functionName
     * @param array  $params
     * @return string Hash de la transaction
     * @throws \Exception
     */
    public function sendTransaction($functionName, $params = [])
    {
        // 1) Générer la data de la fonction
        $functionData = $this->contract->getData($functionName, $params);

        // 2) Récupérer le nonce de l'account
        $nonce = $this->getNonce($this->account);

        // 3) Définir le gasPrice et gasLimit
        //    Ajustez selon votre réseau. Ex: Ganache => chainId 1337 ou 5777
        $gasPriceHex = '0x' . dechex(20_000_000_000); // 20 Gwei
        $gasLimitHex = '0x' . dechex(300000);

        // 4) Construire la transaction
        $tx = [
            'nonce'    => $nonce,
            'to'       => $this->contract->getToAddress(),
            'gas'      => $gasLimitHex,
            'gasPrice' => $gasPriceHex,
            'value'    => '0x0',
            'data'     => $functionData,
            'chainId'  => 1337 // ou 5777 selon votre config Ganache
        ];

        // 5) Signer la transaction
        $signedTx = $this->signTransaction($tx, $this->privateKey);

        // 6) Envoyer la transaction signée
        return $this->sendRawTransaction($signedTx);
    }

    /**
     * Récupérer l'adresse (ex: "0x123...") dérivée de la clé privée
     */
    public function getAccount()
    {
        return $this->account;
    }

    // ----------------------------------------------------------------
    //                   FONCTIONS INTERNES/PRIVÉES
    // ----------------------------------------------------------------

    /**
     * Nettoyer la clé privée : enlever le "0x" s'il est présent, passer en minuscules
     */
    private function sanitizePrivateKey($privateKey)
    {
        $key = strtolower($privateKey);
        if (strpos($key, '0x') === 0) {
            $key = substr($key, 2);
        }
        return $key;
    }

    /**
     * Convertir la clé privée en adresse publique type "0x..."
     * en utilisant la librairie kornrunner/secp256k1
     */
    /*private function privateKeyToAddress($privateKeySans0x)
    {
        // 1) Convertir la clé privée hex => binaire
        $privKeyBin = hex2bin($privateKeySans0x);

        // 2) Obtenir la clé publique en mode "uncompressed"
        //    La lib renvoie un binaire commençant par 0x04
        /*$publicKeyBin = Secp256k1::privateKeyToPublicKey($privKeyBin, false);

        // Convertir en hex
        $publicKeyHex = bin2hex($publicKeyBin);

        // Souvent, la clé publique uncompressed commence par "04"
        // => on retire les 2 premiers caractères ("04")
        if (substr($publicKeyHex, 0, 2) === '04') {
            $publicKeyHex = substr($publicKeyHex, 2);
        }

        // 3) On applique keccak256 (via web3p) sur la clé publique (sans le préfixe 04)
        //    hex2bin => on convertit la chaine hex en binaire brut
        $hash = Utils::sha3(hex2bin($publicKeyHex));

        // 4) L'adresse Ethereum est les 20 derniers octets => 40 derniers hex
        //    => on coupe les 24 1ers caractères pour en garder 40.
        $addressHex = substr($hash, 26);

        // On préfixe "0x"
        return "0x" . $addressHex;
    }*/

    /**
     * Récupérer le nonce pour un compte donné
     *
     * @param string $address
     * @return string ex: "0x1"
     * @throws \Exception
     */
    private function getNonce($address)
    {
        $nonceHex = null;
        $this->web3->eth->getTransactionCount($address, 'latest', function ($err, $result) use (&$nonceHex) {
            if ($err !== null) {
                throw new \Exception($err->getMessage());
            }
            $nonceHex = $result; // ex: "0x1"
        });
        return $nonceHex;
    }

    /**
     * Signer la transaction avec web3p/ethereum-tx
     */
    private function signTransaction(array $transaction, $privateKeySans0x)
    {
        $tx = new EthereumTransaction($transaction);

        // Signer
        $signed = $tx->sign($privateKeySans0x);

        // Retourner la tx signée avec le préfixe "0x"
        return '0x' . $signed;
    }

    /**
     * Envoyer la transaction brute signée
     */
    private function sendRawTransaction($signedTx)
    {
        $txHash = null;

        $this->web3->eth->sendRawTransaction($signedTx, function ($err, $result) use (&$txHash) {
            if ($err !== null) {
                throw new \Exception($err->getMessage());
            }
            $txHash = $result;
        });

        return $txHash;
    }
}
