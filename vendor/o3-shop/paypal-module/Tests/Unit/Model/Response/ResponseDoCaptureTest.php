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

namespace OxidEsales\PayPalModule\Tests\Unit\Model\Response;

/**
 * Testing oxAccessRightException class.
 */
class ResponseDoCaptureTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Returns response data
     *
     * @return array
     */
    protected function getResponseData()
    {
        $data = array(
            'TRANSACTIONID' => 'transactionId',
            'CORRELATIONID' => 'correlationId',
            'PAYMENTSTATUS' => 'completed',
            'AMT'           => 12.45,
            'CURRENCYCODE'  => 'LTL'
        );

        return $data;
    }

    /**
     * Test get authorization id
     */
    public function testGetTransactionId()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoCapture();
        $response->setData($this->getResponseData());
        $this->assertEquals('transactionId', $response->getTransactionId());
    }

    /**
     * Test get correlation id
     */
    public function testGetCorrelationId()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoCapture();
        $response->setData($this->getResponseData());
        $this->assertEquals('correlationId', $response->getCorrelationId());
    }

    /**
     * Test get payment status
     */
    public function testGetPaymentStatus()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoCapture();
        $response->setData($this->getResponseData());
        $this->assertEquals('completed', $response->getPaymentStatus());
    }

    /**
     * Test get payment status
     */
    public function testGetCaptureAmount()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoCapture();
        $response->setData($this->getResponseData());
        $this->assertEquals(12.45, $response->getCapturedAmount());
    }

    /**
     * Test get payment status
     */
    public function testGetCurrency()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoCapture();
        $response->setData($this->getResponseData());
        $this->assertEquals('LTL', $response->getCurrency());
    }
}
