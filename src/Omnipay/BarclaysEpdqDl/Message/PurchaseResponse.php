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
    protected $statusMessages = array(
        0  => "Incomplete or invalid",
        1  => "Cancelled by client",
        2  => "Authorisation refused",
        4  => "Order stored",
        41 => "Waiting client payment",
        5  => "Authorised",
        51 => "Authorisation waiting",
        52 => "Authorisation not known",
        59 => "Author. to get manually",
        6  => "Authorised and canceled",
        61 => "Author. deletion waiting",
        62 => "Author. deletion uncertain",
        63 => "Author. deletion refused",
        7  => "Payment deleted",
        71 => "Payment deletion pending",
        72 => "Payment deletion uncertain",
        73 => "Payment deletion refused",
        74 => "Payment deleted (not accepted)",
        75 => "Deletion processed by merchant",
        8  => "Refund",
        81 => "Refund pending",
        82 => "Refund uncertain",
        83 => "Refund refused",
        84 => "Payment declined by the acquirer (will be debited)",
        85 => "Refund processed by merchant",
        9  => "Payment requested",
        91 => "Payment processing",
        92 => "Payment uncertain",
        93 => "Payment refused",
        94 => "Refund declined by the acquirer",
        95 => "Payment processed by merchant",
        97 => "Being processed (intermediate technical status)",
        98 => "Being processed (intermediate technical status)",
        99 => "Being processed (intermediate technical status)"
    );

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
        if (isset($this->statusMessages[$this->data['STATUS']])) {
            return $this->statusMessages[$this->data['STATUS']];
        }   
        
        return null;
    }

    public function getNcError()
    {   
        return isset($this->data['NCERROR']) ? $this->data['NCERROR'] : null;
    }

    public function getNcErrorPlus()
    {   
        return isset($this->data['NCERRORPLUS']) ? $this->data['NCERRORPLUS'] : null;
    }
}
