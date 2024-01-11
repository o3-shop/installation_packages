<?php

/**
 * This file is part of O3-Shop Usercentrics Cookie Compliance module.
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

declare(strict_types=1);

namespace OxidProfessionalServices\Usercentrics\Service;

interface RendererInterface
{
    /**
     * Form output for includes.
     *
     * @param array<int,array<string>> $pathGroups // [ 10 => ["test.js","test2.js"] ]
     * @param string $widget   Widget name.
     *
     * @return string
     */
    public function formFilesOutput(array $pathGroups, string $widget): string;

    /**
     * Encloses script code with a script tag
     *
     * @param string $scriptsOutput
     * @param string $widget
     * @param bool $isAjaxRequest
     * @return string
     */
    public function encloseScriptSnippet(string $scriptsOutput, string $widget, bool $isAjaxRequest): string;
}
