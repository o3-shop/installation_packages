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
 * Testing oxAccessRightException class.
 */
class OrderPaymentStatusListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /**
     * Data provider for testIsActionValidCapture()
     *
     * @return array
     */
    public function availableStatusesTest_dataProvider()
    {
        return array(
            array('capture', array('completed')),
            array('capture_partial', array('completed', 'pending')),
            array('refund', array('completed', 'pending', 'canceled')),
            array('refund_partial', array('completed', 'pending', 'canceled')),
            array('void', array('completed', 'pending', 'canceled')),
            array('test', array()),
        );
    }

    /**
     * Testing adding amount to PayPal refunded amount
     *
     * @dataProvider availableStatusesTest_dataProvider
     */
    public function testGetAvailableStatuses($action, $statusList)
    {
        $statusListProvider = new \OxidEsales\PayPalModule\Model\OrderPaymentStatusList();

        $this->assertEquals($statusList, $statusListProvider->getAvailableStatuses($action));
    }
}
