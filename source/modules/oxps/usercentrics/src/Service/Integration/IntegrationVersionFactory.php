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

namespace OxidProfessionalServices\Usercentrics\Service\Integration;

use OxidProfessionalServices\Usercentrics\Exception\PatternNotFound;
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern;
use OxidProfessionalServices\Usercentrics\Service\Integration\Pattern\IntegrationPatternInterface;

class IntegrationVersionFactory implements IntegrationVersionFactoryInterface
{
    private $versionMap = [
        Pattern\CmpV1::VERSION_NAME => Pattern\CmpV1::class,
        Pattern\CmpV2::VERSION_NAME => Pattern\CmpV2::class,
        Pattern\CmpV2Legacy::VERSION_NAME => Pattern\CmpV2Legacy::class,
        Pattern\CmpV2Tcf::VERSION_NAME => Pattern\CmpV2Tcf::class,
        Pattern\CmpV2TcfLegacy::VERSION_NAME => Pattern\CmpV2TcfLegacy::class,
        Pattern\Custom::VERSION_NAME => Pattern\Custom::class
    ];

    public function getPatternByVersion(string $integrationVersion): IntegrationPatternInterface
    {
        if (!isset($this->versionMap[$integrationVersion])) {
            throw new PatternNotFound();
        }

        /** @var IntegrationPatternInterface $integrationVersionPattern */
        $integrationVersionPattern = new $this->versionMap[$integrationVersion]();
        return $integrationVersionPattern;
    }
}
