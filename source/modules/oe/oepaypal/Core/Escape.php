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
 * PayPal escape class
 */
class Escape
{
    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param mixed $value value to process escaping
     *
     * @return mixed
     */
    public function escapeSpecialChars($value)
    {
        if (is_object($value)) {
            return $value;
        }

        if (is_array($value)) {
            $value = $this->escapeArraySpecialChars($value);
        } elseif (is_string($value)) {
            $value = $this->escapeStringSpecialChars($value);
        }

        return $value;
    }

    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param array $value value to process escaping
     *
     * @return array
     */
    private function escapeArraySpecialChars($value)
    {
        $newValue = array();
        foreach ($value as $key => $val) {
            $validKey = $key;
            $validKey = $this->escapeSpecialChars($validKey);
            $val = $this->escapeSpecialChars($val);
            if ($validKey != $key) {
                unset($value[$key]);
            }
            $newValue[$validKey] = $val;
        }

        return $newValue;
    }

    /**
     * Checks if passed parameter has special chars and replaces them.
     * Returns checked value.
     *
     * @param string $value value to process escaping
     *
     * @return string
     */
    private function escapeStringSpecialChars($value)
    {
        $value = str_replace(
            array('&', '<', '>', '"', "'", chr(0), '\\'),
            array('&amp;', '&lt;', '&gt;', '&quot;', '&#039;', '', '&#092;'),
            $value
        );

        return $value;
    }
}
