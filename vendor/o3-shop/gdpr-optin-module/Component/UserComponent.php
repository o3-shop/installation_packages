<?php
/**
 * This file is part of O3-Shop GDPR opt-in module.
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

namespace OxidEsales\GdprOptinModule\Component;

/**
 * Class oeGdprOptinOxcmp_user.
 * Extends oxcmp_user.
 *
 * @see \OxidEsales\Eshop\Application\Component\UserComponent
 */
class UserComponent extends UserComponent_parent
{
    /**
     * Create new user.
     *
     * @return mixed
     */
    public function createUser()
    {
        if (false == $this->validateRegistrationOptin()) {
            //show error message on submit but not on page reload.
            if ($this->getRequestParameter('stoken')) {
                \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN', false, true);
                \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_USER_REGISTRATION_OPTIN', false, true, 'oegdproptin_userregistration');
            }
        } else {
            return parent::createUser();
        }
    }

    /**
     * Mostly used for customer profile editing screen (O3-Shop ->
     * MY ACCOUNT). Checks if oUser is set (\OxidEsales\Eshop\Application\Component\UserComponent::oUser) - if
     * not - executes \OxidEsales\Eshop\Application\Component\UserComponent::_loadSessionUser(). If user unchecked newsletter
     * subscription option - removes him from this group. There is an
     * additional MUST FILL fields checking. Function returns true or false
     * according to user data submission status.
     *
     * Session variables:
     * <b>ordrem</b>
     *
     * @return  bool true on success, false otherwise
     */
    protected function _changeUser_noRedirect()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        if (!$session->checkSessionChallenge()) {
            return;
        }

        // no user ?
        $user = $this->getUser();
        if (!$user) {
            return;
        }

        $deliveryOptinValid = $this->validateDeliveryAddressOptIn();
        $invoiceOptinValid = $this->validateInvoiceAddressOptIn();

        if (false == $deliveryOptinValid) {
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS', false, true);
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_DELIVERY_ADDRESS', false, true, 'oegdproptin_deliveryaddress');
        }
        if (false == $invoiceOptinValid) {
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS', false, true);
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONFIRM_STORE_INVOICE_ADDRESS', false, true, 'oegdproptin_invoiceaddress');
        }

        $return = false;
        if ( (true == $deliveryOptinValid) && (true == $invoiceOptinValid)) {
            $return = parent::_changeUser_noRedirect();
        }

        return $return;
    }

    /**
     * Validate delivery address optin.
     * Needed if we get delivery address data from request.
     *
     * @return bool
     */
    protected function validateDeliveryAddressOptIn()
    {
        $return = true;
        $optin = (int) $this->getRequestParameter('oegdproptin_deliveryaddress');
        $changeExistigAddress = (int) $this->getRequestParameter('oegdproptin_changeDelAddress');
        $addressId = $this->getRequestParameter('oxaddressid');
        $deliveryAddressData = $this->_getDelAddressData();

        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('blOeGdprOptinDeliveryAddress')
            && ((null == $addressId) || ('-1' == $addressId) || (1 == $changeExistigAddress))
            && !empty($deliveryAddressData)
            && (1 !== $optin)
            ) {
            $return = false;
        }
        return $return;
    }

    /**
     * Validate user registration optin.
     *
     * @return bool
     */
    protected function validateRegistrationOptin()
    {
        $return = true;
        $optin = (int) $this->getRequestParameter('oegdproptin_userregistration');
        //1 is for guest buy, 3 for account creation
        $registrationOption = (int) $this->getRequestParameter('option');
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('blOeGdprOptinUserRegistration') && (3 == $registrationOption) && (1 !== $optin)) {
            $return = false;
        }
        return $return;
    }

    /**
     * Validate invoice address optin.
     * Needed if user changes invoice address.
     *
     * @return bool
     */
    protected function validateInvoiceAddressOptIn()
    {
        $return = true;
        $optin = (int) $this->getRequestParameter('oegdproptin_invoiceaddress');
        $changeExistigAddress = (int) $this->getRequestParameter('oegdproptin_changeInvAddress');

        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('blOeGdprOptinInvoiceAddress')
            && (1 == $changeExistigAddress)
            && (1 !== $optin)
        ) {
            $return = false;
        }
        return $return;
    }

    /**
     * Wrapper for \OxidEsales\Eshop\Core\Request::getRequestParameter()
     *
     * @param string $name Parameter name
     *
     * @return mixed
     */
    protected function getRequestParameter($name)
    {
        $request = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Request::class);

        return $request->getRequestParameter($name);
    }
}
