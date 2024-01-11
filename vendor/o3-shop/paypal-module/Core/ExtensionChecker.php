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
 * PayPal Extension checker, check if extension is active on shop/sub-shop
 */
class ExtensionChecker
{
    /**
     * Shop id
     *
     * @var string
     */
    protected $shopId = null;

    /**
     * Extension id
     *
     * @var string
     */
    protected $extensionId = '';

    /**
     * Set shop id
     *
     * @param string $shopId shop id
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * Return shop id
     *
     * @return string
     */
    public function getShopId()
    {
        if (is_null($this->shopId)) {
            $this->setShopId(\OxidEsales\Eshop\Core\Registry::getConfig()->getShopId());
        }

        return $this->shopId;
    }

    /**
     * Set extension id
     *
     * @param string $extensionId extension id
     */
    public function setExtensionId($extensionId)
    {
        $this->extensionId = $extensionId;
    }

    /**
     * Return extension id
     *
     * @return string
     */
    public function getExtensionId()
    {
        return $this->extensionId;
    }

    /**
     * Return return extended classes array
     *
     * @return array
     */
    protected function getExtendedClasses()
    {
        return $this->getConfigValue('aModules');
    }

    /**
     * Return disabled modules array
     *
     * @return array
     */
    protected function getDisabledModules()
    {
        return $this->getConfigValue('aDisabledModules');
    }

    /**
     * Return config value
     *
     * @param string $configName - config parameter name were stored arrays od extended classes
     *
     * @return array
     */
    protected function getConfigValue($configName)
    {
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();

        $select = "SELECT `oxvarvalue` " .
                   "FROM `oxconfig` WHERE `oxvarname` = " . $db->quote($configName) . " AND `oxshopid` = " . $db->quote($this->getShopId());

        return unserialize($db->getOne($select));
    }

    /**
     * Check if module is active.
     *
     * @return  bool
     */
    public function isActive()
    {
        $moduleId = $this->getExtensionId();
        $moduleIsActive = false;

        $modules = $this->getExtendedClasses();

        if (is_array($modules)) {
            // Check if module was ever installed.
            $moduleExists = false;
            foreach ($modules as $extendPath) {
                if (false !== strpos($extendPath, '/' . $moduleId . '/')) {
                    $moduleExists = true;
                    break;
                }
            }

            // If module exists, check if it is not disabled.
            if ($moduleExists) {
                $disabledModules = $this->getDisabledModules();
                if (!(is_array($disabledModules) && in_array($moduleId, $disabledModules))) {
                    $moduleIsActive = true;
                }
            }
        }

        return $moduleIsActive;
    }
}
