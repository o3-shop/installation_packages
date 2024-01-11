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

namespace OxidEsales\PayPalModule\Component;

/**
 * Basket component
 *
 * @mixin \OxidEsales\Eshop\Application\Component\BasketComponent
 */
class BasketComponent extends BasketComponent_parent
{
    /**
     * Show ECS PopUp
     *
     * @var bool
     */
    protected $shopPopUp = false;

    /**
     * Method returns URL to checkout products OR to show popup.
     *
     * @return string
     */
    public function actionExpressCheckoutFromDetailsPage()
    {
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $validator = $this->getValidator();
        $currentArticle = $this->getCurrentArticle();
        $validator->setItemToValidate($currentArticle);
        $validator->setBasket($session->getBasket());
        if ($validator->isArticleValid()) {
            //Make express checkout
            $res = $this->actionAddToBasketAndGoToCheckout();
        } else {
            $res = $this->_getRedirectUrl();
            //if amount is more than 0, do not redirect, show ESC popup instead
            if ($currentArticle->getArticleAmount() > 0) {
                $this->shopPopUp = true;
                $res = null;
            }
        }

        return $res;
    }

    /**
     * Returns whether ECS popup should be shown
     *
     * @return bool
     */
    public function shopECSPopUp()
    {
        return $this->shopPopUp;
    }

    /**
     * Action method to add product to basket and return checkout URL.
     *
     * @return string
     */
    public function actionAddToBasketAndGoToCheckout()
    {
        parent::tobasket();

        return $this->getExpressCheckoutUrl();
    }

    /**
     * Action method to return checkout URL.
     *
     * @return string
     */
    public function actionNotAddToBasketAndGoToCheckout()
    {
        return $this->getExpressCheckoutUrl();
    }

    /**
     * Returns express checkout URL
     *
     * @return string
     */
    protected function getExpressCheckoutUrl()
    {
        return 'oepaypalexpresscheckoutdispatcher?fnc=setExpressCheckout&displayCartInPayPal=' . (int) $this->getRequest()->getPostParameter('displayCartInPayPal') . '&oePayPalCancelURL=' . $this->getPayPalCancelURL();
    }

    /**
     * Method returns serialized current article params.
     *
     * @return string
     */
    public function getCurrentArticleInfo()
    {
        $products = $this->_getItems();
        $currentArticleId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('aid');
        $params = null;
        if (!is_null($products[$currentArticleId])) {
            $params = $products[$currentArticleId];
        }

        return $params;
    }

    /**
     * Method sets params for article and returns it's object.
     *
     * @return \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem
     */
    protected function getCurrentArticle()
    {
        $currentItem = oxNew(\OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutCurrentItem::class);
        $currentArticleId = $this->getRequest()->getPostParameter('aid');
        $products = $this->_getItems();
        $productInfo = $products[$currentArticleId];
        $currentItem->setArticleId($currentArticleId);
        $currentItem->setSelectList($productInfo['sel']);
        $currentItem->setPersistParam($productInfo['persparam']);
        $currentItem->setArticleAmount($productInfo['am']);

        return $currentItem;
    }

    /**
     * Method returns request object.
     *
     * @return \OxidEsales\PayPalModule\Core\Request
     */
    protected function getRequest()
    {
        return oxNew(\OxidEsales\PayPalModule\Core\Request::class);
    }

    /**
     * Method sets params for validator and returns it's object.
     *
     * @return \OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutValidator
     */
    protected function getValidator()
    {
        $validator = oxNew(\OxidEsales\PayPalModule\Model\ArticleToExpressCheckoutValidator::class);

        return $validator;
    }

    /**
     * Changes oePayPalCancelURL by changing popup showing parameter.
     *
     * @return string
     */
    public function getPayPalCancelURL()
    {
        $url = $this->formatUrl($this->_getRedirectUrl());
        $replacedURL = str_replace('showECSPopup=1', 'showECSPopup=0', $url);

        return urlencode($replacedURL);
    }

    /**
     * Formats Redirect URL to normal url
     *
     * @param string $unformedUrl
     *
     * @return string
     */
    protected function formatUrl($unformedUrl)
    {
        $myConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $session = \OxidEsales\Eshop\Core\Registry::getSession();
        $params = explode('?', $unformedUrl);
        $pageParams = isset($params[1]) ? $params[1] : null;
        $params = explode('/', $params[0]);
        $className = $params[0];

        $header = ($className) ? "cl=$className&" : '';  // adding view name
        $header .= ($pageParams) ? "$pageParams&" : '';   // adding page params
        $header .= $session->sid();            // adding session Id

        $url = $myConfig->getCurrentShopUrl($this->isAdmin());

        $url = "{$url}index.php?{$header}";

        $url = \OxidEsales\Eshop\Core\Registry::getUtilsUrl()->processUrl($url);

        $seoIsActive = \OxidEsales\Eshop\Core\Registry::getUtils()->seoIsActive();
        if ($seoIsActive && $seoUrl = \OxidEsales\Eshop\Core\Registry::getSeoEncoder()->getStaticUrl($url)) {
            $url = $seoUrl;
        }

        return $url;
    }
}
