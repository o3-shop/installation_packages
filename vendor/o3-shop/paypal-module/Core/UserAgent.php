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
 * Class for User Agent.
 *
 * @package core
 */
class UserAgent
{
    /**
     * Detected device type
     *
     * @var string
     */
    protected $deviceType = null;

    /**
     * Mobile device types.
     *
     * @var string
     */
    protected $mobileDevicesTypes = 'iphone|ipod|android|webos|htc|fennec|iemobile|blackberry|symbianos|opera mobi';

    /**
     * Function returns all supported mobile devices types.
     *
     * @return string
     */
    public function getMobileDeviceTypes()
    {
        return $this->mobileDevicesTypes;
    }

    /**
     * Returns device type: mobile | desktop.
     *
     * @return string
     */
    public function getDeviceType()
    {
        if ($this->deviceType === null) {
            $this->setDeviceType($this->detectDeviceType());
        }

        return $this->deviceType;
    }

    /**
     * Set device type.
     *
     * @param string $deviceType
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
    }

    /**
     * Set mobile device types.
     *
     * @param string $mobileDeviceTypes
     */
    public function setMobileDeviceTypes($mobileDeviceTypes)
    {
        $this->mobileDevicesTypes = $mobileDeviceTypes;
    }

    /**
     * Detects device type from global variable. Device types: mobile, desktop.
     *
     * @return string
     */
    protected function detectDeviceType()
    {
        $deviceType = 'desktop';
        if (preg_match('/(' . $this->getMobileDeviceTypes() . ')/is', $_SERVER['HTTP_USER_AGENT'])) {
            $deviceType = 'mobile';
        }

        return $deviceType;
    }
}
