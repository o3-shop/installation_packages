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
namespace OxidEsales\PayPalModule\Controller;

/**
 * PayPal IPN handler class.
 *
 * Handle PayPal notifications.
 *  - Extract data.
 *  - Check if valid.
 *  - Call order methods to save data.
 */
class IPNHandler extends \OxidEsales\PayPalModule\Controller\FrontendController
{
    /**
     * Current class default template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'ipnhandler.tpl';

    /**
     * PayPal request handler.
     *
     * @var \OxidEsales\PayPalModule\Core\Request
     */
    protected $payPalRequest = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\IPNRequestVerifier
     */
    protected $ipnRequestVerifier = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\IPNProcessor
     */
    protected $processor = null;

    /**
     * Set object to handle request.
     *
     * @param \OxidEsales\PayPalModule\Core\Request $payPalRequest object to set.
     */
    public function setPayPalRequest($payPalRequest)
    {
        $this->payPalRequest = $payPalRequest;
    }

    /**
     * Create object \OxidEsales\PayPalModule\Core\Request to get PayPal request information.
     *
     * @return \OxidEsales\PayPalModule\Core\Request
     */
    public function getPayPalRequest()
    {
        if ($this->payPalRequest === null) {
            $this->payPalRequest = oxNew(\OxidEsales\PayPalModule\Core\Request::class);
        }

        return $this->payPalRequest;
    }

    /**
     * Sets IPN request verifier.
     *
     * @param \OxidEsales\PayPalModule\Model\IPNRequestVerifier $ipnRequestVerifier
     */
    public function setIPNRequestVerifier($ipnRequestVerifier)
    {
        $this->ipnRequestVerifier = $ipnRequestVerifier;
    }

    /**
     * Returns IPN request verifier.
     *
     * @return \OxidEsales\PayPalModule\Model\IPNRequestVerifier
     */
    public function getIPNRequestVerifier()
    {
        if (is_null($this->ipnRequestVerifier)) {
            $ipnRequestVerifier = oxNew(\OxidEsales\PayPalModule\Model\IPNRequestVerifier::class);
            $this->setIPNRequestVerifier($ipnRequestVerifier);
        }

        return $this->ipnRequestVerifier;
    }

    /**
     * \OxidEsales\PayPalModule\Model\IPNProcessor setter.
     *
     * @param \OxidEsales\PayPalModule\Model\IPNProcessor $processor
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    /**
     * Returns \OxidEsales\PayPalModule\Model\IPNProcessor object. If object is not set, than it creates it and sets.
     *
     * @return \OxidEsales\PayPalModule\Model\IPNProcessor
     */
    public function getProcessor()
    {
        if (is_null($this->processor)) {
            $processor = oxNew(\OxidEsales\PayPalModule\Model\IPNProcessor::class);
            $this->setProcessor($processor);
        }

        return $this->processor;
    }

    /**
     * IPN handling function.
     *  - Call to check if request is valid (from PayPal and to correct shop).
     *  - Initiate payment status changes according to IPN information.
     *
     * @return void
     */
    public function handleRequest()
    {
        $requestHandled = false;
        $requestId = md5(microtime(true));

        $request = $this->getPayPalRequest();
        $logger = $this->getLogger();
        $logger->setTitle("IPN Request by PayPal");
        $logger->log([
            'Request ID' => $requestId,
            'GET parameters' => $request->getGet(),
            'POST parameters' => $request->getPost()
        ]);

        $requestValid = $this->requestValid();

        if ($requestValid) {
            $lang = $this->getPayPalConfig()->getLang();

            $processor = $this->getProcessor();
            $processor->setRequest($request);
            $processor->setLang($lang);

            $requestHandled = $processor->process();
        }

        $logger->setTitle("IPN Process result");
        $logger->log([
            'Request ID' => $requestId,
            'Result' => $requestHandled
        ]);
    }

    /**
     * IPN handling function.
     *  - Verify request with PayPal (if request from PayPal and to correct shop).
     *
     * @return bool
     */
    public function requestValid()
    {
        $requestValid = true;

        $request = $this->getRequest();

        $ipnRequestVerifier = $this->getIPNRequestVerifier();
        $ipnRequestVerifier->setRequest($request);
        $ipnRequestVerifier->setShopOwner($this->getPayPalConfig()->getUserEmail());
        $requestCorrect = $ipnRequestVerifier->requestCorrect();

        if (!$requestCorrect) {
            $requestValid = false;

            $logger = $this->getLogger();
            $logger->setTitle("IPN VERIFICATION FAILURE BY PAYPAL");
            $logger->log($ipnRequestVerifier->getFailureMessage());
        }

        return $requestValid;
    }
}
