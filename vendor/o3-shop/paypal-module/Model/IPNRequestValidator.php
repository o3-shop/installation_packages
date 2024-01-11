<?php
/**
 * This file is part of O3-Shop Paypal module.
 *
 * O3-Shop is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * O3-Shop is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with O3-Shop.  If not, see <http://www.gnu.org/licenses/>
 *
 * @copyright  Copyright (c) 2022 OXID eSales AG (https://www.oxid-esales.com)
 * @copyright  Copyright (c) 2022 O3-Shop (https://www.o3-shop.com)
 * @license    https://www.gnu.org/licenses/gpl-3.0  GNU General Public License 3 (GPLv3)
 */

namespace OxidEsales\PayPalModule\Model;

/**
 * PayPal IPN request validator class.
 */
class IPNRequestValidator
{
    /**
     * String PayPal receiver email. It should be same as shop owner credential for PayPal.
     *
     * @var string
     */
    const RECEIVER_EMAIL = 'receiver_email';

    /**
     * Shop owner Email from configuration of PayPal module.
     *
     * @var string
     */
    protected $shopOwnerUserName = null;

    /**
     * PayPal response if OK.
     *
     * @var \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal
     */
    protected $payPalResponse = null;

    /**
     * PayPal request to get email.
     *
     * @var array
     */
    protected $payPalRequest = null;

    /**
     * Set shop owner user name - payPal ID.
     *
     * @param string $shopOwnerUserName
     */
    public function setShopOwnerUserName($shopOwnerUserName)
    {
        $this->shopOwnerUserName = $shopOwnerUserName;
    }

    /**
     * get shop owner user name - payPal ID.
     *
     * @return string
     */
    public function getShopOwnerUserName()
    {
        return $this->shopOwnerUserName;
    }

    /**
     * Set PayPal response object.
     *
     * @param \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal|Response\Response $payPalResponse
     */
    public function setPayPalResponse($payPalResponse)
    {
        $this->payPalResponse = $payPalResponse;
    }

    /**
     * Get PayPal response object.
     *
     * @return \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal
     */
    public function getPayPalResponse()
    {
        return $this->payPalResponse;
    }

    /**
     * Set PayPal request array.
     *
     * @param array $payPalRequest
     */
    public function setPayPalRequest($payPalRequest)
    {
        $this->payPalRequest = $payPalRequest;
    }

    /**
     * Get PayPal request array.
     *
     * @return array
     */
    public function getPayPalRequest()
    {
        return $this->payPalRequest;
    }

    /**
     * Returns validation failure messages.
     *
     * @return array
     */
    public function getValidationFailureMessage()
    {
        $payPalRequest = $this->getPayPalRequest();
        $payPalResponse = $this->getPayPalResponse();
        $shopOwnerUserName = $this->getShopOwnerUserName();
        $receiverEmailPayPal = $payPalRequest[self::RECEIVER_EMAIL];

        $validationMessage = array(
            'Shop owner'           => (string) $shopOwnerUserName,
            'PayPal ID'            => (string) $receiverEmailPayPal,
            'PayPal ACK'           => ($payPalResponse->isPayPalAck() ? 'VERIFIED' : 'NOT VERIFIED'),
            'PayPal Full Request'  => print_r($payPalRequest, true),
            'PayPal Full Response' => print_r($payPalResponse->getData(), true),
        );

        return $validationMessage;
    }

    /**
     * Validate if IPN request from PayPal and to correct shop.
     *
     * @return bool
     */
    public function isValid()
    {
        $payPalRequest = $this->getPayPalRequest();
        $payPalResponse = $this->getPayPalResponse();
        $shopOwnerUserName = $this->getShopOwnerUserName();
        $receiverEmailPayPal = $payPalRequest[self::RECEIVER_EMAIL];

        return ($payPalResponse->isPayPalAck() && $receiverEmailPayPal == $shopOwnerUserName);
    }
}
