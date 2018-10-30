<?php

namespace Omnipay\BarclaysEpdqDl\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\BarclaysEpdqDl\Gateway;

/**
 * Barclays ePDQ Direct Link Purchase Response
 */
class PurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;

        $xmlParser = xml_parser_create();
        xml_parse_into_struct($xmlParser, $data, $parsedXml);
        xml_parser_free($xmlParser);

        $this->data = $parsedXml[0]['attributes'];
    }

    public function isPending()
    {
        return $this->data['STATUS'] == RESULT_PAYMENT_WAITING;
    }

    public function isRedirect()
    {
        return false;
    }

    public function isSuccessful()
    {
        $statusCode = isset($this->data['STATUS']) ? $this->data['STATUS'] : false;
        return ($statusCode == Gateway::RESULT_PAYMENT_SUCCESS || $statusCode == Gateway::RESULT_PAYMENT_REQUESTED);
    }

    public function getTransactionReference()
    {
        return $this->data['PAYID'];
    }

    public function getMessage()
    {
        $message = '';

        if (isset($this->data['NCERROR'])) {
            $message .= 'NCERROR: '.$this->data['NCERROR'].' - ';
        }

        if (isset($this->data['NCERRORPLUS'])) {
            $message .= $this->data['NCERRORPLUS'];
        }

        return $message;
    }
}