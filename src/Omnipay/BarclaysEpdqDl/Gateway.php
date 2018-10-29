<?php

namespace Omnipay\BarclaysEpdqDl;

use Omnipay\Common\AbstractGateway;
use Omnipay\BarclaysEpdqDl\Message\PurchaseRequest;

/**
 * BarclaysEpdq Essential Gateway
 *
 * @link http://www.barclaycard.co.uk/business/epdq-cpi/technical-info
 */
class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Barclays ePDQ Direct Link';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'userId'   => '',
            'password' => '',
            'testMode' => false
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BarclaysEpdq\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        // 'amount'   => '',
        // 'currency' => 'GBP',
        // 'cardNo'   => '',
        // 'ED'       => '',
        // 'CVC'      => '', 

        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
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

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }
}
