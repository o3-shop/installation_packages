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

namespace OxidEsales\Eshop\PayPalModule\Tests\Integration;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\PayPalModule\Tests\Unit\AdminUserTrait;

class OrderFinalizationTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    use AdminUserTrait;

    public function providerFinalizeOrder_TransStatusNotChange()
    {
        /** @var \OxidEsales\PayPalModule\Model\Order $order */
        $order = oxNew(Order::class);
        return array(
            array('Pending', $order::OEPAYPAL_TRANSACTION_STATUS_NOT_FINISHED),
            array('Failed', $order::OEPAYPAL_TRANSACTION_STATUS_NOT_FINISHED),
            array('Complete', $order::OEPAYPAL_TRANSACTION_STATUS_OK)
        );
    }

    /**
     * After order is finalized and PayPal order status is not 'complete',
     * order transaction status should also stay 'NOT FINISHED'.
     *
     * @dataProvider providerFinalizeOrder_TransStatusNotChange
     *
     * @param string $payPalReturnStatus
     * @param string $transactionStatus
     */
    public function testFinalizeOrder_TransactionStatus($payPalReturnStatus, $transactionStatus)
    {
        $this->getSession()->setVariable('sess_challenge', '_testOrderId');
        $this->getSession()->setVariable('paymentid', 'oxidpaypal');

        /** @var \OxidEsales\PayPalModule\Model\Basket $basket */
        $basket = oxNew(Basket::class);

        $paymentGateway = $this->getPaymentGateway($payPalReturnStatus);

        /** @var \OxidEsales\PayPalModule\Model\Order $order */
        $mockBuilder = $this->getMockBuilder(Order::class);
        $mockBuilder->setMethods(['_getGateway', '_sendOrderByEmail', 'validateOrder']);
        $order = $mockBuilder->getMock();
        $order->expects($this->any())->method('_getGateway')->will($this->returnValue($paymentGateway));

        $order->setId('_testOrderId');
        $order->finalizeOrder($basket, $this->getUser());

        $updatedOrder = oxNew(Order::class);
        $updatedOrder->load('_testOrderId');
        $this->assertEquals($transactionStatus, $updatedOrder->getFieldData('oxtransstatus'));
        $updatedOrder->delete();
    }

    /**
     * Returns Payment Gateway with mocked PayPal call. Result returns provided return status.
     *
     * @param string $payPalReturnStatus
     *
     * @return \OxidEsales\PayPalModule\Model\PaymentGateway
     */
    protected function getPaymentGateway($payPalReturnStatus)
    {
        /** @var \OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment $result */
        $result = oxNew(\OxidEsales\PayPalModule\Model\Response\ResponseDoExpressCheckoutPayment::class);
        $result->setData(array('PAYMENTINFO_0_PAYMENTSTATUS' => $payPalReturnStatus));

        /** @var \OxidEsales\PayPalModule\Core\PayPalService $service */
        $mockBuilder = $this->getMockBuilder(\OxidEsales\PayPalModule\Core\PayPalService::class);
        $mockBuilder->setMethods(['doExpressCheckoutPayment']);
        $service = $mockBuilder->getMock();
        $service->expects($this->any())->method('doExpressCheckoutPayment')->will($this->returnValue($result));

        /** @var \OxidEsales\PayPalModule\Model\PaymentGateway $payPalPaymentGateway */
        $payPalPaymentGateway = oxNew(\OxidEsales\Eshop\Application\Model\PaymentGateway::class);
        $payPalPaymentGateway->setPayPalCheckoutService($service);

        return $payPalPaymentGateway;
    }

    /**
     * @return \OxidEsales\PayPalModule\Model\User
     */
    protected function getUser()
    {
        /** @var \OxidEsales\PayPalModule\Model\User $user */
        $user = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
        $user->load('oxdefaultadmin');

        return $user;
    }
}
