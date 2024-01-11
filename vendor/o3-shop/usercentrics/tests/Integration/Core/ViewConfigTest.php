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

namespace OxidProfessionalServices\Usercentrics\Tests\Integration\Core;

use DOMDocument;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\Usercentrics\Core\ViewConfig;
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern;

/**
 * Class ViewConfigTest
 * @covers \OxidProfessionalServices\Usercentrics\Core\ViewConfig
 */
class ViewConfigTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * @dataProvider booleanProvider
     */
    public function testSmartDataProtectorActive(bool $setting): void
    {
        $config = Registry::getConfig();
        /** @psalm-suppress InvalidScalarArgument fails because of wrong typehint in used oxid version */
        $config->saveShopConfVar(
            'bool',
            'smartDataProtectorActive',
            $setting,
            1,
            'module:oxps_usercentrics'
        );

        /** @var ViewConfig $viewConfig */
        $viewConfig = Registry::get(\OxidEsales\Eshop\Core\ViewConfig::class);
        // $this->assertInstanceOf(JavaScriptRenderer::class, $viewConfig);
        $enabled = $viewConfig->isSmartDataProtectorActive();
        $this->assertSame($setting, $enabled);
    }

    /**
     * @dataProvider dataProviderTestOutputPerMode
     */
    public function testOutputPerMode(string $mode, string $expected): void
    {
        $config = Registry::getConfig();

        /** @psalm-suppress InvalidScalarArgument fails because of wrong typehint in used oxid version */
        $config->saveShopConfVar(
            'string',
            'usercentricsMode',
            $mode,
            1,
            'module:oxps_usercentrics'
        );
        $config->saveShopConfVar(
            'string',
            'usercentricsId',
            'ABC123',
            1,
            'module:oxps_usercentrics'
        );

        /** @var ViewConfig $viewConfig */
        $viewConfig = Registry::get(\OxidEsales\Eshop\Core\ViewConfig::class);
        $html = $viewConfig->getUsercentricsScript();
        $this->assertHtmlEquals($expected, $html);
    }

    public function dataProviderTestOutputPerMode(): array
    {
        return [
            [
                Pattern\CmpV2Tcf::VERSION_NAME,
                '<script id="usercentrics-cmp"
                    data-settings-id="ABC123"
                    src="https://app.usercentrics.eu/browser-ui/latest/bundle.js"
                    data-tcf-enabled
                    defer></script>'
            ],
            [
                Pattern\CmpV2TcfLegacy::VERSION_NAME,
                '<script id="usercentrics-cmp"
                    data-settings-id="ABC123"
                    src="https://app.usercentrics.eu/browser-ui/latest/bundle_legacy.js"
                    data-tcf-enabled
                    defer></script>'
            ],
            [
                Pattern\CmpV2Legacy::VERSION_NAME,
                '<script id="usercentrics-cmp"
                    data-settings-id="ABC123"
                    src="https://app.usercentrics.eu/browser-ui/latest/bundle_legacy.js"
                    defer></script>'
            ],
            [
                Pattern\CmpV2::VERSION_NAME,
                '<script id="usercentrics-cmp"
                    data-settings-id="ABC123"
                    src="https://app.usercentrics.eu/browser-ui/latest/bundle.js"
                    defer></script>'
            ],
            [
                Pattern\CmpV1::VERSION_NAME,
                '<script type="application/javascript" 
                    src="https://app.usercentrics.eu/latest/main.js" 
                    id="ABC123" ></script>'
            ]
        ];
    }

    public function assertHtmlEquals(string $expected, string $actual): void
    {
        $eDom = new DOMDocument();
        $eDom->loadHTML($expected, LIBXML_HTML_NOIMPLIED);

        $aDom = new DOMDocument();
        $aDom->loadHTML($actual, LIBXML_HTML_NOIMPLIED);

        $this->assertXmlStringEqualsXmlString($eDom, $aDom);
    }

    public function testNoUsercentricsScriptInCustomMode(): void
    {
        $config = Registry::getConfig();
        /** @psalm-suppress InvalidScalarArgument fails because of wrong typehint in used oxid version */
        $config->saveShopConfVar(
            'string',
            'usercentricsMode',
            Pattern\Custom::VERSION_NAME,
            1,
            'module:oxps_usercentrics'
        );

        /** @var ViewConfig $viewConfig */
        $viewConfig = Registry::get(\OxidEsales\Eshop\Core\ViewConfig::class);
        $html = $viewConfig->getUsercentricsScript();
        $this->assertEmpty($html);
    }

    public function booleanProvider(): array
    {
        return [
            [true],
            [false]
        ];
    }
}
