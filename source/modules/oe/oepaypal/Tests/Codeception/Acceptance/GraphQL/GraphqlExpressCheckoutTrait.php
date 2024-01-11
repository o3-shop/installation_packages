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

namespace OxidEsales\PayPalModule\Tests\Codeception\Acceptance\GraphQL;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\PayPalModule\Tests\Codeception\AcceptanceTester;
use TheCodingMachine\GraphQLite\Types\ID;

trait GraphqlExpressCheckoutTrait
{
    protected function paypalExpressApprovalProcess(
        AcceptanceTester $I,
        string $basketId
    ): array
    {
        $variables = [
            'basketId' => $basketId,
            'returnUrl' => EshopRegistry::getConfig()->getShopUrl(),
            'cancelUrl' => EshopRegistry::getConfig()->getShopUrl()
        ];

        $mutation = '
            query ($basketId: ID!, $returnUrl: String!, $cancelUrl: String!) {
                paypalExpressApprovalProcess(
                    basketId: $basketId,
                    returnUrl: $returnUrl,
                    cancelUrl: $cancelUrl,
                    displayBasketInPayPal: true
                ) {
                   token
                   communicationUrl
                }
            }
        ';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function removeItemFromBasket(
        AcceptanceTester $I,
        string $basketId,
        string $itemId,
        int $amount = 1
    ): array
    {
        $variables = [
            'basketId' => $basketId,
            'itemId'   => $itemId,
            'amount'   => $amount
        ];

        $mutation = 'mutation ($basketId: ID!, $itemId: ID!, $amount: Int!) {
            basketRemoveItem(
                basketId: $basketId, 
                itemId: $itemId, 
                amount: $amount
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function addVoucherToBasket(
        AcceptanceTester $I,
        string $basketId,
        string $voucherNumber
    ) : array
    {
        $variables = [
            'basketId'      => $basketId,
            'voucherNumber' => $voucherNumber
        ];

        $mutation = 'mutation ($basketId: ID!, $voucherNumber: String!) {
            basketAddVoucher (
                basketId: $basketId,
                voucherNumber: $voucherNumber
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }

    protected function removeVoucherFromBasket(
        AcceptanceTester $I,
        string $basketId,
        string $voucherId
    ) : array
    {
        $variables = [
            'basketId'  => $basketId,
            'voucherId' => $voucherId
        ];

        $mutation = 'mutation ($basketId: String!, $voucherId: String!) {
            basketRemoveVoucher (
                basketId: $basketId,
                voucherId: $voucherId
            ) {
                id
            }
        }';

        return $this->getGQLResponse($I, $mutation, $variables);
    }
}