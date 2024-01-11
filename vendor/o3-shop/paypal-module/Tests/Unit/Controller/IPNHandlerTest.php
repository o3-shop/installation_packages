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
namespace OxidEsales\PayPalModule\Tests\Unit\Controller;


/**
 * Testing \OxidEsales\PayPalModule\Controller\IPNHandler class.
 */
class IPNHandlerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetPayPalRequest()
    {
        $payPalHandler = new \OxidEsales\PayPalModule\Controller\IPNHandler();
        $payPalRequest = $payPalHandler->getPayPalRequest();
        $this->assertTrue(is_a($payPalRequest, \OxidEsales\PayPalModule\Core\Request::class));
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Controller\IPNHandler::handleRequest()
     * Handler should return false if called without PayPal request data.
     */
    public function testHandleRequest_emptyData_false()
    {
        $payPalIPNHandler = new \OxidEsales\PayPalModule\Controller\IPNHandler();
        $payPalIPNHandler->handleRequest();

        $logHelper = new \OxidEsales\PayPalModule\Tests\Unit\PayPalLogHelper();
        $logData = $logHelper->getLogData();
        $lastLogItem = end($logData);
        $requestHandled = $lastLogItem->data['Result'] == 'true';

        $this->assertEquals(false, $requestHandled);
    }
}
