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

namespace OxidEsales\PayPalModule\Model;

/**
 * PayPal Current item Article container class.
 */
class ArticleToExpressCheckoutCurrentItem
{
    /**
     * Article id
     *
     * @var string
     */
    protected $articleId;

    /**
     * Select list
     *
     * @var array
     */
    protected $selectList;

    /**
     * Persistent param
     *
     * @var array
     */
    protected $persistParam;

    /**
     * Article amount
     *
     * @var integer
     */
    protected $articleAmount;

    /**
     * Method sets persistent param.
     *
     * @param array $persistParam
     */
    public function setPersistParam($persistParam)
    {
        $this->persistParam = $persistParam;
    }

    /**
     * Method returns persistent param.
     *
     * @return array
     */
    public function getPersistParam()
    {
        return $this->persistParam;
    }

    /**
     * Method sets select list.
     *
     * @param array $selectList
     */
    public function setSelectList($selectList)
    {
        $this->selectList = $selectList;
    }

    /**
     * Method returns select list.
     *
     * @return array
     */
    public function getSelectList()
    {
        return $this->selectList;
    }

    /**
     * Method sets article id.
     *
     * @param string $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * Method returns article id.
     *
     * @return string
     */
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * Method sets article amount.
     *
     * @param int $articleAmount
     */
    public function setArticleAmount($articleAmount)
    {
        $this->articleAmount = $articleAmount;
    }

    /**
     * Method returns article amount.
     *
     * @return int
     */
    public function getArticleAmount()
    {
        return (int) $this->articleAmount;
    }
}
