# Omnipay: Barclays ePDQ DirectLink

**Barclays ePDQ DirectLink driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/JamesNuttall/omnipay-barclays-dl.svg?branch=master)](https://travis-ci.org/JamesNuttall/omnipay-barclays-dl)
[![Latest Stable Version](https://poser.pugx.org/jamesnuttall/omnipay-barclays-dl/version)](https://packagist.org/packages/jamesnuttall/omnipay-barclays-dl)
[![Total Downloads](https://poser.pugx.org/jamesnuttall/omnipay-barclays-dl/downloads)](https://packagist.org/packages/jamesnuttall/omnipay-barclays-dl)
[![License](https://poser.pugx.org/jamesnuttall/omnipay-barclays-dl/license)](https://packagist.org/packages/jamesnuttall/omnipay-barclays-dl)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.

This package implements [Barclays ePDQ Direct Link](https://support.epdq.co.uk/en/guides/integration%20guides/directlink)
support, and supports PHP 7.1+.

## Installation

Using composer, the master branch can be installed like this:

    composer require league/omnipay jamesnuttall/omnipay-barclays-dl

## Basic Usage

The following gateways are provided by this package:

* Barclays ePDQ DirectLink

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### Basic purchase example

```php
    // Gateway initialization
    $gateway = \Omnipay\Omnipay::create('BarclaysEpdqDl');
    $gateway->setClientId('xxxxxx');
    $gateway->setUserId('xxxx');
    $gateway->setPassword('xxxxxxx');
    $gateway->setShaIn('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
    $gateway->setTestMode(true);

    // Card data
    $card = [
        'number' => 'xxxxxxxxxxxxxxxx',
        'expiryMonth' => 'x',
        'expiryYear' => 'xxxx',
        'cvv' => 'xxx'
    ];

    // Try to send purchase request
    try {
        $response = $gateway->purchase(
            [
                'transactionId' => 'xxxxxxxxxx',
                'amount' => '25.00',
                'currency' => 'GBP',
                'card' => $card
            ]
        )->send();

        if ($response->isSuccessful()) {
            // Payment successful
            print($response->getTransactionReference());

        } else {
            // Payment failed
            print($response->getMessage());
        }
    } catch (\Exception $e) {
        // Internal error, log exception and display a generic message to the customer
        exit("Error processing your payment. Please try again later.");
    }
```

## Craft Commerce

If you want to use this payment gateway with [Craft Commerce 2](https://github.com/craftcms/commerce), please see the Craft Commerce [Barclays ePDQ DirectLink gateway wrapper]().