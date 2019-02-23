<?php

namespace Omnipay\BarclaysEpdqDl;

use Guzzle\Http\Client as HttpClient;
use Omnipay\Common\AbstractGateway as OmnipayAbstractGateway;

abstract class AbstractGateway extends OmnipayAbstractGateway
{
    /**
     * Get the global default HTTP client.
     *
     * @return HttpClient
     */
    protected function getDefaultHttpClient()
    {
        return new HttpClient(
            '',
            array(
                'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
                'ssl.certificate_authority' => __DIR__ . '/Resources/cacert.pem'
            )
        );
    }
}
