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

namespace OxidEsales\PayPalModule\Component\Widget;

/**
 * Article box widget
 *
 * @mixin \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails
 */
class ArticleDetails extends ArticleDetails_parent
{
    /**
     * Returns products amount to .tpl pages.
     *
     * @return int
     */
    public function oePayPalGetArticleAmount()
    {
        $article = $this->oePayPalGetECSArticle();

        return isset($article['am']) ? (int) $article['am'] : 1;
    }

    /**
     * Returns persistent parameter.
     *
     * @return string
     */
    public function oePayPalGetPersistentParam()
    {
        $article = $this->oePayPalGetECSArticle();

        return $article['persparam']['details'];
    }

    /**
     * Returns selections array.
     *
     * @return array
     */
    public function oePayPalGetSelection()
    {
        $article = $this->oePayPalGetECSArticle();

        return $article['sel'];
    }

    /**
     * Checks if showECSPopup parameter was passed.
     *
     * @return bool
     */
    public function oePayPalShowECSPopup()
    {
        return $this->getComponent('oxcmp_basket')->shopECSPopUp();
    }

    /**
     * Checks if showECSPopup parameter was passed.
     *
     * @return bool
     */
    public function oePayPalGetCancelUrl()
    {
        return $this->getComponent('oxcmp_basket')->getPayPalCancelURL();
    }

    /**
     * Checks if displayCartInPayPal parameter was passed.
     *
     * @return bool
     */
    public function oePayPalDisplayCartInPayPal()
    {
        $displayCartInPayPal = false;
        if ($this->oePayPalGetRequest()->getPostParameter('displayCartInPayPal')) {
            $displayCartInPayPal = true;
        }

        return $displayCartInPayPal;
    }

    /**
     * Method returns request object.
     *
     * @return \OxidEsales\PayPalModule\Core\Request
     */
    protected function oePayPalGetRequest()
    {
        return oxNew(\OxidEsales\PayPalModule\Core\Request::class);
    }

    /**
     * Gets ECSArticle, unserializes and returns it.
     *
     * @return array
     */
    protected function oePayPalGetECSArticle()
    {
        $products = $this->getComponent('oxcmp_basket')->getCurrentArticleInfo();

        return $products;
    }
}
