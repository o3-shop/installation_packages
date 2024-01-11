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

namespace OxidEsales\PayPalModule\Model\Action;

/**
 * PayPal order action void class
 */
class OrderVoidAction extends \OxidEsales\PayPalModule\Model\Action\OrderAction
{
    /**
     * Processes PayPal response
     */
    public function process()
    {
        $handler = $this->getHandler();
        $response = $handler->getPayPalResponse();
        $data = $handler->getData();

        $order = $this->getOrder();
        $amount = $order->getRemainingOrderSum();
        $order->setVoidedAmount($amount);
        $order->setPaymentStatus($data->getOrderStatus());
        $order->save();

        $payment = oxNew(\OxidEsales\PayPalModule\Model\OrderPayment::class);
        $payment->setDate($this->getDate());
        $payment->setTransactionId($response->getAuthorizationId());
        $payment->setCorrelationId($response->getCorrelationId());
        $payment->setAction('void');
        $payment->setStatus('Voided');
        $payment->setAmount($amount);

        $payment = $order->getPaymentList()->addPayment($payment);

        if ($data->getComment()) {
            $comment = oxNew(\OxidEsales\PayPalModule\Model\OrderPaymentComment::class);
            $comment->setComment($data->getComment());
            $payment->addComment($comment);
        }
    }
}
