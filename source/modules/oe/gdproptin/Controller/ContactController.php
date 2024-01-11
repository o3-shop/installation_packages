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

namespace OxidEsales\GdprOptinModule\Controller;

/**
 * Class ContactController
 * Extends \OxidEsales\Eshop\Application\Controller\ContactController
 *
 * @package OxidEsales\GdprOptinModule\Controller
 */
class ContactController extends ContactController_parent
{
    const CONTACT_FORM_METHOD_DEFAULT = 'deletion';

    /**
     * Flag which shows if validation failed because of optin is not checked
     *
     * @var bool
     */
    protected $optInError = false;
    
    /**
     * Validation and contacts email sending
     *
     * @return bool
     */
    public function send()
    {
        $optInValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('c_oegdproptin');
        if ($this->isOptInValidationRequired() && !$optInValue) {
            \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->addErrorToDisplay('OEGDPROPTIN_CONTACT_FORM_ERROR_MESSAGE');
            $this->optInError = true;
            return false;
        }

        return parent::send();
    }

    /**
     * Check if validation failed because of the optin checkbox not checked
     *
     * @return bool
     */
    public function isOptInError()
    {
        return $this->optInError;
    }

    /**
     * Check if opt in validation is required.
     *
     * @return bool
     */
    public function isOptInValidationRequired()
    {
        return $this->getContactFormMethod() != self::CONTACT_FORM_METHOD_DEFAULT;
    }

    /**
     * Get currently selected contact form opt in method
     *
     * @return string
     */
    private function getContactFormMethod()
    {
        $method = self::CONTACT_FORM_METHOD_DEFAULT;

        if ($configMethod = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('OeGdprOptinContactFormMethod')) {
            $method = $configMethod;
        }

        return $method;
    }
}
