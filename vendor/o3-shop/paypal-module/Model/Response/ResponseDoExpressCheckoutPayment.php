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

namespace OxidEsales\PayPalModule\Model\Response;

/**
 * PayPal response class for do express checkout payment
 */
class ResponseDoExpressCheckoutPayment extends \OxidEsales\PayPalModule\Model\Response\Response
{
    /**
     * Return transaction id.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getValue('PAYMENTINFO_0_TRANSACTIONID');
    }

    /**
     * Return transaction id.
     *
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->getValue('CORRELATIONID');
    }

    /**
     * Return payment status.
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->getValue('PAYMENTINFO_0_PAYMENTSTATUS');
    }

    /**
     * Return price amount.
     *
     * @return string
     */
    public function getAmount()
    {
        return ( float ) $this->getValue('PAYMENTINFO_0_AMT');
    }

    /**
     * Return currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->getValue('PAYMENTINFO_0_CURRENCYCODE');
    }
}
