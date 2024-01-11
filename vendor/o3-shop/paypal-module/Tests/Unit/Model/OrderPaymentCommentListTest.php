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
class OrderPaymentCommentListTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     *  Setup: Prepare data - create need tables
     */
    protected function setUp(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('TRUNCATE `oepaypal_orderpaymentcomments`');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('TRUNCATE `oepaypal_orderpayments`');
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('TRUNCATE `oepaypal_order`');
    }

    /**
     * Test case for oePayPalOrderPayment::oePayPalOrderPaymentList()
     * Gets PayPal Order Payment history list
     */
    public function testLoadOrderPayments()
    {
        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setDate('2013-02-03 12:12:12');
        $comment->setComment('comment1');
        $comment->setPaymentId(2);
        $comment->save();

        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setDate('2013-02-03 12:12:12');
        $comment->setComment('comment2');
        $comment->setPaymentId(2);
        $comment->save();

        $comments = new \OxidEsales\PayPalModule\Model\OrderPaymentCommentList();
        $comments->load(2);

        $this->assertEquals(2, count($comments));

        $i = 1;
        foreach ($comments as $comment) {
            $this->assertEquals('comment' . $i++, $comment->getComment());
        }
    }


    /**
     * Test case for oePayPalOrderPayment::hasPendingPayment()
     * Checks if list has pending payments
     */
    public function testAddComment()
    {
        $list = new \OxidEsales\PayPalModule\Model\OrderPaymentCommentList();
        $list->load('payment');

        $this->assertEquals(0, count($list));

        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setPaymentId('payment');
        $comment->save();

        $list = new \OxidEsales\PayPalModule\Model\OrderPaymentCommentList();
        $list->load('payment');

        $this->assertEquals(1, count($list));

        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setComment('Comment');
        $list->addComment($comment);

        $list = new \OxidEsales\PayPalModule\Model\OrderPaymentCommentList();
        $list->load('payment');

        $this->assertEquals(2, count($list));
    }
}