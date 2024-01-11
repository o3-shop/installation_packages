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

/**
 * Testing oxAccessRightException class.
 */
class ArticleTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * @dataProvider provider
     */
    public function dataProvider()
    {
        return array(
            array(false, false, false),
            array(true, false, false),
            array(false, true, false),
            array(true, true, true),
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIsVirtualPayPalArticle($isMaterial, $isDownloadable, $result)
    {
        $article = oxNew(\OxidEsales\PayPalModule\Model\Article::class);

        $article->oxarticles__oxnonmaterial = new \OxidEsales\Eshop\Core\Field($isMaterial);
        $article->oxarticles__oxisdownloadable = new \OxidEsales\Eshop\Core\Field($isDownloadable);

        $this->assertEquals($result, $article->isVirtualPayPalArticle());
    }

    /**
     */
    public function testGetStockAmount()
    {
        $article = oxNew(\OxidEsales\PayPalModule\Model\Article::class);

        $article->oxarticles__oxstock = new \OxidEsales\Eshop\Core\Field(321);

        $this->assertEquals(321, $article->getStockAmount());
    }
}
