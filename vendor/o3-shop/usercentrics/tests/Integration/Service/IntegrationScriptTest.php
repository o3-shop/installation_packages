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
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern\CmpV1;
use OxidProfessionalServices\Usercentrics\Service\IntegrationScriptInterface;
use OxidProfessionalServices\Usercentrics\Tests\Unit\UnitTestCase;

/**
 * Class RendererTest
 * @covers \OxidProfessionalServices\Usercentrics\Service\IntegrationScript
 */
class IntegrationScriptTest extends UnitTestCase
{
    use ContainerTrait;

    public function testWhiteListedScript(): void
    {
        $config = Registry::getConfig();
        /** @psalm-suppress InvalidScalarArgument fails because of wrong typehint in used oxid version */
        $config->saveShopConfVar(
            'string',
            'usercentricsId',
            'SomeId',
            1,
            'module:oxps_usercentrics'
        );
        $config->saveShopConfVar(
            'string',
            'usercentricsMode',
            CmpV1::VERSION_NAME,
            1,
            'module:oxps_usercentrics'
        );

        /** @var IntegrationScriptInterface $integrationScript */
        $integrationScript = $this->get(IntegrationScriptInterface::class);
        $script = $integrationScript->getIntegrationScript();

        $this->assertHtmlEquals(
            '<script type="application/javascript" 
            src="https://app.usercentrics.eu/latest/main.js" 
            id="SomeId"></script>',
            $script
        );
    }
}
