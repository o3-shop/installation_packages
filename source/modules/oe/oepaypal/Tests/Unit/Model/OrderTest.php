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

use OxidEsales\Eshop\Application\Model\Order;

/**
 * Testing oxAccessRightException class.
 */
class OrderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Tear down the fixture.
     */
    protected function tearDown(): void
    {
        $delete = 'TRUNCATE TABLE `oxorder`';
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($delete);

        $this->getSession()->setVariable('sess_challenge', null);
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Order::loadPayPalOrder()
     */
    public function testLoadPayPalOrder()
    {
        // creating order
        $order = oxNew(Order::class);
        $order->setId('_testOrderId');
        $order->save();

        // checking load from session
        $this->getSession()->setVariable('sess_challenge', '_testOrderId');
        $order = new \OxidEsales\PayPalModule\Model\Order();
        $order->loadPayPalOrder();
        $this->assertEquals('_testOrderId', $order->oxorder__oxid->value);

        // checking order creation if not exist in session order id
        $this->getSession()->setVariable('sess_challenge', null);
        $order = new \OxidEsales\PayPalModule\Model\Order();
        $order->loadPayPalOrder();
        $this->assertTrue((bool) $order->oxorder__oxid->value);
    }

    public function dataProviderFinalizePayPalOrder()
    {
        return [
            'sale'      => [
                'Sale',
                'OK',
                date('Y-m-d'),
                [
                    'PAYMENTINFO_0_TRANSACTIONID' => '_testTransactionId',
                    'PAYMENTINFO_0_PAYMENTSTATUS' => 'Completed',
                ]
            ],
            'authorize' => [
                'Authorization',
                'NOT_FINISHED',
                '0000-00-00',
                [
                    'PAYMENTINFO_0_TRANSACTIONID' => '_testTransactionId',
                    'PAYMENTINFO_0_PAYMENTSTATUS' => 'Pending',
                ]
            ]
        ];
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Order::finalizePayPalOrder()
     *
     * @dataProvider dataProviderFinalizePayPalOrder()
     *
     * @param string $transactionMode
     * @param string $expectedStatus
     * @param string $expectedPaid
     */
    public function testFinalizePayPalOrder($transactionMode, $expectedStatus, $expectedPaid, $result)
    {
        // creating order
        $order = new \OxidEsales\Eshop\Application\Model\Order();
        $order->setId('_testOrderId');
        $order->oxorder__oxtransstatus = new \OxidEsales\Eshop\Core\Field('NOT_FINISHED');
        $order->save();

        /** @var \OxidEsales\Eshop\Application\Model\Basket $basket */
        $basket = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);

        $this->getSession()->setVariable('sess_challenge', '_testOrderId');

        $order = new \OxidEsales\PayPalModule\Model\Order();
        $order->loadPayPalOrder();
        
        $details = new \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment();
        $details->setData($result);

        $order->finalizePayPalOrder($details, $basket, $transactionMode);

        $this->assertEquals($expectedStatus, $order->oxorder__oxtransstatus->value);
        $this->assertEquals('_testTransactionId', $order->oxorder__oxtransid->value);

        $this->assertEquals($expectedPaid, substr($order->oxorder__oxpaid->value, 0, 10));
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Order::finalizePayPalOrder() - when processing order with other payment method
     * (not PayPal), order status should not be changed.
     */
    public function testFinalizeOrder_notPayPalPayment()
    {
        $testOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $testOrder->setId('_testOrderId');
        $testOrder->oxorder__oxtransstatus = new \OxidEsales\Eshop\Core\Field("OK");
        $testOrder->save();

        $this->getSession()->setVariable('sess_challenge', '_testOrderId');

        /** @var \OxidEsales\Eshop\Application\Model\Basket $basket */
        $mockBuilder = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\Basket::class);
        $mockBuilder->setMethods(['getPaymentId']);
        $basket = $mockBuilder->getMock();
        $basket->expects($this->any())->method('getPaymentId')->will($this->returnValue("anotherPayment"));

        /** @var \OxidEsales\Eshop\Application\Model\User $user */
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);

        /** @var \OxidEsales\Eshop\Application\Model\Order $order */
        $order = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
        $order->setId('_testOrderId');
        $order->finalizeOrder($basket, $user);

        $updatedOrder = new \OxidEsales\Eshop\Application\Model\Order();
        $updatedOrder->load('_testOrderId');
        $this->assertEquals("OK", $updatedOrder->oxorder__oxtransstatus->value);
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\Order::deletePayPalOrder()
     */
    public function testDeletePayPalOrder()
    {
        $testOrder = new \OxidEsales\Eshop\Application\Model\Order();
        $testOrder->setId('_testOrderId');
        $testOrder->save();

        $this->getSession()->setVariable('sess_challenge', '_testOrderId');

        $order = new \OxidEsales\PayPalModule\Model\Order();
        $order->deletePayPalOrder();

        $updatedOrder = new \OxidEsales\Eshop\Application\Model\Order();
        $this->assertFalse($updatedOrder->load('_testOrderId'));
    }

    /**
     * Tests getAuthorizationId
     */
    public function testGetAuthorizationId()
    {
        $testOrder = new \OxidEsales\PayPalModule\Model\Order();
        $testOrder->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field('testAuthorizationId');

        $this->assertEquals('testAuthorizationId', $testOrder->getAuthorizationId());
    }

    /**
     *
     */
    public function testValidateDelivery_EmptyPaymentValid_PaymentValid()
    {
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Application\Model\PaymentList::class, null);

        $basket = $this->createPartialMock(\OxidEsales\PayPalModule\Model\Basket::class, ['getPaymentId', 'getShippingId']);
        $basket->method('getPaymentId')->willReturn('oxidpaypal');
        $basket->method('getShippingId')->willReturn('oxidstandard');

        $emptyPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
        $emptyPayment->load('oxempty');
        $emptyPayment->oxpayments__oxactive = new \OxidEsales\Eshop\Core\Field(1);
        $emptyPayment->save();

        $deliverySetList = $this->getMockBuilder(\OxidEsales\Eshop\Application\Model\DeliverySetList::class)
            ->setMethods(['getDeliverySetList'])
            ->getMock();
        $deliverySetList->expects($this->once())->method('getDeliverySetList')->willReturn([]);
        \OxidEsales\Eshop\Core\Registry::set(\OxidEsales\Eshop\Application\Model\DeliverySetList::class, $deliverySetList);

        /** @var \OxidEsales\PayPalModule\Model\User $user */
        $user = oxNew(\OxidEsales\PayPalModule\Model\User::class);

        $order = oxNew(\OxidEsales\PayPalModule\Model\Order::class);
        $order->setUser($user);

        $this->assertNull($order->validateDelivery($basket));
    }

    /**
     * Asserts that order is updated
     *
     */
    public function testUpdateOrderNumber()
    {
        $order = oxNew(\OxidEsales\PayPalModule\Model\Order::class);
        $order->oxorder__oxid = new \OxidEsales\Eshop\Core\Field('_test_order');
        $order->save();
        $this->assertTrue($order->oePayPalUpdateOrderNumber());
    }

    /**
     * Asserts that number is set next than existing one
     */
    public function testUpdateOrderNumber_OrderNumberNotSet()
    {
        $counterIdent = 'orderTestCounter';

        $mockBuilder = $this->getMockBuilder(\OxidEsales\PayPalModule\Model\Order::class);
        $mockBuilder->setMethods(['_getCounterIdent']);
        $order = $mockBuilder->getMock();
        $order->expects($this->any())->method('_getCounterIdent')->will($this->returnValue($counterIdent));
        $order->oxorder__oxid = new \OxidEsales\Eshop\Core\Field('_test_order');
        $order->save();

        $counter = oxNew(\OxidEsales\Eshop\Core\Counter::class);
        $orderNumber = $counter->getNext($counterIdent);

        $order->oePayPalUpdateOrderNumber();

        $this->assertEquals($orderNumber + 1, $order->oxorder__oxordernr->value);
    }

    /**
     *
     */
    public function testUpdateOrderNumber_OrderNumberSet()
    {
        $counterIdent = 'orderTestCounter';

        $mockBuilder = $this->getMockBuilder(\OxidEsales\PayPalModule\Model\Order::class);
        $mockBuilder->setMethods(['_getCounterIdent']);
        $order = $mockBuilder->getMock();
        $order->expects($this->any())->method('_getCounterIdent')->will($this->returnValue($counterIdent));

        $counter = oxNew(\OxidEsales\Eshop\Core\Counter::class);
        $counter->getNext($counterIdent);

        $order->oxorder__oxordernr = new \OxidEsales\Eshop\Core\Field(5);
        $order->oePayPalUpdateOrderNumber();

        $this->assertEquals(5, $order->oxorder__oxordernr->value);
    }
}
