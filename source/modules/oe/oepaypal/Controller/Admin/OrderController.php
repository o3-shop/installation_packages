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
 * Order class wrapper for PayPal module
 */
class OrderController extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{
    /**
     * Executes parent method parent::render(), creates oxOrder object,
     * passes it's data to Smarty engine and returns
     * name of template file "order_paypal.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $this->_aViewData["sOxid"] = $this->getEditObjectId();
        if ($this->isNewPayPalOrder()) {
            $this->_aViewData['oOrder'] = $this->getEditObject();
        } else {
            $this->_aViewData['sMessage'] = $this->isPayPalOrder() ? \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEPAYPAL_ONLY_FOR_NEW_PAYPAL_PAYMENT") :
                \OxidEsales\Eshop\Core\Registry::getLang()->translateString("OEPAYPAL_ONLY_FOR_PAYPAL_PAYMENT");
        }

        return "order_paypal.tpl";
    }

    /**
     * Processes PayPal actions.
     */
    public function processAction()
    {
        try {
            /** @var \OxidEsales\PayPalModule\Core\Request $request */
            $request = oxNew(\OxidEsales\PayPalModule\Core\Request::class);
            $action = $request->getRequestParameter('action');

            $order = $this->getEditObject();

            /** @var \OxidEsales\PayPalModule\Model\Action\OrderActionFactory $actionFactory */
            $actionFactory = oxNew(\OxidEsales\PayPalModule\Model\Action\OrderActionFactory::class, $request, $order);
            $action = $actionFactory->createAction($action);

            $action->process();
        } catch (\OxidEsales\Eshop\Core\Exception\StandardException $exception) {
            $this->_aViewData["error"] = $exception->getMessage();
        }
    }

    /**
     * Returns PayPal order action manager.
     *
     * @return \OxidEsales\PayPalModule\Model\OrderActionManager
     */
    public function getOrderActionManager()
    {
        /** @var \OxidEsales\PayPalModule\Model\OrderActionManager $manager */
        $manager = oxNew(\OxidEsales\PayPalModule\Model\OrderActionManager::class);
        $manager->setOrder($this->getEditObject()->getPayPalOrder());

        return $manager;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPaymentActionManager
     */
    public function getOrderPaymentActionManager()
    {
        $manager = oxNew(\OxidEsales\PayPalModule\Model\OrderPaymentActionManager::class);

        return $manager;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPaymentStatusCalculator
     */
    public function getOrderPaymentStatusCalculator()
    {
        /** @var \OxidEsales\PayPalModule\Model\OrderPaymentStatusCalculator $statusCalculator */
        $statusCalculator = oxNew(\OxidEsales\PayPalModule\Model\OrderPaymentStatusCalculator::class);
        $statusCalculator->setOrder($this->getEditObject()->getPayPalOrder());

        return $statusCalculator;
    }

    /**
     * Returns PayPal order action manager
     *
     * @return \OxidEsales\PayPalModule\Model\OrderPaymentStatusList
     */
    public function getOrderPaymentStatusList()
    {
        $list = oxNew(\OxidEsales\PayPalModule\Model\OrderPaymentStatusList::class);

        return $list;
    }

    /**
     * Returns editable order object
     *
     * @return \OxidEsales\PayPalModule\Model\Order
     */
    public function getEditObject()
    {
        $soxId = $this->getEditObjectId();
        if ($this->_oEditObject === null && isset($soxId) && $soxId != '-1') {
            $this->_oEditObject = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
            $this->_oEditObject->load($soxId);
        }

        return $this->_oEditObject;
    }

    /**
     * Method checks if order was made with current PayPal module, but not eFire PayPal module
     *
     * @return bool
     */
    public function isNewPayPalOrder()
    {
        $active = false;

        $order = $this->getEditObject();
        $orderPayPal = $order->getPayPalOrder();
        if ($this->isPayPalOrder() && $orderPayPal->isLoaded()) {
            $active = true;
        }

        return $active;
    }

    /**
     * Method checks is order was made with any PayPal module
     *
     * @return bool
     */
    public function isPayPalOrder()
    {
        $active = false;

        $order = $this->getEditObject();
        if ($order && $order->getFieldData('oxpaymenttype') == 'oxidpaypal') {
            $active = true;
        }

        return $active;
    }

    /**
     * Template getter for price formatting
     *
     * @param double $price price
     *
     * @return string
     */
    public function formatPrice($price)
    {
        return \OxidEsales\Eshop\Core\Registry::getLang()->formatCurrency($price);
    }
}
