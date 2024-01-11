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

use OxidEsales\PayPalModule\Model\PaymentManager;
use OxidEsales\PayPalModule\Core\PayPalService;

/**
 * Payment class wrapper for PayPal module
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\PaymentController
 */
class PaymentController extends PaymentController_parent
{
    /**
     * Detects is current payment must be processed by PayPal and instead of standard validation
     * redirects to standard PayPal dispatcher
     *
     * @return bool
     */
    public function validatePayment()
    {
        $paymentId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('paymentid');
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $basket = $session->getBasket();
        if ($paymentId === 'oxidpaypal' && !$this->isConfirmedByPayPal($basket)) {
            $session->setVariable('paymentid', 'oxidpaypal');

            return 'oepaypalstandarddispatcher?fnc=setExpressCheckout'
                   . '&displayCartInPayPal=' . ((int) \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('displayCartInPayPal'));
        }

        return parent::validatePayment();
    }

    /**
     * Detects if current payment was already successfully processed by PayPal
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket $basket basket object
     *
     * @return bool
     */
    public function isConfirmedByPayPal($basket)
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $oldBasketAmount = $session->getVariable("oepaypal-basketAmount");
        if (!$oldBasketAmount) {
            return false;
        }

        $paypalService = oxNew(PayPalService::class);
        $paymentManager = oxNew(PaymentManager::class, $paypalService);

        return $paymentManager->validateApprovedBasketAmount($basket->getPrice()->getBruttoPrice(), $oldBasketAmount);
    }
}
