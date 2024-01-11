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

namespace OxidEsales\PayPalModule\Tests\Unit\Model\PayPalRequest;

/**
 * Testing \OxidEsales\PayPalModule\Model\PayPalRequest\GetExpressCheckoutDetailsRequestBuilder class.
 */
class GetExpressCheckoutDetailsRequestBuilderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Test building PayPal request object
     */
    public function testBuildRequest()
    {
        $expectedParams = array(
            'TOKEN' => '111',
        );
        $session = oxNew(\OxidEsales\Eshop\Core\Session::class);
        $session->setVariable("oepaypal-token", "111");

        $builder = $this->getPayPalRequestBuilder();
        $builder->setSession($session);
        $builder->buildRequest();

        $this->assertArraysEqual($expectedParams, $builder->getPayPalRequest()->getData());
    }

    /**
     *
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalRequest\GetExpressCheckoutDetailsRequestBuilder
     */
    protected function getPayPalRequestBuilder()
    {
        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\GetExpressCheckoutDetailsRequestBuilder();

        return $builder;
    }

    /**
     * Checks whether array length are equal and array keys and values are equal independent on keys position
     *
     * @param $expected
     * @param $result
     */
    protected function assertArraysEqual($expected, $result)
    {
        $this->assertArraysContains($expected, $result);
        $this->assertEquals(count($expected), count($result));
    }

    /**
     * Checks whether array array keys and values are equal independent on keys position
     *
     * @param $expected
     * @param $result
     */
    protected function assertArraysContains($expected, $result)
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $result, "Key not found: $key");
            $this->assertEquals($value, $result[$key], "Key '$key' value is not equal to '$value'");
        }
    }
}