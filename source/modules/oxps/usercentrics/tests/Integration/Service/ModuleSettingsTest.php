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

namespace OxidProfessionalServices\Usercentrics\Tests\Integration\Service;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;
use OxidProfessionalServices\Usercentrics\Service\ModuleSettingsInterface;
use OxidProfessionalServices\Usercentrics\Tests\Unit\UnitTestCase;

/**
 * Class RendererTest
 * @covers \OxidProfessionalServices\Usercentrics\Service\ModuleSettings
 */
class ModuleSettingsTest extends UnitTestCase
{
    use ContainerTrait;

    public function testGetSettingValue(): void
    {
        $config = Registry::getConfig();
        $config->saveShopConfVar(
            'str',
            'specialVarName',
            'someValue',
            1,
            'module:oxps_usercentrics'
        );

        /** @var ModuleSettingsInterface $integrationScript */
        $moduleSettings = $this->get(ModuleSettingsInterface::class);
        $value = $moduleSettings->getSettingValue('specialVarName');

        $this->assertEquals('someValue', $value);
    }

    public function testGetSettingValueGivesNullOnMissingSetting(): void
    {
        /** @var ModuleSettingsInterface $integrationScript */
        $moduleSettings = $this->get(ModuleSettingsInterface::class);
        $value = $moduleSettings->getSettingValue('missingSetting');

        $this->assertNull($value);
    }

    public function testGetSettingValueGivesSpecificDefaultOnFail(): void
    {
        /** @var ModuleSettingsInterface $integrationScript */
        $moduleSettings = $this->get(ModuleSettingsInterface::class);
        $value = $moduleSettings->getSettingValue('missingSetting', 'special');

        $this->assertSame('special', $value);
    }
}
