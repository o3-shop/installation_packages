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
 * PayPal IPN Payment validator class.
 */
class IPNPaymentValidator
{
    /**
     * Language object to get translations from.
     *
     * @var object
     */
    protected $lang = null;

    /**
     * Payment created from PayPal request.
     *
     * @var \OxidEsales\PayPalModule\Model\OrderPayment
     */
    protected $requestPayment = null;

    /**
     * Payment created by PayPal request id.
     *
     * @var \OxidEsales\PayPalModule\Model\OrderPayment
     */
    protected $orderPayment = null;

    /**
     * Sets language object to get translations from.
     *
     * @param object $lang get translations from.
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Gets language object to get translations from.
     *
     * @return object
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets request object.
     *
     * @param \OxidEsales\PayPalModule\Model\OrderPayment $requestPayment
     */
    public function setRequestOrderPayment($requestPayment)
    {
        $this->requestPayment = $requestPayment;
    }

    /**
     * Returns request payment object.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPayment
     */
    public function getRequestOrderPayment()
    {
        return $this->requestPayment;
    }

    /**
     * Sets order payment object.
     *
     * @param \OxidEsales\PayPalModule\Model\OrderPayment $payment
     */
    public function setOrderPayment($payment)
    {
        $this->orderPayment = $payment;
    }

    /**
     * Returns order payment object.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPayment
     */
    public function getOrderPayment()
    {
        return $this->orderPayment;
    }

    /**
     * Returns validation failure message.
     *
     * @return string
     */
    public function getValidationFailureMessage()
    {
        $requestPayment = $this->getRequestOrderPayment();
        $orderPayment = $this->getOrderPayment();

        $currencyPayPal = $requestPayment->getCurrency();
        $pricePayPal = $requestPayment->getAmount();

        $currencyPayment = $orderPayment->getCurrency();
        $amountPayment = $orderPayment->getAmount();

        $lang = $this->getLang();
        $validationMessage = $lang->translateString('OEPAYPAL_PAYMENT_INFORMATION') . ': ' . $amountPayment . ' ' . $currencyPayment . '. ' . $lang->translateString('OEPAYPAL_INFORMATION') . ': ' . $pricePayPal . ' ' . $currencyPayPal . '.';

        return $validationMessage;
    }

    /**
     * Check if PayPal response fits payment information.
     *
     * @return bool
     */
    public function isValid()
    {
        $requestPayment = $this->getRequestOrderPayment();
        $orderPayment = $this->getOrderPayment();

        $currencyPayPal = $requestPayment->getCurrency();
        $pricePayPal = $requestPayment->getAmount();

        $currencyPayment = $orderPayment->getCurrency();
        $amountPayment = $orderPayment->getAmount();

        return ($currencyPayPal == $currencyPayment && $pricePayPal == $amountPayment);
    }
}
