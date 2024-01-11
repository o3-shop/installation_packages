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

namespace OxidEsales\PayPalModule\Tests\Unit\Model\Action;

use \OxidEsales\PayPalModule\Core\Exception\PayPalInvalidActionException;

/**
 * Testing \OxidEsales\PayPalModule\Model\Action\OrderActionFactory class.
 */
class OrderActionFactoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Data provider for testCreateAction
     */
    public function providerCreateAction()
    {
        return array(
            array('capture', \OxidEsales\PayPalModule\Model\Action\OrderCaptureAction::class),
            array('refund', \OxidEsales\PayPalModule\Model\Action\OrderRefundAction::class),
            array('void', \OxidEsales\PayPalModule\Model\Action\OrderVoidAction::class),
        );
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Action\OrderActionFactory::createAction
     * Testing action object creation with correct actions
     *
     * @dataProvider providerCreateAction
     */
    public function testCreateAction($action, $class)
    {
        $order = oxNew(\OxidEsales\PayPalModule\Model\Order::class);
        $request = new \OxidEsales\PayPalModule\Core\Request();
        $actionFactory = new \OxidEsales\PayPalModule\Model\Action\OrderActionFactory($request, $order);

        $this->assertTrue($actionFactory->createAction($action) instanceof $class);
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Action\OrderActionFactory::createAction
     * Testing action object creation with incorrect actions
     */
    public function testCreateActionWithInvalidData()
    {
        $this->expectException(PayPalInvalidActionException::class);

        $order = oxNew(\OxidEsales\PayPalModule\Model\Order::class);
        $request = new \OxidEsales\PayPalModule\Core\Request();
        $actionFactory = new \OxidEsales\PayPalModule\Model\Action\OrderActionFactory($request, $order);

        $actionFactory->createAction('some_non_existing_action');
    }
}