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

use OxidEsales\PayPalModule\GraphQL\Exception\GraphQLServiceNotFound;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Event\AbstractShopAwareEventSubscriber;
use OxidEsales\PayPalModule\GraphQL\Service\BeforePlaceOrder as BeforePlaceOrderService;

class BeforePlaceOrder extends AbstractShopAwareEventSubscriber
{
    /** @var BeforePlaceOrderService */
    private $beforePlaceOrderService;

    public function __construct(BeforePlaceOrderService $beforePlaceOrderService)
    {
        $this->beforePlaceOrderService = $beforePlaceOrderService;
    }

    public function handle(Event $event): Event
    {
        $this->validateState();

        $this->beforePlaceOrderService->handle($event);
        
        return $event;
    }

    public static function getSubscribedEvents()
    {
        return [
            'OxidEsales\GraphQL\Storefront\Basket\Event\BeforePlaceOrder' => 'handle'
        ];
    }

    protected function validateState(): void
    {
        if (is_null($this->beforePlaceOrderService)) {
            throw GraphQLServiceNotFound::byServiceName(BeforePlaceOrderService::class);
        }
    }
}