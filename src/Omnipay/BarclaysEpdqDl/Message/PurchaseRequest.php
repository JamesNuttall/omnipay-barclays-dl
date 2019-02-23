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

    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    public function setShaIn($value)
    {
        return $this->setParameter('shaIn', $value);
    }

    public function getShaOut()
    {
        return $this->getParameter('shaOut');
    }

    public function setShaOut($value)
    {
        return $this->setParameter('shaOut', $value);
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
            // Required card details for transaction
            $data['CARDNO'] = $card->getNumber();
            $data['ED']     = $card->getExpiryDate('my');
            $data['CVC']    = $card->getCvv();

            // Optional card details
            $data['CN']            = $card->getName();
            $data['COM']           = $card->getCompany();
            $data['EMAIL']         = $card->getEmail();
            $data['OWNERZIP']      = $card->getPostcode();
            $data['OWNERTOWN']     = $card->getCity();
            $data['OWNERCTY']      = $card->getCountry();
            $data['OWNERTELNO']    = $card->getPhone();
            $data['OWNERADDRESS']  = $card->getAddress1();
            $data['OWNERADDRESS2'] = $card->getAddress2();
        }

        // Make sure all parameter keys are uppercase and no null values are passed.
        $data = $this->cleanParameters($data);

        // If shaIn was provided generate a SHASIGN
        if ($this->getShaIn()) {
            $data['SHASIGN'] = $this->calculateSha($data, $this->getShaIn());
        }

        return $data;
    }

    protected function cleanParameters($data)
    {
        $clean = array();
        foreach ($data as $key => $value) {
            if (!is_null($value) && $value !== false && $value !== '') {
                $clean[strtoupper($key)] = $value;
            }
        }
        return $clean;
    }

    public function calculateSha($data, $shaKey)
    {
        ksort($data);

        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= sprintf('%s=%s%s', strtoupper($key), $value, $shaKey);
        }

        return strtoupper(sha1($shaString));
    }

    public function sendData($data)
    {
        $httpRequest = $this->httpClient->createRequest(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            http_build_query($data, '', '&')
        );
        
        $response = $httpRequest->send();

        return $this->response = new PurchaseResponse($this, (string) $response->getBody());
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
