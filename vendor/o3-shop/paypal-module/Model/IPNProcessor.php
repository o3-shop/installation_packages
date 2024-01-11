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
 * PayPal IPN processor class.
 */
class IPNProcessor
{
    /**
     * PayPal request handler.
     *
     * @var \OxidEsales\PayPalModule\Core\Request
     */
    protected $request = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\IPNPaymentBuilder
     */
    protected $paymentBuilder = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\OrderManager
     */
    protected $orderManager = null;

    /**
     * Set object \OxidEsales\PayPalModule\Core\Request.
     *
     * @param \OxidEsales\PayPalModule\Core\Request $request object to set.
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Create object \OxidEsales\PayPalModule\Core\Request to get PayPal request information.
     *
     * @return \OxidEsales\PayPalModule\Core\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets language object.
     *
     * @param \OxidEsales\Eshop\Core\Language $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Returns language object.
     *
     * @return \OxidEsales\Eshop\Core\Language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets payment builder.
     *
     * @param \OxidEsales\PayPalModule\Model\IPNPaymentBuilder $paymentBuilder
     */
    public function setPaymentBuilder($paymentBuilder)
    {
        $this->paymentBuilder = $paymentBuilder;
    }

    /**
     * Creates \OxidEsales\PayPalModule\Model\IPNPaymentBuilder, sets if it was not set and than returns it.
     *
     * @return \OxidEsales\PayPalModule\Model\IPNPaymentBuilder
     */
    public function getPaymentBuilder()
    {
        if (is_null($this->paymentBuilder)) {
            $this->paymentBuilder = oxNew(\OxidEsales\PayPalModule\Model\IPNPaymentBuilder::class);
        }

        return $this->paymentBuilder;
    }

    /**
     * Sets order manager.
     *
     * @param \OxidEsales\PayPalModule\Model\OrderManager $payPalOrderManager
     */
    public function setOrderManager($payPalOrderManager)
    {
        $this->orderManager = $payPalOrderManager;
    }

    /**
     * Returns order manager.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderManager
     */
    public function getOrderManager()
    {
        if (is_null($this->orderManager)) {
            $this->orderManager = oxNew(\OxidEsales\PayPalModule\Model\OrderManager::class);
        }

        return $this->orderManager;
    }

    /**
     * Initiate payment status changes according to IPN information.
     *
     * @return bool
     */
    public function process()
    {
        $lang = $this->getLang();
        $request = $this->getRequest();
        $paymentBuilder = $this->getPaymentBuilder();
        $payPalOrderManager = $this->getOrderManager();

        // Create Payment from Request.
        $paymentBuilder->setLang($lang);
        $paymentBuilder->setRequest($request);
        $orderPayment = $paymentBuilder->buildPayment();

        $payPalOrderManager->setOrderPayment($orderPayment);
        $processSuccess = $payPalOrderManager->updateOrderStatus();

        return $processSuccess;
    }
}
