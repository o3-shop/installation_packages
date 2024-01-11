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

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Page\Checkout\ThankYou;
use OxidEsales\Codeception\Step\Basket;
use OxidEsales\Codeception\Step\ProductNavigation;
use OxidEsales\Codeception\Page\Checkout\PaymentCheckout;
use OxidEsales\Codeception\Page\Checkout\Basket as BasketCheckout;
use OxidEsales\PayPalModule\Tests\Codeception\AcceptanceTester;
use OxidEsales\PayPalModule\Tests\Codeception\Page\Checkout\OrderCheckout;
use OxidEsales\PayPalModule\Tests\Codeception\Page\PayPalLogin;
use OxidEsales\Codeception\Module\Translation\Translator;

/**
 * @group oepaypal
 * @group oepaypal_standard
 * @group oepaypal_express_checkout
 *
 * Tests for checkout with payment process started via paypal button
 */
class ExpressCheckoutCest extends BaseCest
{
    /**
     * @group oepaypal_mandatory_test_with_graphql
     */
    public function checkoutWithPaypalExpressWithNotYetExistingShopAccount(AcceptanceTester $I)
    {
        $I->wantToTest('express checkout from minibasket button. Customer is not logged in and account does not exist in shop');

        $I->updateConfigInDatabase('sOEPayPalTransactionMode', 'Authorization', 'str');
        $I->updateConfigInDatabase('blOEPayPalFinalizeOrderOnPayPal', true, 'bool');

        $basket = new Basket($I);

        $basketItem = Fixtures::get('product');

        //add Product to basket
        $basket->addProductToBasket($basketItem['id'], $basketItem['amount']);
        $I->openShop()->seeMiniBasketContains([$basketItem], $basketItem['price'], (string) $basketItem['amount']);
        $I->waitForElementVisible("#paypalExpressCheckoutMiniBasketImage", 10);
        $I->click("#paypalExpressCheckoutMiniBasketImage");

        $loginPage = new PayPalLogin($I);
        $loginPage->loginAndCheckout($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        $thankYouPage = new ThankYou($I);
        $orderNumber = $thankYouPage->grabOrderNumber();

        $I->assertGreaterThan(1, $orderNumber);
        $I->seeInDataBase(
            'oxorder',
            [
                'OXORDERNR' => $orderNumber,
                'OXTOTALORDERSUM' => '119.6',
                'OXBILLFNAME' => $_ENV['sBuyerFirstName'],
                'OXBILLCITY' => 'Freiburg',
                'OXDELCITY' => ''
            ]
        );

        //Order was only authorized, so it should not yet be marked as paid
        $oxPaid = $I->grabFromDatabase('oxorder', 'oxpaid', ['OXORDERNR' => $orderNumber]);
        $I->assertStringStartsWith('0000-00-00', $oxPaid);
    }

    /**
     * NOTE: this test relies on the shipping cost callback NOT being accessible by PayPal
     *
     * @group oepaypal_will_fail_with_public_url
     */
    public function testExpressCheckoutFromDetailsButtonWhenNotLoggedInWithExistingShopAccount(AcceptanceTester $I): void
    {
        $I->wantToTest('checkout from details page with empty cart. Customer is not logged in to existing account in shop.');

        //Only use name will be same as PayPal. Addresses will be different.
        $this->setUserDataSameAsPayPal($I);

        $I->openShop();
        $I->waitForText(Translator::translate('HOME'));

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->seeElement("#paypalExpressCheckoutDetailsButton");
        $I->click("#paypalExpressCheckoutDetailsButton");

        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        //we are below shipping cost free limit and did not enable callback
        //so we need to reapprove the order with PayPal
        $I->see(Translator::translate('OEPAYPAL_ORDER_TOTAL_HAS_CHANGED'));

        $I->seeElement('//input[@name="paypalExpressCheckoutButtonECS"]');
        $I->click('//input[@name="paypalExpressCheckoutButtonECS"]');
        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        $orderCheckout = new OrderCheckout($I);
        $orderCheckout->submitOrder();

        $thankYouPage = new ThankYou($I);
        $orderNumber = $thankYouPage->grabOrderNumber();

        $I->assertGreaterThan(1, $orderNumber);

        $I->seeInDataBase(
            'oxorder',
            [
                'OXORDERNR' => $orderNumber,
                'OXTOTALORDERSUM' => Fixtures::get('totalordersum_ecswithshipping'),
                'OXBILLFNAME' => $_ENV['sBuyerFirstName'],
                'OXBILLCITY' => 'Freiburg',
            ]
        );

        //Order was captured, so it should be marked as paid
        $oxPaid = $I->grabFromDatabase('oxorder', 'oxpaid', ['OXORDERNR' => $orderNumber]);
        $I->assertStringStartsWith(date('Y-m-d'), $oxPaid);
    }

    public function testExpressCheckoutFromDetailsButtonWhenNotLoggedInWithExistingSameDataShopAccount(AcceptanceTester $I): void
    {
        $I->wantToTest('checkout from details page with empty cart. Customer is not logged in to existing account in shop.');

        //Only use name will be same as PayPal. Addresses will be different.
        $this->setUserNameSameAsPayPal($I);

        $I->openShop();
        $I->waitForText(Translator::translate('HOME'));

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->seeElement("#paypalExpressCheckoutDetailsButton");
        $I->click("#paypalExpressCheckoutDetailsButton");

        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        //Only email address is found in shop but address does not match
        $I->see(Translator::translate('OEPAYPAL_ERROR_USER_ADDRESS'));

        //So let's log in and try again
        $basketCheckout = new BasketCheckout($I);
        $basketCheckout->loginUser($_ENV['sBuyerLogin'], Fixtures::get('userPassword'));

        $I->seeElement('//input[@name="paypalExpressCheckoutButtonECS"]');
        $I->click('//input[@name="paypalExpressCheckoutButtonECS"]');
        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        $orderCheckout = new OrderCheckout($I);
        $orderCheckout->submitOrder();

        $thankYouPage = new ThankYou($I);
        $orderNumber = $thankYouPage->grabOrderNumber();

        $I->assertGreaterThan(1, $orderNumber);

        $I->seeInDataBase(
            'oxorder',
            [
                'OXORDERNR' => $orderNumber,
                'OXTOTALORDERSUM' => Fixtures::get('totalordersum_ecswithshipping'),
                'OXBILLFNAME' => Fixtures::get('details')['firstname'],
                'OXBILLCITY' => Fixtures::get('details')['oxcity']
            ]
        );

        //Order was captured, so it should be marked as paid
        $oxPaid = $I->grabFromDatabase('oxorder', 'oxpaid', ['OXORDERNR' => $orderNumber]);
        $I->assertStringStartsWith(date('Y-m-d'), $oxPaid);
    }

    public function testExpressCheckoutFromDetailsButton(AcceptanceTester $I): void
    {
        $I->wantToTest('checkout from details page with prefilled cart. Customer is logged in. PayPal and shop data are different.');

        $this->proceedToBasketStep($I);

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->seeElement("#paypalExpressCheckoutDetailsButton");
        $I->click("#paypalExpressCheckoutDetailsButton");
        $I->see(substr(sprintf(Translator::translate('OEPAYPAL_SAME_ITEM_QUESTION'), 1), 0, 30));
        $I->seeElement("#actionAddToBasketAndGoToCheckout");
        $I->click("#actionAddToBasketAndGoToCheckout");

        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        $orderCheckout = new OrderCheckout($I);
        $orderCheckout->submitOrder();

        $thankYouPage = new ThankYou($I);
        $orderNumber = $thankYouPage->grabOrderNumber();

        $I->assertGreaterThan(1, $orderNumber);

        $I->seeInDataBase(
            'oxorder',
            [
                'OXORDERNR' => $orderNumber,
                'OXTOTALORDERSUM' => Fixtures::get('totalordersum_ecsdetails'),
                'OXBILLFNAME' => Fixtures::get('details')['firstname'],
                'OXBILLCITY' => Fixtures::get('details')['oxcity'],
                'OXDELCITY' => ''
            ]
        );

        //Order was captured, so it should be marked as paid
        $oxPaid = $I->grabFromDatabase('oxorder', 'oxpaid', ['OXORDERNR' => $orderNumber]);
        $I->assertStringStartsWith(date('Y-m-d'), $oxPaid);
    }

    /**
     * NOTE: this test relies on the shipping cost callback NOT being accessible by PayPal
     *
     * @group oepaypal_will_fail_without_public_url
     * @group oepaypal_express_checkout_callback
     */
    public function testExpressCheckoutWithCallback(AcceptanceTester $I): void
    {
        $I->wantToTest('checkout from details page with empty cart. Customer has no account in shop.');

        $I->openShop();
        $I->waitForText(Translator::translate('HOME'));

        $productNavigation = new ProductNavigation($I);
        $productNavigation->openProductDetailsPage(Fixtures::get('product')['id']);
        $I->seeElement("#paypalExpressCheckoutDetailsButton");
        $I->click("#paypalExpressCheckoutDetailsButton");

        $loginPage = new PayPalLogin($I);
        $loginPage->approveExpressPayPal($_ENV['sBuyerLogin'], $_ENV['sBuyerPassword']);

        $orderCheckout = new OrderCheckout($I);
        $orderCheckout->submitOrder();

        $thankYouPage = new ThankYou($I);
        $orderNumber = $thankYouPage->grabOrderNumber();

        $I->assertGreaterThan(1, $orderNumber);

        $I->seeInDataBase(
            'oxorder',
            [
                'OXORDERNR' => $orderNumber,
                'OXTOTALORDERSUM' => Fixtures::get('totalordersum_ecswithshipping'),
                'OXBILLFNAME' => $_ENV['sBuyerFirstName'],
                'OXBILLCITY' => 'Freiburg',
            ]
        );

        //Order was captured, so it should be marked as paid
        $oxPaid = $I->grabFromDatabase('oxorder', 'oxpaid', ['OXORDERNR' => $orderNumber]);
        $I->assertStringStartsWith(date('Y-m-d'), $oxPaid);
    }
}
