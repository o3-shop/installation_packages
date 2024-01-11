<?php

/**
 * This file is part of O3-Shop Usercentrics Cookie Compliance module.
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

namespace OxidProfessionalServices\Usercentrics\Core;

use OxidProfessionalServices\Usercentrics\Service\IntegrationScriptInterface;
use OxidProfessionalServices\Usercentrics\Service\ModuleSettingsInterface;

class ViewConfig extends ViewConfig_parent
{
    public function isSmartDataProtectorActive(): bool
    {
        $moduleSettings = $this->getContainer()->get(ModuleSettingsInterface::class);
        return $moduleSettings->getSettingValue('smartDataProtectorActive', true);
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getUsercentricsID(): string
    {
        $moduleSettings = $this->getContainer()->get(ModuleSettingsInterface::class);
        return $moduleSettings->getSettingValue('usercentricsId', '');
    }

    public function getUsercentricsScript(): string
    {
        /**
         * @psalm-suppress InternalMethod
         * @var IntegrationScriptInterface $service
         */
        $service = $this->getContainer()->get(IntegrationScriptInterface::class);
        return $service->getIntegrationScript();
    }

    public function isDevelopmentAutomaticConsentActive(): bool
    {
        $moduleSettings = $this->getContainer()->get(ModuleSettingsInterface::class);
        return $moduleSettings->getSettingValue('developmentAutomaticConsent', false);
    }

    public function getSmartDataProtectorDeactivateBlockingServices(): array
    {
        $moduleSettings = $this->getContainer()->get(ModuleSettingsInterface::class);
        $value = $moduleSettings->getSettingValue('smartDataProtectorDeactivateBlocking', '');

        return array_map(function ($value) {
            return trim($value);
        }, explode(",", $value));
    }
}
