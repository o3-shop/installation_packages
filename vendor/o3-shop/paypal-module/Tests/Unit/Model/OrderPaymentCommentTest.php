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
class OrderPaymentCommentTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     *  Setup: Prepare data - create need tables
     */
    protected function setUp(): void
    {
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute('TRUNCATE `oepaypal_orderpaymentcomments`');
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\PayPalOrder::save()
     * Tests adding / getting PayPal Order Payment history item
     */
    public function testSavePayPalPayPalOrder_insertIdSet()
    {
        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setCommentId(1);
        $comment->setDate('2013-02-03 12:12:12');
        $comment->setComment('comment');
        $comment->setPaymentId(2);
        $id = $comment->save();

        $this->assertEquals(1, $id);

        $commentLoaded = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $commentLoaded->load($comment->getCommentId());

        $this->assertEquals(1, $commentLoaded->getCommentId());
        $this->assertEquals('comment', $commentLoaded->getComment());
        $this->assertEquals(2, $commentLoaded->getPaymentId());
        $this->assertEquals('2013-02-03 12:12:12', $commentLoaded->getDate());
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\PayPalOrder::save()
     * Tests adding / getting PayPal Order Payment history item
     */
    public function testSavePayPalPayPalOrder_withoutDate_dateSetNow()
    {
        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setComment('comment');
        $comment->setPaymentId(2);
        $comment->save();

        $commentLoaded = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $commentLoaded->load($comment->getCommentId());

        $this->assertEquals('comment', $commentLoaded->getComment());
        $this->assertEquals(date('Y-m-d'), substr($commentLoaded->getDate(), 0, 10));
    }


    /**
     * Test case for \OxidEsales\PayPalModule\Model\PayPalOrder::save()
     * Tests adding / getting PayPal Order Payment history item
     */
    public function testSavePayPalPayPalOrder_insertIdNotSet()
    {
        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setDate('2013-02-03 12:12:12');
        $comment->setComment('comment');
        $comment->setPaymentId(2);
        $commentId = $comment->save();

        $commentLoaded = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $commentLoaded->load($commentId);

        $this->assertEquals($commentId, $commentLoaded->getCommentId());
        $this->assertEquals('comment', $commentLoaded->getComment());
        $this->assertEquals(2, $commentLoaded->getPaymentId());
        $this->assertEquals('2013-02-03 12:12:12', $commentLoaded->getDate());
    }

    /**
     * Test case for \OxidEsales\PayPalModule\Model\PayPalOrder::save()
     * Tests adding / getting PayPal Order Payment history item
     */
    public function testSavePayPalPayPalOrder_update()
    {
        $comment = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $comment->setCommentId(10);
        $comment->setDate('2013-02-03 12:12:12');
        $comment->setComment('comment');
        $comment->setPaymentId(2);
        $comment->save();

        $commentLoaded = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $commentLoaded->load(10);
        $commentLoaded->setComment('comment comment');
        $id = $commentLoaded->save();

        $this->assertEquals(10, $id);

        $commentLoaded = new \OxidEsales\PayPalModule\Model\OrderPaymentComment();
        $commentLoaded->load(10);
        $this->assertEquals(10, $commentLoaded->getCommentId());
        $this->assertEquals('comment comment', $commentLoaded->getComment());
        $this->assertEquals(2, $commentLoaded->getPaymentId());
        $this->assertEquals('2013-02-03 12:12:12', $commentLoaded->getDate());
    }
}