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

namespace OxidEsales\PayPalModule\Model\Action\Handler;

/**
 * PayPal order action class
 */
abstract class OrderActionHandler
{
    /**
     * @var object
     */
    protected $data = null;

    /**
     * @var \OxidEsales\PayPalModule\Core\PayPalService
     */
    protected $payPalService = null;

    /**
     * PayPal order
     *
     * @var \OxidEsales\PayPalModule\Model\PayPalOrder
     */
    protected $payPalRequestBuilder = null;

    /**
     * Sets data object.
     *
     * @param object $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Returns Data object
     *
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets PayPal request builder
     *
     * @param \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder $builder
     */
    public function setPayPalRequestBuilder($builder)
    {
        $this->payPalRequestBuilder = $builder;
    }

    /**
     * Returns PayPal request builder
     *
     * @return \OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder
     */
    public function getPayPalRequestBuilder()
    {
        if ($this->payPalRequestBuilder === null) {
            $this->payPalRequestBuilder = oxNew(\OxidEsales\PayPalModule\Model\PayPalRequest\PayPalRequestBuilder::class);
        }

        return $this->payPalRequestBuilder;
    }

    /**
     * Sets PayPal service
     *
     * @param \OxidEsales\PayPalModule\Core\PayPalService $service
     */
    public function setPayPalService($service)
    {
        $this->payPalService = $service;
    }

    /**
     * Returns PayPal service
     *
     * @return \OxidEsales\PayPalModule\Core\PayPalService
     */
    public function getPayPalService()
    {
        if ($this->payPalService === null) {
            $this->payPalService = oxNew(\OxidEsales\PayPalModule\Core\PayPalService::class);
        }

        return $this->payPalService;
    }
}
