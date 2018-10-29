<?php

require('./bootstrap.php');

$gateway = \Omnipay\Omnipay::create('BarclaysEpdqDl');
$gateway->setClientId('ghostwhite');
$gateway->setUserId('nosleep');
$gateway->setPassword('28Ug3EFmVk@G');
$gateway->setTestMode(true);

// Example form data
$formData = [
    'number' => '5399999999999999',
    'expiryMonth' => '6',
    'expiryYear' => '2020',
    'cvv' => '123'
];

// Send purchase request
$response = $gateway->purchase(
    [
        'transactionId' => 'zxbngf2d6sw33e211278654',
        'amount' => '25.00',
        'currency' => 'GBP',
        'card' => $formData
    ]
)->send();

// Process response
if ($response->isSuccessful()) {
    
    // Payment was successful
    // print_r($response);
    print($response->getTransactionReference());

} else {
    // Payment failed
    print($response->getMessage());
}