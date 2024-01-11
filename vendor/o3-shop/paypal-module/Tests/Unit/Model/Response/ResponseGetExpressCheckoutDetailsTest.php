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

namespace OxidEsales\PayPalModule\Tests\Unit\Model\Response;

/**
 * Testing \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails class.
 */
class ResponseGetExpressCheckoutDetailsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Returns response data
     *
     * @return array
     */
    protected function getResponseData()
    {
        $data = array(
            'SHIPPINGOPTIONNAME'                 => 'Air',
            'PAYMENTREQUEST_0_AMT'               => 1200,
            'PAYERID'                            => 'payer',
            'EMAIL'                              => 'oxid@oxid.com',
            'FIRSTNAME'                          => 'Name',
            'LASTNAME'                           => 'Surname',
            'PAYMENTREQUEST_0_SHIPTOSTREET'      => 'Street',
            'PAYMENTREQUEST_0_SHIPTOCITY'        => 'City',
            'PAYMENTREQUEST_0_SHIPTONAME'        => 'First Last',
            'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => 'CountryCode',
            'PAYMENTREQUEST_0_SHIPTOSTATE'       => 'State',
            'PAYMENTREQUEST_0_SHIPTOZIP'         => '1121',
            'PAYMENTREQUEST_0_SHIPTOPHONENUM'    => '+37000000000',
            'PAYMENTREQUEST_0_SHIPTOSTREET2'     => 'Street2',
            'SALUTATION'                         => 'this is salutation',
            'BUSINESS'                           => 'company',
            'PHONENUM'                           => '123-345-789'
        );

        return $data;
    }

    /**
     * Test getting shipping option name
     */
    public function testGetShippingOptionName()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('Air', $response->getShippingOptionName());
    }

    /**
     * Test getting token
     */
    public function testGetAmount()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals(1200, $response->getAmount());
    }

    /**
     * Test getting payer id
     */
    public function testGetPayerId()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('payer', $response->getPayerId());
    }

    /**
     * Test getting email
     */
    public function testGetEmail()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('oxid@oxid.com', $response->getEmail());
    }

    /**
     * Test getting first name
     */
    public function testGetFirstName()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('Name', $response->getFirstName());
    }

    /**
     * Test getting last name
     */
    public function testGetLastName()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('Surname', $response->getLastName());
    }

    /**
     * Test getting street
     */
    public function testGetShipToStreet()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('Street', $response->getShipToStreet());
    }

    /**
     * Test getting city
     */
    public function testGetShipToCity()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('City', $response->getShipToCity());
    }

    /**
     * Test getting name
     */
    public function testGetShipToName()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('First Last', $response->getShipToName());
    }

    /**
     * Test getting country code
     */
    public function testGetShipToCountryCode()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('CountryCode', $response->getShipToCountryCode());
    }

    /**
     * Test getting state
     */
    public function testGetShipToState()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('State', $response->getShipToState());
    }

    /**
     * Test getting state
     */
    public function testGetShipToZip()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('1121', $response->getShipToZip());
    }

    /**
     * Test getting phone number
     */
    public function testGetShipToPhoneNumber()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('+37000000000', $response->getShipToPhoneNumber());
    }

    /**
     * Test getting phone number
     */
    public function testGetShipToPhoneNumberMandatory()
    {
        $defaultFields = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('aMustFillFields');
        \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam('aMustFillFields', array_merge($defaultFields, ['oxuser__oxfon']));

        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('123-345-789', $response->getShipToPhoneNumber());
    }

    /**
     * Test getting phone number
     */
    public function testGetShipToStreet2()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('Street2', $response->getShipToStreet2());
    }

    /**
     * Test getting salutation
     */
    public function testGetSalutation()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('this is salutation', $response->getSalutation());
    }

    /**
     * Test getting company name
     */
    public function testGetBusiness()
    {
        $response = new \OxidEsales\PayPalModule\Model\Response\ResponseGetExpressCheckoutDetails();
        $response->setData($this->getResponseData());
        $this->assertEquals('company', $response->getBusiness());
    }
}
