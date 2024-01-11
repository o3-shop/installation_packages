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

use OxidProfessionalServices\Usercentrics\DataObject\ScriptSnippet;
use OxidProfessionalServices\Usercentrics\Service\Configuration\ConfigurationDao;
use OxidProfessionalServices\Usercentrics\DataObject\Configuration;
use OxidProfessionalServices\Usercentrics\DataObject\Script;
use OxidProfessionalServices\Usercentrics\DataObject\Service;
use OxidProfessionalServices\Usercentrics\Tests\Unit\UnitTestCase;

/**
 * Class ConfigTest
 * @package OxidProfessionalServices\Usercentrics\Tests\Integration\Service
 * @psalm-suppress PropertyNotSetInConstructor
 * @covers \OxidProfessionalServices\Usercentrics\Service\Configuration\ConfigurationDao
 */
class ConfigTest extends UnitTestCase
{
    public function testConfigPut(): void
    {
        $directory = $this->getVirtualStructurePath();
        $file = 'ConfigPutTest.yaml';

        $sut = new ConfigurationDao($this->getStorage($file, $directory));

        $services = [new Service('name', 'TestServiceId')];
        $scripts = [new Script('test.js', 'TestServiceId')];
        $snippets = [new ScriptSnippet('123', 'TestServiceId')];
        $configuration = new Configuration($services, $scripts, $snippets);

        $sut->putConfiguration($configuration);

        $this->assertFileEquals(
            __DIR__ . '/ConfigTestData/ConfigPutTest.yaml',
            $directory . DIRECTORY_SEPARATOR . $file
        );
    }

    public function testConfigGet(): void
    {
        $file = 'ConfigReadTest.yaml';

        $sut = new ConfigurationDao($this->getStorage($file, __DIR__ . '/ConfigTestData/'));

        $configuration = $sut->getConfiguration();

        $oneService = $configuration->getServices()['TestService1Id'];
        $this->assertEquals("TestService1Id", $oneService->getId());
        $this->assertEquals("name1", $oneService->getName());

        $oneScript = $configuration->getScripts()[0];
        $this->assertEquals("test1.js", $oneScript->getPath());
        $this->assertEquals("TestService1Id", $oneScript->getServiceId());
    }
}
