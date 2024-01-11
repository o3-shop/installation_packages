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

namespace OxidEsales\PayPalModule\Model\PayPalRequest;

/**
 * PayPal request class
 */
class PayPalRequest
{
    /**
     * PayPal response data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Sets value to data by given key.
     *
     * @param string $key   Key of data value.
     * @param string $value Data value.
     */
    public function setParameter($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Returns value by given key.
     *
     * @param string $key Key of data value.
     *
     * @return string
     */
    public function getParameter($key)
    {
        return $this->data[$key];
    }

    /**
     * Set request data.
     *
     * @param array $responseData Response data from PayPal.
     */
    public function setData($responseData)
    {
        $this->data = $responseData;
    }

    /**
     * Return request data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return value from data by given key.
     *
     * @param string $key   Key of data value.
     * @param string $value Data value.
     */
    protected function setValue($key, $value)
    {
        $this->data[$key] = $value;
    }
}
