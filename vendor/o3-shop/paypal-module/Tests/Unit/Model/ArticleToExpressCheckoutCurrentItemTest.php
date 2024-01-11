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

namespace OxidEsales\PayPalModule\Tests\Unit\Model;

class ArticleToExpressCheckoutCurrentItemTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Tests setter and getter.
     */
    public function testSetGetArticleId()
    {
        $articleId = 'this is product id';
        $articleToExpressCheckoutValidator = new \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem();
        $articleToExpressCheckoutValidator->setArticleId($articleId);

        $this->assertEquals($articleId, $articleToExpressCheckoutValidator->getArticleId());
    }

    /**
     * Tests setter and getter.
     */
    public function testSetGeSelectList()
    {
        $selectList = array('testable' => 'selection list');
        $articleToExpressCheckoutValidator = new \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem();
        $articleToExpressCheckoutValidator->setSelectList($selectList);

        $this->assertEquals($selectList, $articleToExpressCheckoutValidator->getSelectList());
    }

    /**
     * Tests setter and getter.
     */
    public function testSetGetPersistParam()
    {
        $persistentParam = array('testable' => 'persistent param');
        $articleToExpressCheckoutValidator = new \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem();
        $articleToExpressCheckoutValidator->setPersistParam($persistentParam);

        $this->assertEquals($persistentParam, $articleToExpressCheckoutValidator->getPersistParam());
    }

    /**
     * Tests setter and getter.
     */
    public function testSetGetArticleAmount()
    {
        $amount = 5;
        $articleToExpressCheckoutValidator = new \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem();
        $articleToExpressCheckoutValidator->setArticleAmount($amount);

        $this->assertEquals($amount, $articleToExpressCheckoutValidator->getArticleAmount());
    }
}
