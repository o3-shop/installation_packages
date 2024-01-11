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

namespace OxidEsales\PayPalModule\Model\Action;

/**
 * PayPal order action class
 */
abstract class OrderAction
{
    /**
     *
     * @var \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    protected $_oOrder = null;

    /**
     * @var string
     */
    protected $orderStatus = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler
     */
    protected $handler = null;

    /**
     * Sets handler and order.
     *
     * @param \OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler $handler
     * @param \OxidEsales\PayPalModule\Model\PayPalOrder                              $order
     */
    public function __construct($handler, $order)
    {
        $this->handler = $handler;
        $this->order = $order;
    }

    /**
     * Returns \OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler object.
     *
     * @return \OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Returns \OxidEsales\PayPalModule\Model\PayPalOrder object.
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Returns formatted date
     *
     * @return string
     */
    public function getDate()
    {
        $utilsDate = \OxidEsales\Eshop\Core\Registry::getUtilsDate();

        return date('Y-m-d H:i:s', $utilsDate->getTime());
    }

    /**
     * Processes PayPal action
     */
    abstract public function process();
}
