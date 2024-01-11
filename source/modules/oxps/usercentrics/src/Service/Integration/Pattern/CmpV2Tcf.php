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

namespace OxidProfessionalServices\Usercentrics\Service\Integration\Pattern;

class CmpV2Tcf implements IntegrationPatternInterface
{
    public const VERSION_NAME = 'CmpV2Tcf';

    protected $scriptSource = 'https://app.usercentrics.eu/browser-ui/latest/bundle.js';

    public function getIntegrationScriptPattern(): string
    {
        return '<script id="usercentrics-cmp"
            data-settings-id="{USERCENTRICS_CLIENT_ID}"
            src="' . $this->scriptSource . '"
            data-tcf-enabled
            defer></script>';
    }
}
