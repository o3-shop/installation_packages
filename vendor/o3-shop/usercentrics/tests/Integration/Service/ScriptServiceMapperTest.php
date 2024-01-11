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

use OxidProfessionalServices\Usercentrics\DataObject\Service;
use OxidProfessionalServices\Usercentrics\Service\Configuration\ConfigurationDao;
use OxidProfessionalServices\Usercentrics\Service\ScriptServiceMapper;
use OxidProfessionalServices\Usercentrics\Tests\Unit\UnitTestCase;

/**
 * Class RepositoryTest
 * @package OxidProfessionalServices\Usercentrics\Tests\Integration\Service
 * @psalm-suppress PropertyNotSetInConstructor
 * @covers \OxidProfessionalServices\Usercentrics\Service\ScriptServiceMapper
 */
class ScriptServiceMapperTest extends UnitTestCase
{

    /**
     * @dataProvider notMatchingScriptUrls
     */
    public function testScriptNoNameConfigured(string $scriptUrl): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Service1.yaml');

        $service = $scriptServiceMapper->getServiceByScriptUrl($scriptUrl);
        $this->assertNull(
            $service,
            "test.js should not return a service name as its not configured"
        );
    }

    /**
     * @dataProvider matchingScriptUrls
     */
    public function testScriptNameConfigured(string $scriptUrl): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Service1.yaml');

        /** @var Service $service */
        $service = $scriptServiceMapper->getServiceByScriptUrl($scriptUrl);

        $this->assertNotNull($service);
        $this->assertEquals("name1", $service->getName());
    }

    public function matchingScriptUrls(): array
    {
        return [
            ["http://someurl/path/test1.js"],
            ["http://someurl/path/test1.js?123456"],
            ["http://someurl/path/test1.js?123456#abc"],
            ["http://someurl/path/test1.js#abc"],
            ["https://someurl/path/test2.js#abc"],
            ["https://someurl/1/test/js/path/test2.js?123456"],
        ];
    }

    public function notMatchingScriptUrls(): array
    {
        return [
            ["http://someurl/path/test.js"],
            ["http://someurl/path/test.js?123456"],
            ["http://someurl/path/test.js?123456#abc"],
            ["http://someurl/path/test.js#abc"],
            ["https://someurl/test2.js#abc"],
            ["https://someurl/js/test2.js"],
            ["https://someurl/path/js/test2.js?123456"],
        ];
    }


    public function testCalculateSnippetIdIsNotEmpty(): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Snippets.yaml');
        $snippet = "alert('Service1')";
        $id = $scriptServiceMapper->calculateSnippetId($snippet);
        $this->assertNotEmpty($id);
    }

    public function testCalculateSnippetIdIsUnique(): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Snippets.yaml');
        $snippet = "alert('Service1')";
        $id = $scriptServiceMapper->calculateSnippetId($snippet);

        $snippet2 = "alert('Service2')";
        $id2 = $scriptServiceMapper->calculateSnippetId($snippet2);
        $this->assertNotEquals($id, $id2);
    }

    public function testCalculateSnippetIdIsStable(): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Snippets.yaml');
        $snippet = "alert('Service1')";
        $id = $scriptServiceMapper->calculateSnippetId($snippet);
        $id3 = $scriptServiceMapper->calculateSnippetId($snippet);
        $this->assertEquals($id, $id3);
    }

    public function testGetServiceBySnippetId(): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Snippets.yaml');
        $id = $scriptServiceMapper->calculateSnippetId("alert('Service2')");
        $service = $scriptServiceMapper->getServiceBySnippetId($id);
        $this->assertNotNull($service);
        /** @psalm-suppress PossiblyNullReference */
        $this->assertEquals("name1", $service->getName());
    }

    public function testGetServiceByNotExistingSnippetId(): void
    {
        $scriptServiceMapper = $this->createScriptMapper('Snippets.yaml');
        $id = $scriptServiceMapper->calculateSnippetId("alert('NoService')");
        $service = $scriptServiceMapper->getServiceBySnippetId($id);
        $this->assertNull($service);
    }

    /**
     * @return ScriptServiceMapper
     */
    private function createScriptMapper(string $file): ScriptServiceMapper
    {
        $config = new ConfigurationDao($this->getStorage($file, __DIR__ . '/ConfigTestData'));
        return new ScriptServiceMapper($config);
    }
}
