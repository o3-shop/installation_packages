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

namespace OxidEsales\PayPalModule\Tests\Codeception\Acceptance;

use OxidEsales\Codeception\Step\ProductNavigation;
use OxidEsales\PayPalModule\Tests\Codeception\AcceptanceTester;
use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Module\Translation\Translator;

/**
 * @group oepaypal
 * @group oepaypal_standard
 * @group oepaypal_buttons
 *
 * Tests for checkout with regular payment method 'oxidpaypal'
 */
class ButtonPlacementCest extends BaseCest
{
    public function _after(AcceptanceTester $I): void
    {
        $I->activatePaypalModule();

        parent::_after($I);
    }

    /**
     * @group oepaypal_deactivated
     */
    public function deactivatedModuleDoesNoHarm(AcceptanceTester $I): void
    {
        $I->wantToTest('that the shop is safe with installed but inactive PayPal Module');

        $I->deactivatePaypalModule();

        $I->openShop();
        $I->waitForText(Translator::translate('HOME'));

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->dontSeeElement("#paypalExpressCheckoutDetailsButton");

        $home = $I->openShop()
            ->loginUser(Fixtures::get('userName'), Fixtures::get('userPassword'));
        $I->waitForText(Translator::translate('HOME'));

        $this->fillBasket($I);
        $I->dontSeeElement('//input[@name="paypalExpressCheckoutButtonECS"]');

        $home->openMiniBasket();
        $I->dontSeeElement('#paypalExpressCheckoutMiniBasketImage');

        $this->fromBasketToPayment($I);
        $I->dontSeeElement('#payment_oxidpaypal');
    }

    public function seeButtonsInExpectedLocations(AcceptanceTester $I): void
    {
        $I->wantToTest('that all PayPal checkout buttons are shown');

        $I->openShop();
        $I->waitForText(Translator::translate('HOME'));

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->seeElement("#paypalExpressCheckoutDetailsButton");

        $home = $I->openShop()
            ->loginUser(Fixtures::get('userName'), Fixtures::get('userPassword'));
        $I->waitForText(Translator::translate('HOME'));

        //add product to basket and start checkout
        $this->fillBasket($I);
        $I->seeElement('//input[@name="paypalExpressCheckoutButtonECS"]');

        $home->openMiniBasket();
        $I->seeElement('#paypalExpressCheckoutMiniBasketImage');

        $this->fromBasketToPayment($I);
        $I->seeElement('#payment_oxidpaypal');
    }

}