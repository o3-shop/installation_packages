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

declare(strict_types=1);

namespace OxidProfessionalServices\Usercentrics\Tests\Codeception\Acceptance;

use OxidProfessionalServices\Usercentrics\Tests\Codeception\AcceptanceTester;
use OxidProfessionalServices\Usercentrics\Tests\Codeception\Module\Config;

abstract class BaseCest
{
    private $configBackup;

    public function _before(AcceptanceTester $I, Config $configModule): void
    {
        $this->configBackup = $configModule->getConfiguration();
        $this->prepareConfiguration($configModule);

        $I->saveShopConfVar('string', 'usercentricsId', '3j0TmWxNS', 1, 'module:oxps_usercentrics');
        $I->saveShopConfVar('string', 'usercentricsMode', 'CmpV2', 1, 'module:oxps_usercentrics');
        $I->saveShopConfVar('bool', 'developmentAutomaticConsent', false, 1, 'module:oxps_usercentrics');

        $I->clearShopCache();
    }

    public function _after(AcceptanceTester $I, Config $configModule): void
    {
        $configModule->putConfiguration($this->configBackup);

        $I->saveShopConfVar('string', 'usercentricsId', '', 1, 'module:oxps_usercentrics');
    }

    /**
     * Prepare some test configuration before tests
     */
    protected function prepareConfiguration(Config $configModule)
    {
    }

    protected function waitForUserCentrics($I, $accept = false)
    {
        $I->waitForElement("#usercentrics-root", 10);
        $I->waitForJS("return typeof UC_UI !== 'undefined' && UC_UI !== null && UC_UI.isInitialized()");

        if ($accept) {
            $I->executeJS("UC_UI.acceptAllConsents() && UC_UI.restartCMP()");
        }
    }
}
