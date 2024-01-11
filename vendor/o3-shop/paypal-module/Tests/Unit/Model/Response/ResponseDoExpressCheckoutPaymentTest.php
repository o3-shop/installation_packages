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
 * Testing \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment class.
 */
class ResponseDoExpressCheckoutPaymentTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Returns response data
     *
     * @return array
     */
    protected function getResponseData()
    {
        $data = array(
            'PAYMENTINFO_0_TRANSACTIONID' => 'transactionID',
            'CORRELATIONID'               => 'correlationID',
            'PAYMENTINFO_0_PAYMENTSTATUS' => 'confirmed',
            'PAYMENTINFO_0_AMT'           => 1200,
            'PAYMENTINFO_0_CURRENCYCODE'  => 'LTL'
        );

        return $data;
    }

    /**
     * Test get transaction id
     */
    public function testGetTransactionId()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $response->setData($this->getResponseData());
        $this->assertEquals('transactionID', $response->getTransactionId());
    }

    /**
     * Test get transaction id
     */
    public function testGetCorrelationId()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $response->setData($this->getResponseData());
        $this->assertEquals('correlationID', $response->getCorrelationId());
    }

    /**
     * Test get payment status
     */
    public function testGetPaymentStatus()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $response->setData($this->getResponseData());
        $this->assertEquals('confirmed', $response->getPaymentStatus());
    }

    /**
     * Test get price amount
     */
    public function testGetAmount()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $response->setData($this->getResponseData());
        $this->assertEquals(1200, $response->getAmount());
    }

    /**
     * Test get currency code
     */
    public function testGetCurrencyCode()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $response->setData($this->getResponseData());
        $this->assertEquals('LTL', $response->getCurrencyCode());
    }
}
