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
 * PayPal order payment comment class
 */
class OrderPaymentComment extends \OxidEsales\PayPalModule\Core\Model
{
    /**
     * Sets date value.
     */
    public function __construct()
    {
        $utilsDate = \OxidEsales\Eshop\Core\Registry::getUtilsDate();

        $this->setValue('oepaypal_date', date('Y-m-d H:i:s', $utilsDate->getTime()));
    }

    /**
     * Sets comment id.
     *
     * @param string $commentId
     */
    public function setId($commentId)
    {
        $this->setCommentId($commentId);
    }

    /**
     * Returns comment id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getCommentId();
    }

    /**
     * Set PayPal order comment Id
     *
     * @param string $commentId
     */
    public function setCommentId($commentId)
    {
        $this->setValue('oepaypal_commentid', $commentId);
    }

    /**
     * Set PayPal comment Id
     *
     * @return string
     */
    public function getCommentId()
    {
        return $this->getValue('oepaypal_commentid');
    }

    /**
     * Set PayPal order payment Id
     *
     * @param string $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->setValue('oepaypal_paymentid', $paymentId);
    }

    /**
     * Set PayPal order payment Id
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->getValue('oepaypal_paymentid');
    }

    /**
     * Set date
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->setValue('oepaypal_date', $date);
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->getValue('oepaypal_date');
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->setValue('oepaypal_comment', $comment);
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->getValue('oepaypal_comment');
    }

    /**
     * Return database gateway
     *
     * @return \OxidEsales\PayPalModule\Model\DbGateways\OrderPaymentCommentDbGateway|\OxidEsales\PayPalModule\Core\ModelDbGateway
     */
    protected function getDbGateway()
    {
        if (is_null($this->dbGateway)) {
            $this->setDbGateway(oxNew(\OxidEsales\PayPalModule\Model\DbGateways\OrderPaymentCommentDbGateway::class));
        }

        return $this->dbGateway;
    }
}
