<?php
/**
 * This file is part of O3-Shop GDPR opt-in module.
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

namespace OxidEsales\GdprOptinModule\Component\Widget;

/**
 * Class Review
 * Extends \OxidEsales\Eshop\Application\Component\Widget\Review.
 *
 * @package OxidEsales\GdprOptinModule\Component\Widget
 * @see \OxidEsales\Eshop\Application\Component\Widget\Review
 */
class Review extends Review_parent
{
    /**
     * Is optin for product review required.
     *
     * @return bool
     */
    public function isReviewOptInValidationRequired()
    {
        $review = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        return $review->isReviewOptInValidationRequired();
    }

    /**
     * Was there an error for shop side review optin validation?
     *
     * @return bool
     */
    public function isReviewOptInError()
    {
        $review = oxNew(\OxidEsales\Eshop\Application\Controller\ReviewController::class);
        return $review->isReviewOptInError();
    }
}
