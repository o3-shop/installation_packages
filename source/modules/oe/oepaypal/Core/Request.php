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

namespace OxidEsales\PayPalModule\Core;

/**
 * PayPal Request class
 */
class Request
{
    /**
     * Get post.
     *
     * @return array
     */
    public function getPost()
    {
        $post = array();

        if (!empty($_POST)) {
            $post = $_POST;
        }

        return $post;
    }

    /**
     * Get get.
     *
     * @return array
     */
    public function getGet()
    {
        $get = array();

        if (!empty($_GET)) {
            $get = $_GET;
        }

        return $get;
    }

    /**
     * Returns value of parameter stored in POST,GET.
     *
     * @param string $name Name of parameter
     * @param bool   $raw  mark to return not escaped parameter
     *
     * @return mixed
     */
    public function getRequestParameter($name, $raw = false)
    {
        $value = null;

        $value = $this->getPostParameter($name, $raw);
        if (!isset($value)) {
            $value = $this->getGetParameter($name, $raw);
        }

        return $value;
    }

    /**
     * Returns value of parameter stored in POST.
     *
     * @param string $name Name of parameter
     * @param bool   $raw  mark to return not escaped parameter
     *
     * @return mixed
     */
    public function getPostParameter($name, $raw = false)
    {
        $value = null;
        $post = $this->getPost();

        if (isset($post[$name])) {
            $value = $post[$name];
        }

        if ($value !== null && !$raw) {
            $value = $this->escapeSpecialChars($value);
        }

        return $value;
    }

    /**
     * Returns value of parameter stored in GET.
     *
     * @param string $name Name of parameter
     * @param bool   $raw  mark to return not escaped parameter
     *
     * @return mixed
     */
    public function getGetParameter($name, $raw = false)
    {
        $value = null;
        $get = $this->getGet();

        if (isset($get[$name])) {
            $value = $get[$name];
        }

        if ($value !== null && !$raw) {
            $value = $this->escapeSpecialChars($value);
        }

        return $value;
    }

    /**
     * Wrapper for PayPal escape class.
     *
     * @param mixed $value value to escape
     *
     * @return mixed
     */
    public function escapeSpecialChars($value)
    {
        $payPalEscape = oxNew(\OxidEsales\PayPalModule\Core\Escape::class);

        return $payPalEscape->escapeSpecialChars($value);
    }
}
