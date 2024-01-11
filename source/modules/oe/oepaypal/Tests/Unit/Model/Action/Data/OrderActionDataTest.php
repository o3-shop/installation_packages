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

namespace OxidEsales\PayPalModule\Tests\Unit\Model\Action\Data;

use OxidEsales\Eshop\Application\Model\Order;

/**
 * Testing \OxidEsales\PayPalModule\Model\Action\Data\OrderActionData class.
 */
class OrderActionDataTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     */
    public function testGetAuthorizationId()
    {
        $request = $this->getRequest(array());
        $order = $this->getOrder();
        $order->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field('authorizationId');

        $actionData = new \OxidEsales\PayPalModule\Model\Action\Data\OrderActionData($request, $order);

        $this->assertEquals('authorizationId', $actionData->getAuthorizationId());
    }

    /**
     */
    public function testGetAmount()
    {
        $request = $this->getRequest(array('action_comment' => 'comment'));
        $order = $this->getOrder();

        $actionData = new \OxidEsales\PayPalModule\Model\Action\Data\OrderActionData($request, $order);

        $this->assertEquals('comment', $actionData->getComment());
    }

    /**
     *  Returns Request object with given parameters
     *
     * @param $params
     *
     * @return mixed
     */
    protected function getRequest($params)
    {
        $request = $this->_createStub(\OxidEsales\PayPalModule\Core\Request::class, array('getGet' => $params));

        return $request;
    }

    /**
     *
     */
    protected function getOrder()
    {
        $order = oxNew(Order::class);

        return $order;
    }
}