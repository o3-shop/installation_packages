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
 * Testing \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder class.
 */
class PayPalRequestBuilderTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testSetGetRequest()
    {
        $data = array('DATA' => 'data');
        $request = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequest();
        $request->setData($data);
        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setRequest($request);

        $this->assertEquals($request, $builder->getRequest());
    }

    public function testSetAuthorizationId()
    {
        $authorizationId = 'AuthorizationId';
        $expected = array('AUTHORIZATIONID' => $authorizationId);

        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setAuthorizationId($authorizationId);

        $request = $builder->getRequest();

        $this->assertEquals($expected, $request->getData());
    }

    public function testSetAmount()
    {
        $amount = 99.99;
        $currency = 'EUR';
        $expected = array(
            'AMT'          => $amount,
            'CURRENCYCODE' => $currency
        );

        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setAmount($amount);

        $request = $builder->getRequest();

        $this->assertEquals($expected, $request->getData());
    }

    public function testSetCompleteType()
    {
        $completeType = 'Full';
        $expected = array('COMPLETETYPE' => $completeType);

        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setCompleteType($completeType);

        $request = $builder->getRequest();

        $this->assertEquals($expected, $request->getData());
    }

    public function testSetRefundType()
    {
        $refundType = 'Complete';
        $expected = array('REFUNDTYPE' => $refundType);

        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setRefundType($refundType);

        $request = $builder->getRequest();

        $this->assertEquals($expected, $request->getData());
    }

    public function testSetComment()
    {
        $comment = 'Comment';
        $expected = array('NOTE' => $comment);

        $builder = new \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder();
        $builder->setComment($comment);

        $request = $builder->getRequest();

        $this->assertEquals($expected, $request->getData());
    }
}