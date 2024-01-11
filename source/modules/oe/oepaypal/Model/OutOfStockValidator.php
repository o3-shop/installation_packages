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
 * PayPal out of stock validator class
 */
class OutOfStockValidator
{
    /**
     * Basket object
     *
     * @var object
     */
    private $basket;

    /**
     * Level of empty stock level
     *
     * @var int
     */
    private $emptyStockLevel;

    /**
     * Sets empty stock level.
     *
     * @param int $emptyStockLevel
     */
    public function setEmptyStockLevel($emptyStockLevel)
    {
        $this->emptyStockLevel = $emptyStockLevel;
    }

    /**
     * Returns empty stock level.
     *
     * @return int
     */
    public function getEmptyStockLevel()
    {
        return $this->emptyStockLevel;
    }

    /**
     * Sets basket object.
     *
     * @param object $basket
     */
    public function setBasket($basket)
    {
        $this->basket = $basket;
    }

    /**
     * Returns basket object.
     *
     * @return object
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * Checks if basket has Articles that are out of stock.
     *
     * @return bool
     */
    public function hasOutOfStockArticles()
    {
        $result = false;

        $basketContents = $this->getBasket()->getContents();

        foreach ($basketContents as $basketItem) {
            $article = $basketItem->getArticle();
            if (($article->getStockAmount() - $basketItem->getAmount()) < $this->getEmptyStockLevel()) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
