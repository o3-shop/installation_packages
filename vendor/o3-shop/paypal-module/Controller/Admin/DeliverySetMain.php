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
 * Adds additional functionality needed for PayPal when managing delivery sets.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\DeliverySetMain
 */
class DeliverySetMain extends DeliverySetMain_parent
{
    /**
     * Add default PayPal mobile payment.
     *
     * @return string
     */
    public function render()
    {
        $template = parent::render();

        $deliverySetId = $this->getEditObjectId();
        if ($deliverySetId != "-1" && isset($deliverySetId)) {
            /** @var \OxidEsales\PayPalModule\Core\Config $config */
            $config = oxNew(\OxidEsales\PayPalModule\Core\Config::class);

            $isPayPalDefaultMobilePayment = ($deliverySetId == $config->getMobileECDefaultShippingId());

            $this->_aViewData['isPayPalDefaultMobilePayment'] = $isPayPalDefaultMobilePayment;
        }

        return $template;
    }

    /**
     * Saves default PayPal mobile payment.
     */
    public function save()
    {
        parent::save();

        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        /** @var \OxidEsales\PayPalModule\Core\Config $payPalConfig */
        $payPalConfig = oxNew(\OxidEsales\PayPalModule\Core\Config::class);

        $deliverySetId = $this->getEditObjectId();
        $deliverySetMarked = (bool) $config->getRequestParameter('isPayPalDefaultMobilePayment');
        $mobileECDefaultShippingId = $payPalConfig->getMobileECDefaultShippingId();

        if ($deliverySetMarked && $deliverySetId != $mobileECDefaultShippingId) {
            $this->saveECDefaultShippingId($config, $deliverySetId, $payPalConfig);
        } elseif (!$deliverySetMarked && $deliverySetId == $mobileECDefaultShippingId) {
            $this->saveECDefaultShippingId($config, '', $payPalConfig);
        }
    }

    /**
     * Save default shipping id.
     *
     * @param \OxidEsales\Eshop\Core\Config        $config       Config object to save.
     * @param string                               $shippingId   Shipping id.
     * @param \OxidEsales\PayPalModule\Core\Config $payPalConfig PayPal config.
     */
    protected function saveECDefaultShippingId($config, $shippingId, $payPalConfig)
    {
        $payPalModuleId = 'module:' . $payPalConfig->getModuleId();
        $config->saveShopConfVar('string', 'sOEPayPalMECDefaultShippingId', $shippingId, null, $payPalModuleId);
    }
}
