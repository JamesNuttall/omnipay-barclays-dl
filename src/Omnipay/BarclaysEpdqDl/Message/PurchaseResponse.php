<?php

namespace Omnipay\BarclaysEpdqDl\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

const RESULT_PAYMENT_SUCCESS   = 5;
const RESULT_PAYMENT_REQUESTED = 9;
const RESULT_PAYMENT_INVALID   = 0;
const RESULT_PAYMENT_REFUSED   = 2;
const RESULT_PAYMENT_WAITING   = 51;

/**
 * BarclaysEpdq Purchase Response
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
        return isset($this->data['STATUS']) && ($this->data['STATUS'] == RESULT_PAYMENT_SUCCESS || $this->data['STATUS'] == RESULT_PAYMENT_REQUESTED);
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