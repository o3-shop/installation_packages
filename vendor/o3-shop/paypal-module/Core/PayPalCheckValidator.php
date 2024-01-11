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

namespace OxidEsales\PayPalModule\Core;

/**
 * Validates changed basket amount. Checks if it is bigger than previous price.
 * Than returns false to recheck new basket amount in PayPal.
 */
class PayPalCheckValidator
{
    /**
     * Basket new amount
     *
     * @var double
     */
    protected $newBasketAmount = null;

    /**
     * Basket old amount
     *
     * @var double
     */
    protected $oldBasketAmount = null;

    /**
     * Returns if order should be rechecked by PayPal
     *
     * @return bool
     */
    public function isPayPalCheckValid()
    {
        $newBasketAmount = $this->getNewBasketAmount();
        $prevBasketAmount = $this->getOldBasketAmount();
        // check only if new price is different and bigger than old price
        if ($newBasketAmount > $prevBasketAmount) {
            return false;
        }

        return true;
    }

    /**
     * Sets new basket amount
     *
     * @param double $newBasketAmount changed basket amount
     */
    public function setNewBasketAmount($newBasketAmount)
    {
        $this->newBasketAmount = $newBasketAmount;
    }

    /**
     * Returns new basket amount
     *
     * @return double
     */
    public function getNewBasketAmount()
    {
        return (float) $this->newBasketAmount;
    }

    /**
     * Sets old basket amount
     *
     * @param double $oldBasketAmount old basket amount
     */
    public function setOldBasketAmount($oldBasketAmount)
    {
        $this->oldBasketAmount = $oldBasketAmount;
    }

    /**
     * Returns old basket amount
     *
     * @return double
     */
    public function getOldBasketAmount()
    {
        return (float) $this->oldBasketAmount;
    }
}
