<?php

/**
 * This file is part of O3-Shop.
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

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Config\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Config\DataObject\ShopConfigurationSetting;
use OxidEsales\EshopCommunity\Internal\Framework\Dao\EntryDoesNotExistDaoException;

interface ShopConfigurationSettingDaoInterface
{
    /**
     * @param ShopConfigurationSetting $shopConfigurationSetting
     */
    public function save(ShopConfigurationSetting $shopConfigurationSetting);

    /**
     * @param string $name
     * @param int    $shopId
     * @return ShopConfigurationSetting
     *
     * @throws EntryDoesNotExistDaoException
     */
    public function get(string $name, int $shopId): ShopConfigurationSetting;

    /**
     * @param ShopConfigurationSetting $setting
     */
    public function delete(ShopConfigurationSetting $setting);
}
