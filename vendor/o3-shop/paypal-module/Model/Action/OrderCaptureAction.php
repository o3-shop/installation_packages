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
 * PayPal order action capture class
 */
class OrderCaptureAction extends \OxidEsales\PayPalModule\Model\Action\OrderAction
{
    /**
     * @var \OxidEsales\PayPalModule\Model\Action\Handler\OrderReauthorizeActionHandler
     */
    protected $reauthorizeHandler = null;

    /**
     * Sets dependencies.
     *
     * @param \OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler     $handler
     * @param \OxidEsales\PayPalModule\Model\PayPalOrder                                  $order
     * @param \OxidEsales\PayPalModule\Model\Action\Handler\OrderReauthorizeActionHandler $reauthorizeHandler
     */
    public function __construct($handler, $order, $reauthorizeHandler)
    {
        parent::__construct($handler, $order);

        $this->reauthorizeHandler = $reauthorizeHandler;
    }

    /**
     * Returns reauthorize action handler.
     *
     * @return \OxidEsales\PayPalModule\Model\Action\Handler\OrderReauthorizeActionHandler
     */
    public function getReauthorizeHandler()
    {
        return $this->reauthorizeHandler;
    }

    /**
     * Processes PayPal response.
     */
    public function process()
    {
        $this->reauthorize();

        $handler = $this->getHandler();

        $response = $handler->getPayPalResponse();
        $data = $handler->getData();

        $this->updateOrder($response, $data);

        $payment = $this->createPayment($response);
        $paymentList = $this->getOrder()->getPaymentList();
        $payment = $paymentList->addPayment($payment);

        $this->addComment($payment, $data->getComment());
    }

    /**
     * Reauthorizes payment if order was captured at least once.
     */
    protected function reauthorize()
    {
        $order = $this->getOrder();

        if ($order->getCapturedAmount() > 0) {
            $handler = $this->getReauthorizeHandler();
            try {
                $response = $handler->getPayPalResponse();

                $payment = oxNew(\OxidEsales\PayPalModule\Model\OrderPayment::class);
                $payment->setDate($this->getDate());
                $payment->setTransactionId($response->getAuthorizationId());
                $payment->setCorrelationId($response->getCorrelationId());
                $payment->setAction('re-authorization');
                $payment->setStatus($response->getPaymentStatus());

                $order->getPaymentList()->addPayment($payment);
            } catch (\OxidEsales\PayPalModule\Core\Exception\PayPalResponseException $e) {
                // Ignore PayPal response exceptions
            }
        }
    }

    /**
     * Updates order with PayPal response info.
     *
     * @param object $response
     * @param object $data
     */
    protected function updateOrder($response, $data)
    {
        $order = $this->getOrder();
        $order->addCapturedAmount($response->getCapturedAmount());
        $order->setPaymentStatus($data->getOrderStatus());
        $order->save();
    }

    /**
     * Creates Payment object with PayPal response data.
     *
     * @param object $response
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPayment::class
     */
    protected function createPayment($response)
    {
        $payment = oxNew(\OxidEsales\PayPalModule\Model\OrderPayment::class);
        $payment->setDate($this->getDate());
        $payment->setTransactionId($response->getTransactionId());
        $payment->setCorrelationId($response->getCorrelationId());
        $payment->setAction('capture');
        $payment->setStatus($response->getPaymentStatus());
        $payment->setAmount($response->getCapturedAmount());
        $payment->setCurrency($response->getCurrency());

        return $payment;
    }

    /**
     * Adds comment to given Payment object.
     *
     * @param object $payment
     * @param string $comment
     */
    protected function addComment($payment, $commentContent)
    {
        if ($commentContent) {
            $comment = oxNew(\OxidEsales\PayPalModule\Model\OrderPaymentComment::class);
            $comment->setComment($commentContent);

            $payment->addComment($comment);
        }
    }
}
