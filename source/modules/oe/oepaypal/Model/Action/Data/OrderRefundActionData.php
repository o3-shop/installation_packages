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

namespace OxidEsales\PayPalModule\Model\Action\Data;

/**
 * PayPal order action factory class
 */
class OrderRefundActionData extends \OxidEsales\PayPalModule\Model\Action\Data\OrderActionData
{
    /**
     * @var \OxidEsales\PayPalModule\Model\OrderPayment::class
     */
    public $paymentBeingRefunded = null;

    /**
     * Returns action type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getRequest()->getRequestParameter('refund_type');
    }

    /**
     * Returns action amount.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getRequest()->getRequestParameter('transaction_id');
    }

    /**
     * Returns amount to refund.
     *
     * @return float
     */
    public function getAmount()
    {
        $amount = $this->getRequest()->getRequestParameter('refund_amount');
        $validDecimalAmount = preg_replace('/,(?=\d{0,2}$)/', '.', $amount) ?? $amount;

        return $validDecimalAmount ? $validDecimalAmount : $this->getPaymentBeingRefunded()->getRemainingRefundAmount();
    }

    /**
     * Returns currency.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getOrder()->getPayPalOrder()->getCurrency();
    }

    /**
     * Returns payment to refund.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPayment
     */
    public function getPaymentBeingRefunded()
    {
        if (is_null($this->paymentBeingRefunded)) {
            $this->paymentBeingRefunded = oxNew(\OxidEsales\PayPalModule\Model\OrderPayment::class);
            $this->paymentBeingRefunded->loadByTransactionId($this->getTransactionId());
        }

        return $this->paymentBeingRefunded;
    }
}
