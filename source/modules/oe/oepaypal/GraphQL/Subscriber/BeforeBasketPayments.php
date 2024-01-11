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

namespace OxidEsales\PayPalModule\GraphQL\Subscriber;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\GraphQL\Storefront\Basket\Event\BeforeBasketPayments as BeforeBasketPaymentsEvent;
use OxidEsales\GraphQL\Storefront\Basket\Service\Basket as StorefrontBasketService;
use OxidEsales\PayPalModule\Core\Config as PayPalConfig;
use OxidEsales\PayPalModule\GraphQL\Exception\GraphQLServiceNotFound;
use OxidEsales\PayPalModule\GraphQL\Service\Basket as BasketService;
use OxidEsales\PayPalModule\GraphQL\Service\BasketExtendType;

class BeforeBasketPayments extends AbstractShopAwareEventSubscriber
{
    /** @var BasketService */
    private $basketService;

    /** @var StorefrontBasketService */
    private $storefrontBasketService;

    public function __construct(
        BasketService $basketService,
        StorefrontBasketService $storefrontBasketService = null
    ) {
        $this->basketService           = $basketService;
        $this->storefrontBasketService = $storefrontBasketService;
    }

    public function handle(BeforeBasketPaymentsEvent $event): BeforeBasketPaymentsEvent
    {
        $this->validateState();

        $basketDataType = $this->storefrontBasketService->getAuthenticatedCustomerBasket($event->getBasketId());
        if ($this->basketService->checkBasketPaymentMethodIsPayPal($basketDataType)) {
            $extendUserBasket = new BasketExtendType();
            $session = EshopRegistry::getSession();
            $session->setVariable(
                PayPalConfig::OEPAYPAL_TRIGGER_NAME,
                $extendUserBasket->paypalServiceType($basketDataType)
            );
        }

        return $event;
    }

    public static function getSubscribedEvents()
    {
        return [
            'OxidEsales\GraphQL\Storefront\Basket\Event\BeforeBasketPayments' => 'handle'
        ];
    }

    protected function validateState(): void
    {
        if (is_null($this->storefrontBasketService)) {
            throw GraphQLServiceNotFound::byServiceName(StorefrontBasketService::class);
        }
    }
}
