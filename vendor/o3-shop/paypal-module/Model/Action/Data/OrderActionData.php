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

namespace OxidEsales\PayPalModule\Model\Action\Data;

/**
 * PayPal order action factory class
 */
class OrderActionData
{
    /**
     * Request object
     *
     * @var \OxidEsales\PayPalModule\Core\Request
     */
    protected $request = null;

    /**
     * Order object
     *
     * @var \OxidEsales\PayPalModule\Model\Order
     */
    protected $order = null;

    /**
     * Sets dependencies.
     *
     * @param \OxidEsales\PayPalModule\Core\Request      $request
     * @param \OxidEsales\PayPalModule\Model\PayPalOrder $order
     */
    public function __construct($request, $order)
    {
        $this->request = $request;
        $this->order = $order;
    }

    /**
     * Returns Request object
     *
     * @return \OxidEsales\PayPalModule\Core\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns PayPal Order object
     *
     * @return \OxidEsales\PayPalModule\Model\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * returns action amount
     *
     * @return string
     */
    public function getAuthorizationId()
    {
        return $this->getOrder()->oxorder__oxtransid->value;
    }

    /**
     * returns comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->getRequest()->getRequestParameter('action_comment');
    }

    /**
     * Returns order status
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->getRequest()->getRequestParameter('order_status');
    }
}
