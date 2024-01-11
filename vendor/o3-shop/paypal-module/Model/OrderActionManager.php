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

namespace OxidEsales\PayPalModule\Model;

/**
 * PayPal order action manager class
 */
class OrderActionManager
{
    /**
     * States related with transaction mode
     *
     * @var \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    protected $availableActions = array(
        'Sale'          => array(),
        'Authorization' => array('capture', 'reauthorize', 'void'),
    );

    /**
     * Order object
     *
     * @var \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    protected $order = null;

    /**
     * Sets order
     *
     * @param \OxidEsales\PayPalModule\Model\PayPalOrder $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Returns order
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Return state for given transaction mode
     *
     * @param string $mode transaction mode
     *
     * @return array
     */
    protected function getAvailableAction($mode)
    {
        $actions = $this->availableActions[$mode];

        return $actions ? $actions : array();
    }

    /**
     * Checks whether action is available for given order
     *
     * @param string $action
     *
     * @return bool
     */
    public function isActionAvailable($action)
    {
        $order = $this->getOrder();

        $availableActions = $this->getAvailableAction($order->getTransactionMode());

        $isAvailable = in_array($action, $availableActions);

        if ($isAvailable) {
            $isAvailable = false;

            switch ($action) {
                case 'capture':
                case 'reauthorize':
                case 'void':
                    if ($order->getRemainingOrderSum() > 0 && $order->getVoidedAmount() < $order->getRemainingOrderSum()) {
                        $isAvailable = true;
                    }
                    break;
            }
        }

        return $isAvailable;
    }
}
