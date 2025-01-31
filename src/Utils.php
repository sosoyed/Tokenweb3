<?php
// src/Utils.php

namespace App;

class Utils
{
    /**
     * Valider une adresse Ethereum
     */
    public static function isValidEthereumAddress($address)
    {
        return preg_match('/^0x[a-fA-F0-9]{40}$/', $address);
    }
}
