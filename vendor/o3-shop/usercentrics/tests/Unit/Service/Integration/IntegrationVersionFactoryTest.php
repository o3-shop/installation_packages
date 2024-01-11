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

namespace OxidProfessionalServices\Usercentrics\Tests\Unit\Service\Integration;

use OxidProfessionalServices\Usercentrics\Exception\PatternNotFound;
use OxidProfessionalServices\Usercentrics\Service\Integration\IntegrationVersionFactory;
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern\CmpV1;
use OxidProfessionalServices\Usercentrics\Tests\Unit\UnitTestCase;

/**
 * @covers \OxidProfessionalServices\Usercentrics\Service\Integration\IntegrationVersionFactory
 */
class IntegrationVersionFactoryTest extends UnitTestCase
{
    public function testGetIntegrationScriptPattern(): void
    {
        $versionName = CmpV1::VERSION_NAME;
        $factory = new IntegrationVersionFactory();
        $patternObject = $factory->getPatternByVersion($versionName);

        $this->assertInstanceOf(CmpV1::class, $patternObject);
    }

    public function testGetNotExistingIntegrationScriptPattern(): void
    {
        $this->expectException(PatternNotFound::class);

        $versionName = 'NotExisting';
        $factory = new IntegrationVersionFactory();
        $patternObject = $factory->getPatternByVersion($versionName);
    }
}
