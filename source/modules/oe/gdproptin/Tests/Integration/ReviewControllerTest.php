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

use OxidEsales\GdprOptinModule\Controller\ReviewController;

/**
 * Class ReviewControllerTest
 *
 * @package OxidEsales\GdprOptinModule\Tests\Integration
 */
class ReviewControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test validation error appears if needed
     */
    public function testSendError()
    {
        /** @var ReviewController $controller */
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, true);
        $this->assertFalse($controller->saveReview());
    }

    /**
     * Test validation error appears if needed
     */
    public function testSendNotError()
    {
        /** @var ReviewController $controller */
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, false);
        $this->assertNull($controller->saveReview());
    }

    /**
     * Test if validation is required.
     *
     * @dataProvider dataProviderReviewOptInValidationRequired
     */
    public function testReviewOptInValidationRequired($configValue, $expected)
    {
        /** @var ReviewController $controller */
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->assertSame($expected, $controller->isReviewOptInValidationRequired());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInValidationRequired()
    {
        return [
            'required' => [true, true],
            'not-required' => [false, false]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderValidateOptIn
     */
    public function testValidateOptIn($configValue, $checkboxStatus, $expectedValue)
    {
        /** @var ReviewController $controller */
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->validateOptIn());
    }

    /**
     * @return array
     */
    public function dataProviderValidateOptIn()
    {
        return [
            'required-checked' => [true, 1, true],
            'required-not-checked' => [true, 0, false],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, true],
            'not-required-not-checked' => [false, 0, true],
            'not-required-not-exits' => [false, null, true]
        ];
    }

    /**
     * Test opt in validation
     *
     * @dataProvider dataProviderReviewOptInError
     */
    public function testReviewOptInError($configValue, $checkboxStatus, $expectedValue)
    {
        /** @var ReviewController $controller */
        $controller = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam($controller::REVIEW_OPTIN_PARAM, $configValue);
        $this->setRequestParameter('rvw_oegdproptin', $checkboxStatus);

        $this->assertSame($expectedValue, $controller->isReviewOptInError());
    }

    /**
     * @return array
     */
    public function dataProviderReviewOptInError()
    {
        return [
            'required-checked' => [true, 1, false],
            'required-not-checked' => [true, 0, true],
            'required-not-exist' => [true, null, false],
            'not-required-checked' => [false, 1, false],
            'not-required-not-checked' => [false, 0, false],
            'not-required-not-exits' => [false, null, false]
        ];
    }
}
