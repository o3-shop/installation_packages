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

declare(strict_types=1);

namespace OxidEsales\PayPalModule\GraphQL\Service;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\GraphQL\Storefront\Basket\Event\BeforePlaceOrder as BeforePlaceOrderEvent;
use OxidEsales\PayPalModule\Core\Config as PayPalConfig;
use OxidEsales\PayPalModule\GraphQL\Exception\BasketCommunication;
use OxidEsales\PayPalModule\GraphQL\Service\Basket as BasketService;
use OxidEsales\PayPalModule\GraphQL\Service\Payment as PaymentService;
use OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails;
use OxidEsales\GraphQL\Storefront\Basket\Service\Basket as StorefrontBasketService;
use OxidEsales\PayPalModule\GraphQL\Exception\GraphQLServiceNotFound;

final class BeforePlaceOrder
{
    /** @var PaymentService */
    private $paymentService;

    /** @var BasketService */
    private $basketService;

    /** @var StorefrontBasketService */
    private $storefrontBasketService;

    public function __construct(
        PaymentService $paymentService,
        BasketService $basketService,
        StorefrontBasketService $storefrontBasketService = null
    ) {
        $this->paymentService = $paymentService;
        $this->basketService = $basketService;
        $this->storefrontBasketService = $storefrontBasketService;
    }

    public function handle(BeforePlaceOrderEvent $event): void
    {
        $this->validateState();

        $userBasket = $this->storefrontBasketService->getAuthenticatedCustomerBasket($event->getBasketId());
        if ($this->basketService->checkBasketPaymentMethodIsPayPal($userBasket)) {
            $extendUserBasket = new BasketExtendType();

            $token = $extendUserBasket->paypalToken($userBasket);
            if (!$token) {
                throw BasketCommunication::notStarted($userBasket->id()->val());
            }

            //call PayPal API once for ExpressCheckoutDetails
            /** @var ResponseGetExpressCheckoutDetails $expressCheckoutDetails */
            $expressCheckoutDetails = $this->paymentService->getExpressCheckoutDetails($token);

            $tokenStatus = $this->paymentService->getPayPalTokenStatus($token, $expressCheckoutDetails);
            if (!$tokenStatus->isTokenApproved()) {
                throw BasketCommunication::notConfirmed($userBasket->id()->val());
            }

            $sessionBasket = $this->paymentService->getValidEshopBasketModel($userBasket, $expressCheckoutDetails);

            // In order to be able to finalize order, using PayPal as payment method,
            // we need to prepare the following session variables.
            $session = EshopRegistry::getSession();
            $session->setBasket($sessionBasket);
            $session->setVariable('oepaypal-token', $token);
            $session->setVariable("oepaypal-userId", $sessionBasket->getUser()->getId());
            $session->setVariable('oepaypal-payerId', $tokenStatus->getPayerId());
            $session->setVariable(
                PayPalConfig::OEPAYPAL_TRIGGER_NAME,
                $extendUserBasket->paypalServiceType($userBasket)
            );
        }
    }

    protected function validateState(): void
    {
        if (is_null($this->storefrontBasketService)) {
            throw GraphQLServiceNotFound::byServiceName(StorefrontBasketService::class);
        }
    }
}