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

namespace OxidEsales\PayPalModule\Controller\Admin;

/**
 * Order list class wrapper for PayPal module
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\OrderList
 */
class OrderList extends OrderList_parent
{
    /**
     * Executes parent method parent::render() and returns name of template
     * file "order_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $template = parent::render();

        $paymentStatus = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("paypalpaymentstatus");
        $payment = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("paypalpayment");

        $this->_aViewData["spaypalpaymentstatus"] = $paymentStatus ? $paymentStatus : -1;
        $this->_aViewData["opaypalpaymentstatuslist"] = new \OxidEsales\PayPalModule\Model\OrderPaymentStatusList();

        $this->_aViewData["paypalpayment"] = $payment ? $payment : -1;

        /** @var \OxidEsales\Eshop\Core\Model\ListModel $paymentList */
        $paymentList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
        $paymentList->init(\OxidEsales\Eshop\Application\Model\Payment::class);

        $this->_aViewData["oPayments"] = $paymentList->getList();

        return $template;
    }

    /**
     * Builds and returns SQL query string. Adds additional order check.
     *
     * @param object $listObject list main object.
     *
     * @return string
     */
    protected function _buildSelectString($listObject = null)
    {
        $query = parent::_buildSelectString($listObject);

        $viewNameGenerator = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\TableViewNameGenerator::class);
        $viewName = $viewNameGenerator->getViewName("oxpayments");

        $queryPart = ", `oepaypal_order`.`oepaypal_paymentstatus`, `payments`.`oxdesc` as `paymentname` from oxorder
        LEFT JOIN `oepaypal_order` ON `oepaypal_order`.`oepaypal_orderid` = `oxorder`.`oxid`
        LEFT JOIN `" . $viewName . "` AS `payments` on `payments`.oxid=oxorder.oxpaymenttype ";

        $query = str_replace('from oxorder', $queryPart, $query);

        return $query;
    }

    /**
     * Adding folder check.
     *
     * @param array  $where   SQL condition array.
     * @param string $sqlFull SQL query string.
     *
     * @return string
     */
    protected function _prepareWhereQuery($where, $fullQuery)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $query = parent::_prepareWhereQuery($where, $fullQuery);

        $paymentStatus = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("paypalpaymentstatus");
        $paymentStatusList = new \OxidEsales\PayPalModule\Model\OrderPaymentStatusList();

        if ($paymentStatus && $paymentStatus != '-1' && in_array($paymentStatus, $paymentStatusList->getArray())) {
            $query .= " AND ( `oepaypal_order`.`oepaypal_paymentstatus` = " . $database->quote($paymentStatus) . " )";
            $query .= " AND ( `oepaypal_order`.`oepaypal_orderid` IS NOT NULL ) ";
        }

        $payment = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("paypalpayment");
        if ($payment && $payment != '-1') {
            $query .= " and ( oxorder.oxpaymenttype = " . $database->quote($payment) . " )";
        }

        return $query;
    }
}
