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
 * PayPal order action factory class
 */
class OrderActionFactory
{
    /**
     * @var \OxidEsales\PayPalModule\Core\Request
     */
    protected $_oRequest = null;

    /**
     * @var \OxidEsales\PayPalModule\Model\Order
     */
    protected $order = null;

    /**
     * Sets dependencies
     *
     * @param \OxidEsales\PayPalModule\Core\Request $request
     * @param \OxidEsales\PayPalModule\Model\Order  $order
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
     * Returns Order object
     *
     * @return \OxidEsales\PayPalModule\Model\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Creates action object by given action name.
     *
     * @param string $action
     *
     * @return object
     *
     * @throws \OxidEsales\PayPalModule\Core\Exception\PayPalInvalidActionException
     */
    public function createAction($action)
    {
        $method = "get" . ucfirst($action) . "Action";

        if (!method_exists($this, $method)) {
            /** @var \OxidEsales\PayPalModule\Core\Exception\PayPalInvalidActionException $exception */
            $exception = oxNew(\OxidEsales\PayPalModule\Core\Exception\PayPalInvalidActionException::class);
            throw $exception;
        }

        return $this->$method();
    }

    /**
     * Returns capture action object
     *
     * @return \OxidEsales\PayPalModule\Model\Action\OrderCaptureAction
     */
    public function getCaptureAction()
    {
        $order = $this->getOrder();
        $request = $this->getRequest();

        $data = oxNew(\OxidEsales\PayPalModule\Model\Action\Data\OrderCaptureActionData::class, $request, $order);
        $handler = oxNew(\OxidEsales\PayPalModule\Model\Action\Handler\OrderCaptureActionHandler::class, $data);

        $reauthorizeData = oxNew(\OxidEsales\PayPalModule\Model\Action\Data\OrderReauthorizeActionData::class, $request, $order);
        $reauthorizeHandler = oxNew(\OxidEsales\PayPalModule\Model\Action\Handler\OrderReauthorizeActionHandler::class, $reauthorizeData);

        $action = oxNew(\OxidEsales\PayPalModule\Model\Action\OrderCaptureAction::class, $handler, $order->getPayPalOrder(), $reauthorizeHandler);

        return $action;
    }

    /**
     * Returns refund action object
     *
     * @return \OxidEsales\PayPalModule\Model\Action\OrderRefundAction
     */
    public function getRefundAction()
    {
        $order = $this->getOrder();
        $data = oxNew(\OxidEsales\PayPalModule\Model\Action\Data\OrderRefundActionData::class, $this->getRequest(), $order);
        $handler = oxNew(\OxidEsales\PayPalModule\Model\Action\Handler\OrderRefundActionHandler::class, $data);

        $action = oxNew(\OxidEsales\PayPalModule\Model\Action\OrderRefundAction::class, $handler, $order->getPayPalOrder());

        return $action;
    }

    /**
     * Returns void action object
     *
     * @return \OxidEsales\PayPalModule\Model\Action\OrderVoidAction
     */
    public function getVoidAction()
    {
        $order = $this->getOrder();
        $data = oxNew(\OxidEsales\PayPalModule\Model\Action\Data\OrderVoidActionData::class, $this->getRequest(), $order);
        $handler = oxNew(\OxidEsales\PayPalModule\Model\Action\Handler\OrderVoidActionHandler::class, $data);

        $action = oxNew(\OxidEsales\PayPalModule\Model\Action\OrderVoidAction::class, $handler, $order->getPayPalOrder());

        return $action;
    }
}
