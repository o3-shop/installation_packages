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
 * Testing \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal class.
 */
class ResponseDoVerifyWithPayPalTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Returns response data
     *
     * @return array
     */
    public function providerResponseData()
    {
        return array(
            array(
                array(
                    'VERIFIED'       => true,
                    'receiver_email' => 'some@oxid-esales.com',
                    'test_ipn'       => true,
                    'payment_status' => 'refund',
                    'txn_id'         => '153d4fd1s',
                    'mc_gross'       => 20.55,
                    'mc_currency'    => 'EUR',
                ),
                array(
                    'ack'            => true,
                    'receiver_email' => 'some@oxid-esales.com',
                    'test_ipn'       => true,
                    'payment_status' => 'refund',
                    'transaction_id' => '153d4fd1s',
                    'amount'         => 20.55,
                    'currency'       => 'EUR',
                )
            ),
            array(
                array(
                    'Not-VERIFIED'   => true,
                    'receiver_email' => 'someone@oxid-esales.com',
                    'test_ipn'       => false,
                    'payment_status' => 'completed',
                    'txn_id'         => '454asd4as46d4',
                    'mc_gross'       => 124.55,
                    'mc_currency'    => 'USD',
                ),
                array(
                    'ack'            => false,
                    'receiver_email' => 'someone@oxid-esales.com',
                    'test_ipn'       => false,
                    'payment_status' => 'completed',
                    'transaction_id' => '454asd4as46d4',
                    'amount'         => 124.55,
                    'currency'       => 'USD',
                )
            ),
        );
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testIsPayPalAck($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['ack'], $response->isPayPalAck());
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testGetReceiverEmail($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['receiver_email'], $response->getReceiverEmail());
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testGetPaymentStatus($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['payment_status'], $response->getPaymentStatus());
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testGetTransactionId($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['transaction_id'], $response->getTransactionId());
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testGetCurrency($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['currency'], $response->getCurrency());
    }

    /**
     * @dataProvider providerResponseData
     */
    public function testGetAmount($dataResponse, $dataExpect)
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseDoVerifyWithPayPal();
        $response->setData($dataResponse);
        $this->assertEquals($dataExpect['amount'], $response->getAmount());
    }
}
