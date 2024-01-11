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

namespace OxidEsales\PayPalModule\Tests\Unit\Model;

/**
 * Testing oePayPalOxState class.
 */
class OrderActionManagerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Data provider for testIsActionAvailable()
     *
     * @return array
     */
    public function isActionAvailableTest_dataProvider()
    {
        return array(
            array('Sale', 'capture', 1000, 0, 0, 0, false),
            array('Sale', 'reauthorize', 1000, 0, 0, 0, false),
            array('Sale', 'void', 1000, 0, 0, 0, false),
            array('Sale', 'refund', 1000, 0, 0, 0, false),
            array('Authorization', 'capture', 1000, 999.99, 0, 0, true),
            array('Authorization', 'capture', 1000, 900, 1200, 0, true),
            array('Authorization', 'capture', 1000, 1000, 0, 0, false),
            array('Authorization', 'capture', 1000, 1100, 0, 0, false),
            array('Authorization', 'capture', 1000, 0, 0, 1000, false),
            array('Authorization', 'reauthorize', 1000, 999.99, 0, 0, true),
            array('Authorization', 'reauthorize', 1000, 900, 1200, 0, true),
            array('Authorization', 'reauthorize', 1000, 1000, 0, 0, false),
            array('Authorization', 'reauthorize', 1000, 1100, 0, 0, false),
            array('Authorization', 'reauthorize', 1000, 0, 0, 1000, false),
            array('Authorization', 'void', 1000, 999.99, 0, 0, true),
            array('Authorization', 'void', 1000, 900, 1200, 0, true),
            array('Authorization', 'void', 1000, 1000, 0, 0, false),
            array('Authorization', 'void', 1000, 1100, 0, 0, false),
            array('Authorization', 'void', 1000, 0, 0, 1000, false),
            array('Authorization', 'refund', 1000, 0, 0, 0, false),
            array('TestMode', 'testAction', -50, -5, 9999, 9999, false),
        );
    }

    /**
     * Tests isPayPalActionValid
     *
     * @dataProvider isActionAvailableTest_dataProvider
     */
    public function testIsActionAvailable($transactionMode, $action, $total, $captured, $refunded, $voided, $isValid)
    {
        $order = new \OxidEsales\PayPalModule\Model\PayPalOrder();

        $order->setTotalOrderSum($total);
        $order->setCapturedAmount($captured);
        $order->setRefundedAmount($refunded);
        $order->setVoidedAmount($voided);
        $order->setTransactionMode($transactionMode);

        $actionManager = new \OxidEsales\PayPalModule\Model\OrderActionManager();
        $actionManager->setOrder($order);

        $this->assertEquals($isValid, $actionManager->isActionAvailable($action));
    }
}
