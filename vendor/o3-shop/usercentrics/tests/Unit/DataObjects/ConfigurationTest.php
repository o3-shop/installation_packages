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

namespace OxidProfessionalServices\Usercentrics\Tests\Unit\DataObjects;

use OxidProfessionalServices\Usercentrics\DataObject\Configuration;
use OxidProfessionalServices\Usercentrics\DataObject\Script;
use OxidProfessionalServices\Usercentrics\DataObject\ScriptSnippet;
use OxidProfessionalServices\Usercentrics\DataObject\Service;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceTest
 * @package OxidProfessionalServices\Usercentrics\Tests\Unit
 * @covers \OxidProfessionalServices\Usercentrics\DataObject\Configuration
 */
class ConfigurationTest extends TestCase
{
    public function testHasService(): void
    {
        $service = new Service('name', 'id');
        $configuration = new Configuration(
            [$service],
            [],
            []
        );
        $services = $configuration->getServices();
        $this->assertCount(1, $services);
        $this->assertSame($service, $services[0]);
    }

    public function testHasScript(): void
    {
        $script = new Script('path', 'id');
        $configuration = new Configuration(
            [],
            [$script],
            []
        );
        $scripts = $configuration->getScripts();
        $this->assertCount(1, $scripts);
        $this->assertSame($script, $scripts[0]);
    }

    public function testHasScriptSnippets(): void
    {
        $scriptSnippet = new ScriptSnippet('123', 'id');
        $configuration = new Configuration(
            [],
            [],
            [$scriptSnippet]
        );
        $scripts = $configuration->getScriptSnippets();
        $this->assertCount(1, $scripts);
        $this->assertSame($scriptSnippet, $scripts[0]);
    }
}
