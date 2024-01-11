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

namespace OxidEsales\PayPalModule\Tests\Unit\Core;

/**
 * Testing \OxidEsales\PayPalModule\Core\Request class.
 */
class RequestTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Data provider for testGetPost()
     *
     * @return array
     */
    public function providerGetPost()
    {
        return array(
            array(
                array('asd&' => 'a%&'),
                array('asd&' => 'a%&'),
            ),
            array(
                null,
                array(),
            )
        );
    }

    /**
     * Test if return POST.
     *
     * @param array $post
     * @param array $postExpected
     *
     * @dataProvider providerGetPost
     */
    public function testGetPost($post, $postExpected)
    {
        $_POST = $post;
        $_GET = array('zzz' => 'yyyy');
        $payPalRequest = new \OxidEsales\PayPalModule\Core\Request();
        $this->assertEquals($postExpected, $payPalRequest->getPost());
    }

    /**
     * Data provider for testGetGet()
     *
     * @return array
     */
    public function providerGetGet()
    {
        return array(
            array(
                array('asd&' => 'a%&'),
                array('asd&' => 'a%&'),
            ),
            array(
                null,
                array(),
            )
        );
    }

    /**
     * Test if return Get.
     *
     * @param array $get
     * @param array $getExpected
     *
     * @dataProvider providerGetGet
     */
    public function testGetGet($get, $getExpected)
    {
        $_GET = $get;
        $_POST = array('zzz' => 'yyyy');
        $payPalRequest = new \OxidEsales\PayPalModule\Core\Request();
        $this->assertEquals($getExpected, $payPalRequest->getGet());
    }

    /**
     * Data provider for testGetRequestParameter()
     *
     * @return array
     */
    public function providerGetRequestParameter()
    {
        return array(
            array(array('zzz' => 'yyy'), array('zzz' => 'iii'), 'zzz', false, 'yyy'),
            array(array('zzz' => 'yyy'), array('zzz' => 'yyy'), 'zzz', false, 'yyy'),
            array(array('zzz' => 'iii'), array('zzz' => 'yyy'), 'zzz', false, 'iii'),
            array(array('zzz' => 'yyy&'), null, 'zzz', true, 'yyy&'),
            array(null, array('zzz' => 'yyy&'), 'zzz', true, 'yyy&'),
            array(array('zzz' => 'yyy&'), null, 'zzz', false, 'yyy&amp;'),
            array(null, array('zzz' => 'yyy&'), 'zzz', false, 'yyy&amp;'),
        );
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Core\Request::getRequestParameter()
     * Test case for \OxidEsales\PayPalModule\Core\Request::getGetParameter()
     * Test case for \OxidEsales\PayPalModule\Core\Request::getPostParameter()
     *
     * @dataProvider providerGetRequestParameter
     */
    public function testGetRequestParameter($post, $get, $parameterName, $raw, $expectedRequestParameter)
    {
        $_POST = $post;
        $_GET = $get;
        $payPalRequest = new \OxidEsales\PayPalModule\Core\Request();
        $this->assertEquals($expectedRequestParameter, $payPalRequest->getRequestParameter($parameterName, $raw));
    }
}