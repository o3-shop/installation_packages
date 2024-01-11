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

use OxidEsales\Eshop\Core\Registry;

/**
 * Order class wrapper for PayPal module
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\OrderController
 */
class OrderController extends OrderController_parent
{
    /**
     * Checks if payment action is processed by PayPal
     *
     * @return bool
     */
    public function isPayPal()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        return ($session->getVariable("paymentid") == "oxidpaypal");
    }

    /**
     * Returns PayPal user
     *
     * @return \OxidEsales\Eshop\Application\Model\User
     */
    public function getUser()
    {
        $user = parent::getUser();
        $session = \OxidEsales\Eshop\Core\Registry::getSession();

        $userId = $session->getVariable("oepaypal-userId");
        if ($this->isPayPal() && $userId) {
            $payPalUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
            if ($payPalUser->load($userId)) {
                $user = $payPalUser;
            }
        }

        return $user;
    }

    /**
     * Returns PayPal payment object if PayPal is on, or returns parent::getPayment()
     *
     * @return \OxidEsales\Eshop\Application\Model\Payment
     */
    public function getPayment()
    {
        if (!$this->isPayPal()) {
            // removing PayPal payment type from session
            $session = \OxidEsales\Eshop\Core\Registry::getSession();
            $session->deleteVariable('oepaypal');
            $session->deleteVariable('oepaypal-basketAmount');

            return parent::getPayment();
        }

        if ($this->payment === null) {
            // payment is set ?
            $payment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
            if ($payment->load('oxidpaypal')) {
                $this->payment = $payment;
            }
        }

        return $this->payment;
    }

    /**
     * Make sure orderId is set in sess_challenge session field.
     * If it's not set and the option to finalize order on paypal checkout is enabled,
     * the last checkout step - thank you page - will redirect the user to home.
     *
     * @return string
     */
    public function execute()
    {
        $nextStep = parent::execute();
        $session = Registry::getSession();

        if (!$session->getVariable('sess_challenge')) {
            $orderId = $this->getBasket()->getOrderId();
            $session->setVariable('sess_challenge', $orderId);
        }

        return $nextStep;
    }

    /**
     * Returns current order object
     *
     * @return \OxidEsales\Eshop\Application\Model\Order
     */
    protected function getOrder()
    {
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $session = \OxidEsales\Eshop\Core\Registry::getSession();

        $order->load($session->getVariable('sess_challenge'));

        return $order;
    }

    /**
     * Checks if order payment is PayPal and redirects to payment processing part.
     *
     * @param int $success order state
     *
     * @return string
     */
    protected function getNextStep($success)
    {
        $nextStep = parent::_getNextStep($success);

        // Detecting PayPal & loading order & execute payment only if go wrong
        if ($this->isPayPal() && ($success == \OxidEsales\Eshop\Application\Model\Order::ORDER_STATE_PAYMENTERROR)) {
            $session = \OxidEsales\Eshop\Core\Registry::getSession();
            $payPalType = (int) $session->getVariable("oepaypal");
            $nextStep = ($payPalType == 2) ? "basket" : "order";
        }

        return $nextStep;
    }
}
