<?php
/**
 * This file is part of O3-Shop Paypal module.
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

namespace OxidEsales\PayPalModule\Tests\Integration;

class CurlMainParametersTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testCurlMainParameterHost_modeSandbox_sandboxHost()
    {
        $this->getConfig()->setConfigParam('blOEPayPalSandboxMode', true);

        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('api-3t.sandbox.paypal.com', $curl->getHost());
    }

    public function testCurlMainParameterHost_modeProduction_payPalHost()
    {
        $this->getConfig()->setConfigParam('blOEPayPalSandboxMode', false);

        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('api-3t.paypal.com', $curl->getHost());
    }

    public function testCurlMainParameterCharset_default_iso()
    {
        $this->getConfig()->setConfigParam('iUtfMode', false);

        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('UTF-8', $curl->getDataCharset());
    }

    public function testCurlMainParameterCharset_utfMode_utf()
    {
        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('UTF-8', $curl->getDataCharset());
    }

    public function testCurlMainParameterUrlToCall_defaultProductionMode_ApiUrl()
    {
        $this->getConfig()->setConfigParam('blOEPayPalSandboxMode', false);

        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('https://api-3t.paypal.com/nvp', $curl->getUrlToCall());
    }

    public function testCurlMainParameterUrlToCall_defaultSandboxMode_sandboxApiUrl()
    {
        $this->getConfig()->setConfigParam('blOEPayPalSandboxMode', true);

        $service = new \OxidEsales\PayPalModule\Core\PayPalService();
        $curl = $service->getCaller()->getCurl();

        $this->assertEquals('https://api-3t.sandbox.paypal.com/nvp', $curl->getUrlToCall());
    }
}