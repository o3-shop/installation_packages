<?php
/**
 * This file is part of O3-Shop GDPR opt-in module.
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

namespace OxidEsales\GdprOptinModule\Tests\Integration;

/**
 * Class ContactControllerTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class ContactControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test checkbox validation.
     *
     * @dataProvider dataProviderOptInValidationRequired
     */
    public function testOptInValidationRequired($configValue, $expected)
    {
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', $configValue);

        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ContactController::class);
        $this->assertSame($expected, $controller->isOptInValidationRequired());
    }

    /**
     * @return array
     */
    public function dataProviderOptInValidationRequired()
    {
        return [
            'formMethod-deletion' => ['deletion', false],
            'formMethod-statistical' => ['statistical', true],
        ];
    }

    /**
     * Test validation error appears if needed
     */
    public function testSendError()
    {
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('OeGdprOptinContactFormMethod', "statistical");

        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ContactController::class);
        $this->assertFalse($controller->isOptInError());
        $this->assertFalse($controller->send());
        $this->assertTrue($controller->isOptInError());
    }
}
