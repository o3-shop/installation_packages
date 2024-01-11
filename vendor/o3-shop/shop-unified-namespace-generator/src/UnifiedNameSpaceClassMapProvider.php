<?php
/**
 * This file is part of O3-Shop.
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

namespace OxidEsales\UnifiedNameSpaceGenerator;

use \OxidEsales\UnifiedNameSpaceGenerator\UnifiedNamespaceClassMap\CommunityEditionUnifiedNamespaceClassMap;
use \OxidEsales\UnifiedNameSpaceGenerator\Exceptions\InvalidEditionException;

/**
 * Provides a map of classes in the unified namespace to edition class names depending on the shop's current edition.
 */
class UnifiedNameSpaceClassMapProvider
{

    /** @var \OxidEsales\Facts\Facts */
    private $facts = null;

    /**
     * @param \OxidEsales\Facts\Facts $facts
     */
    public function __construct(\OxidEsales\Facts\Facts $facts)
    {
        $this->facts = $facts;
    }

    /**
     * Return an array, which is mapping unified namespace class name as key to real edition namespace class name.
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getClassMap()
    {
        $shopEdition = $this->facts->getEdition();
        $unifiedNamespaceClassMap = null;

        switch ($shopEdition) {
            case \OxidEsales\UnifiedNameSpaceGenerator\Generator::COMMUNITY_EDITION:
                $unifiedNamespaceClassMap =
                    new CommunityEditionUnifiedNamespaceClassMap($this->facts);
        }

        if (is_null($unifiedNamespaceClassMap)) {
            throw new InvalidEditionException(
                'The O3-Shop edition could not be detected. Be sure to setup your O3-Shop correctly.'
            );
        }

        $editionSpecificUnifiedNamespaceClassMap = $unifiedNamespaceClassMap->getClassMap();

        return $editionSpecificUnifiedNamespaceClassMap;
    }
}
