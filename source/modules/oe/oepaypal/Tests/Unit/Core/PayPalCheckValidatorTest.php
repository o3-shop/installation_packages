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
 * Testing \OxidEsales\PayPalModule\Core\PayPalCheckValidator class.
 */
class PayPalCheckValidatorTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Data provider for testIsPayPalCheckValid()
     *
     * @return array
     */
    public function isIsPayPalCheckValid_dataProvider()
    {
        return array(
            array(200, 220, true),   //if new value is less
            array(50, 50, true),     //if new and old values are the same
            array(620, 600, false),   //if new value is bigger
            array(26.55, '26.55', true),   //if old value is string
            array(600, 620, true),   //if new value is smaller
        );
    }

    /**
     * Test \OxidEsales\PayPalModule\Core\PayPalCheckValidator::isPayPalCheckValid()
     *
     * @dataProvider isIsPayPalCheckValid_dataProvider
     */
    public function testIsPayPalCheckValid($newAmount, $oldAmount, $result)
    {
        $checkValidator = new \OxidEsales\PayPalModule\Core\PayPalCheckValidator();
        $checkValidator->setNewBasketAmount($newAmount);
        $checkValidator->setOldBasketAmount($oldAmount);
        $this->assertEquals($result, $checkValidator->isPayPalCheckValid());
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Core\PayPalCheckValidator::getOldBasketAmount()
     */
    public function testSetGetOldBasketAmount()
    {
        $validator = new \OxidEsales\PayPalModule\Core\PayPalCheckValidator();
        $validator->setOldBasketAmount('3.5');

        $this->assertEquals(3.5, $validator->getOldBasketAmount());
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Core\PayPalCheckValidator::getNewBasketAmount()
     */
    public function testSetGetNewBasketAmount()
    {
        $validator = new \OxidEsales\PayPalModule\Core\PayPalCheckValidator();
        $validator->setNewBasketAmount('3.5');

        $this->assertEquals(3.5, $validator->getNewBasketAmount());
    }
}

