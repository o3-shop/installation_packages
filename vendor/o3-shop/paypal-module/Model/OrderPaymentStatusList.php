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
 * PayPal order payment status list class
 */
class OrderPaymentStatusList extends \OxidEsales\PayPalModule\Core\PayPalList
{
    /**
     * All available statuses
     *
     * @return array
     */
    protected $array = array(
        'completed',
        'pending',
        'canceled',
        'failed'
    );

    /**
     * Available statuses depending on action
     *
     * @var array
     */
    protected $availableStatuses = array(
        'capture'         => array('completed'),
        'capture_partial' => array('completed', 'pending'),
        'refund'          => array('completed', 'pending', 'canceled'),
        'refund_partial'  => array('completed', 'pending', 'canceled'),
        'void'            => array('completed', 'pending', 'canceled'),
    );

    /**
     * Returns the list of available statuses to choose from for admin
     *
     * @param string $action
     *
     * @return array
     */
    public function getAvailableStatuses($action)
    {
        $list = $this->availableStatuses[$action];

        return $list ? $list : array();
    }
}
