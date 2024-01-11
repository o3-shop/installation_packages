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
 * Testing oePayPalIPNRequest class.
 */
class IPNRequestPaymentSetterTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function providerGetRequestOrderPayment()
    {
        return array(
            'capture' => array(
                array(
                    'payment_status' => 'Completed',
                    'txn_id'         => 'a2s12as1d2',
                    'receiver_email' => 'test@oxid-esales.com',
                    'mc_gross'       => 15.66,
                    'mc_currency'    => 'EUR',
                    'ipn_track_id'   => 'corrxxx',
                    'payment_date'   => '00:54:36 Jun 03, 2015 PDT',
                ),
                'capture'
            ),
            'nothing' => array(null, ''),
            'refund'  => array(
                array(
                    'payment_status' => 'Refunded',
                    'txn_id'         => 'a2s12as1dxxx',
                    'receiver_email' => 'test@oxid-esales.com',
                    'mc_gross'       => -6.66,
                    'mc_currency'    => 'EUR',
                    'correlation_id' => 'corryyy',
                    'payment_date'   => '00:54:36 Jun 03, 2015 PDT',
                ),
                'refund'
            ),
        );
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::getRequestOrderPayment
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::setRequestOrderPayment
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::_prepareOrderPayment
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::getRequest
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::getAction
     * Test case for \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter::getAmount
     *
     * @param array  $params        parameters for POST imitating PayPal.
     * @param string $expectedAction Expected action for resulting payment.
     *
     * @dataProvider providerGetRequestOrderPayment
     */
    public function testGetRequestOrderPayment($params, $expectedAction)
    {
        $payPalExpectedPayment = new \OxidEsales\PayPalModule\Model\OrderPayment();
        if (!empty($params)) {
            $payPalExpectedPayment->setStatus($params['payment_status']);
            $payPalExpectedPayment->setTransactionId($params['txn_id']);
            $payPalExpectedPayment->setCurrency($params['mc_currency']);
            $payPalExpectedPayment->setAmount(abs($params['mc_gross']));
            $payPalExpectedPayment->setAction($expectedAction);

            $correlationId = empty($params['correlation_id']) ? $params['ipn_track_id'] :$params['correlation_id'];
            $payPalExpectedPayment->setCorrelationId($correlationId);
            $payPalExpectedPayment->setDate(date('Y-m-d H:i:s', strtotime($params['payment_date'])));

        } else {
            $payPalExpectedPayment->setStatus(null);
            $payPalExpectedPayment->setTransactionId(null);
            $payPalExpectedPayment->setCurrency(null);
            $payPalExpectedPayment->setAmount(null);
            $payPalExpectedPayment->setCorrelationId(null);
            $payPalExpectedPayment->setDate(null);
            $payPalExpectedPayment->setAction('capture');
        }

        $_POST = $params;
        $request = new \OxidEsales\PayPalModule\Core\Request();
        $payPalPayment = new \OxidEsales\PayPalModule\Model\OrderPayment();

        $payPalIPNRequestSetter = new \OxidEsales\PayPalModule\Model\IPNRequestPaymentSetter();
        $payPalIPNRequestSetter->setRequest($request);
        $payPalIPNRequestSetter->setRequestOrderPayment($payPalPayment);
        $requestOrderPayment = $payPalIPNRequestSetter->getRequestOrderPayment();

        $this->assertEquals($payPalExpectedPayment, $requestOrderPayment, 'Payment object do not have request parameters.');
    }

}
