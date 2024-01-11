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

namespace OxidEsales\PayPalModule\Model\PayPalRequest;

/**
 * PayPal request builder class for get express checkout details
 */
class GetExpressCheckoutDetailsRequestBuilder
{
    /**
     * PayPal Request
     *
     * @var \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest
     */
    protected $payPalRequest = null;

    /**
     * Session object
     *
     * @var \OxidEsales\Eshop\Core\Session
     *
     * @deprecated Session property is never used in this class
     */
    protected $session = null;

    /** @var ?string */
    protected $token = null;

    /**
     * Sets PayPal request object.
     *
     * @param \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest $request
     */
    public function setPayPalRequest($request)
    {
        $this->payPalRequest = $request;
    }

    /**
     * Returns PayPal request object.
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest
     */
    public function getPayPalRequest()
    {
        if ($this->payPalRequest === null) {
            $this->payPalRequest = oxNew(\OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest::class);
        }

        return $this->payPalRequest;
    }

    /**
     * Sets Session.
     *
     * @param \OxidEsales\Eshop\Core\Session $session
     * @deprecated Use self::setToken to set token or omit this method if it should be taken from session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Returns Session.
     *
     * @return \OxidEsales\Eshop\Core\Session
     *
     * @throws \OxidEsales\PayPalModule\Core\Exception\PayPalMissingParameterException
     *
     * @deprecated Session property is never used in this class
     */
    public function getSession()
    {
        if (!$this->session) {
            /**
             * @var \OxidEsales\PayPalModule\Core\Exception\PayPalMissingParameterException $exception
             */
            $exception = oxNew(\OxidEsales\PayPalModule\Core\Exception\PayPalMissingParameterException::class);
            throw $exception;
        }

        return $this->session;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        if (!$this->token) {
            $session = \OxidEsales\Eshop\Core\Registry::getSession();
            $this->token = $session->getVariable('oepaypal-token');
        }

        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token)
    {
        $this->token = $token;
    }

    /**
     * Builds Request.
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest
     */
    public function buildRequest()
    {
        $request = $this->getPayPalRequest();
        $request->setParameter('TOKEN', $this->getToken());

        return $request;
    }
}
