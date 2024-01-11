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
 * PayPal oxArticle class
 *
 * @mixin \OxidEsales\Eshop\Application\Model\Article
 */
class Article extends Article_parent
{
    /**
     * Check if Product is virtual: is non material and is downloadable
     *
     * @return bool
     */
    public function isVirtualPayPalArticle()
    {
        $virtual = true;

        // non material products
        if (!$this->oxarticles__oxnonmaterial->value) {
            $virtual = false;
        } elseif (isset($this->oxarticles__oxisdownloadable) &&
                  !$this->oxarticles__oxisdownloadable->value
        ) {
            $virtual = false;
        }

        return $virtual;
    }

    /**
     * Gets stock amount for article
     *
     * @return float
     */
    public function getStockAmount()
    {
        return $this->oxarticles__oxstock->value;
    }
}
