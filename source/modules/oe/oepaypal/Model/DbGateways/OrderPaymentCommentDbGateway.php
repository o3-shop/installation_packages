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

namespace OxidEsales\PayPalModule\Model\DbGateways;

/**
 * Order payment comment db gateway class.
 */
class OrderPaymentCommentDbGateway extends \OxidEsales\PayPalModule\Core\ModelDbGateway
{
    /**
     * Save PayPal order payment comment data to database.
     *
     * @param array $data
     *
     * @return bool
     */
    public function save($data)
    {
        $db = $this->getDb();

        foreach ($data as $field => $value) {
            $fields[] = '`' . $field . '` = ' . $db->quote($value);
        }

        $query = 'INSERT INTO `oepaypal_orderpaymentcomments` SET ';
        $query .= implode(', ', $fields);
        $query .= ' ON DUPLICATE KEY UPDATE ';
        $query .= ' `oepaypal_commentid`=LAST_INSERT_ID(`oepaypal_commentid`), ';
        $query .= implode(', ', $fields);
        $db->execute($query);

        $commentId = $data['oepaypal_commentid'];
        if (empty($commentId)) {
            $commentId = $db->getOne('SELECT LAST_INSERT_ID()');
        }

        return $commentId;
    }

    /**
     * Load PayPal order payment comment data from Db.
     *
     * @param string $paymentId order id
     *
     * @return array
     */
    public function getList($paymentId)
    {
        $db = $this->getDb();
        $data = $db->getAll('SELECT * FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_paymentid` = ' . $db->quote($paymentId) . ' ORDER BY `oepaypal_date` DESC');

        return $data;
    }

    /**
     * Load PayPal order payment comment data from Db.
     *
     * @param string $commentId Order id.
     *
     * @return array
     */
    public function load($commentId)
    {
        $db = $this->getDb();
        $data = $db->getRow('SELECT * FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_commentid` = ' . $db->quote($commentId));

        return $data;
    }

    /**
     * Delete PayPal order payment comment data from database.
     *
     * @param string $commentId Order id.
     *
     * @return bool
     */
    public function delete($commentId)
    {
        $db = $this->getDb();
        $db->startTransaction();

        $deleteResult = $db->execute('DELETE FROM `oepaypal_orderpaymentcomments` WHERE `oepaypal_commentid` = ' . $db->quote($commentId));

        $result = ($deleteResult !== false);

        if ($result) {
            $db->commitTransaction();
        } else {
            $db->rollbackTransaction();
        }

        return $result;
    }
}
