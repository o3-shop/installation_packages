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

namespace OxidEsales\PayPalModule\Tests\Codeception\Admin;

use OxidEsales\Codeception\Page\Page;

/**
 * Class Orders
 * @package OxidEsales\PayPalModule\Tests\Codeception\Admin
 */
class PayPalOrder extends Page
{
    public $paypalTab = '//a[@href="#oepaypalorder_paypal"]';
    public $captureButton = '#captureButton';
    public $amountSelect = '.amountSelect';
    public $captureAmountInput = '#captureAmountInput';
    public $pendingStatusCheckbox = '#pendingStatusCheckbox';
    public $editForm = '[name=myedit]';
    public $refundButton = '#refundButton0';
    public $refundAmountInput = '#refundAmountInput';
    public $errorBox = '.errorbox';
    public $captureErrorText = 'Error message from PayPal: Amount is not valid';
    public $refundErrorText = 'Error message from PayPal: The partial refund amount is not valid';
    public $lastHistoryRowAction = '//*[@id="historyTable"]/tbody/tr[2]/td[2]';
    public $lastHistoryRowAmount = '//*[@id="historyTable"]/tbody/tr[2]/td[3]';

    /**
     * Capture order
     *
     * @param $amount
     * @param string $type
     * @return $this
     */
    public function captureAmount($amount, $type = 'Complete')
    {
        $I = $this->user;
        $I->waitForElement($this->captureButton, 10);
        $I->click($this->captureButton);
        $I->selectOption($this->amountSelect, $type);
        if ($type !== 'Complete') {
            $I->fillField($this->captureAmountInput, $amount);
            $I->click($this->pendingStatusCheckbox);
        }
        $I->submitForm($this->editForm, []);
        $I->waitForElementNotVisible($this->editForm, 30);

        return $this;
    }

    /**
     * Refund amount
     *
     * @param $amount
     * @param string $type
     * @return $this
     */
    public function refundAmount($amount, $type = 'Full')
    {
        $I = $this->user;
        $I->waitForElement($this->refundButton, 10);
        $I->click($this->refundButton);
        $I->selectOption($this->amountSelect, $type);
        if ($type !== 'Full') {
            $I->fillField($this->refundAmountInput, $amount);
        }
        $I->submitForm($this->editForm, []);
        $I->waitForElementNotVisible($this->editForm, 30);

        return $this;
    }
}
