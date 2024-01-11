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
 * Class for splitting user name.
 *
 * @package core
 */
class FullName
{
    /** @var string  */
    private $firstName = '';

    /** @var string  */
    private $lastName = '';

    /**
     * User first name and second name.
     *
     * @param string $fullName
     */
    public function __construct($fullName)
    {
        $this->split($fullName);
    }

    /**
     * Return user first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Return user second name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Split user full name to first name and second name.
     *
     * @param string $fullName
     */
    protected function split($fullName)
    {
        $names = explode(" ", trim($fullName), 2);

        $this->firstName = trim($names[0]);
        $this->lastName = trim($names[1]);
    }
}
