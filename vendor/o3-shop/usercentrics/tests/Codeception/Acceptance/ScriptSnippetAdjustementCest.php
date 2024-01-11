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

use OxidEsales\Codeception\Page\Home;
use OxidEsales\Codeception\Step\Basket as BasketSteps;
use OxidProfessionalServices\Usercentrics\DataObject\Configuration;
use OxidProfessionalServices\Usercentrics\DataObject\ScriptSnippet;
use OxidProfessionalServices\Usercentrics\DataObject\Service;
use OxidProfessionalServices\Usercentrics\Tests\Codeception\AcceptanceTester;
use OxidProfessionalServices\Usercentrics\Tests\Codeception\Module\Config;

final class ScriptSnippetAdjustementCest extends BaseCest
{
    /**
     * @param AcceptanceTester $I
     * @group usercentrics
     */
    public function scriptIncludeDecorated(AcceptanceTester $I, Config $configModule)
    {
        $homePage = new Home($I);
        $I->amOnPage($homePage->URL);

        $basketSteps = new BasketSteps($I);
        $basketSteps->addProductToBasketAndOpenBasket('dc5ffdf380e15674b56dd562a7cb6aec', 1);

        $value = $I->grabAttributeFrom("//script[@data-oxid][1]", "data-oxid");
        $this->prepareSpecialConfiguration($configModule, $value);
        $I->reloadPage();

        $I->waitForElement("//script[@data-oxid='{$value}'][@data-usercentrics='testcustomservice'][@type='text/plain']");

        // Accept cookie policy
        $this->waitForUserCentrics($I, true);

        $I->waitForElement("//script[@data-oxid='{$value}'][@type='text/javascript']");
    }

    /**
     * Prepare some test configuration before tests
     */
    protected function prepareSpecialConfiguration(Config $configModule, string $value): void
    {
        $config = new Configuration(
            [ //services
                new Service('testcustomservice', 'testcustomservice')
            ],
            [],
            [ //snippets
                new ScriptSnippet($value, 'testcustomservice')
            ]
        );
        $configModule->putConfiguration($config);
    }
}
