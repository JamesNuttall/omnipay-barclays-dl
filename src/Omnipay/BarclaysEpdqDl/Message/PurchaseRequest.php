<?php

namespace Omnipay\BarclaysEpdqDl\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Barclays ePDQ Direct Link Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{

    // OPERATION CODE FOR SALE
    protected $operation    = 'SAL';

    protected $liveEndpoint = 'https://payments.epdq.co.uk/ncol/prod/orderdirect.asp';
    protected $testEndpoint = 'https://mdepayments.epdq.co.uk/ncol/test/orderdirect.asp';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }
    
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getUserId()
    {
        return $this->getParameter('userId');
    }

    public function setUserId($value)
    {
        return $this->setParameter('userId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getData()
    {
        $data = array();

        $data['OPERATION'] = $this->operation;
        $data['PSPID']     = $this->getClientId();
        $data['ORDERID']   = $this->getTransactionId();

        $data['USERID']    = $this->getUserId();
        $data['PSWD']      = $this->getPassword();

        $data['CURRENCY']  = $this->getCurrency();
        $data['AMOUNT']    = $this->getAmountInteger();

        $card = $this->getCard();
        if ($card) {
            $data['CARDNO'] = $card->getNumber();
            $data['ED']     = $card->getExpiryDate('my');
            $data['CVC']    = $card->getCvv();
        }

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            http_build_query($data, '', '&')
        );

        return $this->response = new PurchaseResponse($this, $response->getBody()->getContents());
    }
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
