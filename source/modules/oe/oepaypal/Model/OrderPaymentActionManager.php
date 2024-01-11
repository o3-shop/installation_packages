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
 * PayPal Order payment action manager class
 */
class OrderPaymentActionManager
{
    /**
     * Array of available actions for payment action.
     *
     * @var array
     */
    protected $availableActions = array(
        "capture" => array(
            "Completed" => array(
                'refund'
            )
        )
    );

    /**
     * Order object.
     *
     * @var \OxidEsales\PayPalModule\Model\OrderPayment
     */
    protected $payment = null;

    /**
     * Sets order.
     *
     * @param \OxidEsales\PayPalModule\Model\OrderPayment $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Returns order.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPayment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Returns available actions for given payment action
     *
     * @param string $paymentAction
     * @param string $paymentStatus
     *
     * @return array
     */
    protected function getAvailableActions($paymentAction, $paymentStatus)
    {
        $actions = $this->availableActions[$paymentAction][$paymentStatus];

        return $actions ? $actions : array();
    }


    /**
     * Checks whether action is available for given order
     *
     * @param string                                      $action
     * @param \OxidEsales\PayPalModule\Model\OrderPayment $payment
     *
     * @return bool
     */
    public function isActionAvailable($action, $payment = null)
    {
        if ($payment) {
            $this->setPayment($payment);
        }

        $payment = $this->getPayment();

        $isAvailable = in_array($action, $this->getAvailableActions($payment->getAction(), $payment->getStatus()));

        if ($isAvailable) {
            $isAvailable = false;

            switch ($action) {
                case 'refund':
                    $isAvailable = ($payment->getAmount() > $payment->getRefundedAmount());
                    break;
            }
        }

        return $isAvailable;
    }
}
