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

/**
 * Provides a map of classes in the unified namespace to backwards compatible classes
 */
class BackwardsCompatibilityClassMapProvider
{

    const ERROR_CODE_MISSING_BACKWARDS_COMPATIBILITY_CLASS_MAP = 5;
    const ERROR_CODE_INVALID_BACKWARDS_COMPATIBILITY_CLASS_MAP = 7;

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
     * Return a map of classes in the unified namespace to backwards compatibility classes (e.g. oxArticle)
     *
     * @return array|bool
     *
     * @throws \Exception
     */
    public function getClassMap()
    {
        $communityEditionSourcePath = $this->facts->getCommunityEditionSourcePath();
        $backwardsCompatibilityClassMapFile = $communityEditionSourcePath . DIRECTORY_SEPARATOR .
                                              'Core' . DIRECTORY_SEPARATOR .
                                              'Autoload' . DIRECTORY_SEPARATOR .
                                              'BackwardsCompatibilityClassMap.php';

        if (!is_readable($backwardsCompatibilityClassMapFile)) {
            throw new \OxidEsales\UnifiedNameSpaceGenerator\Exceptions\InvalidBackwardsCompatibilityClassMapException(
                'Backwards compatibility class map file ' . $backwardsCompatibilityClassMapFile .
                ' is not readable or does not exist',
                static::ERROR_CODE_MISSING_BACKWARDS_COMPATIBILITY_CLASS_MAP
            );
        }

        $backwardsCompatibilityClassMap =
            include $backwardsCompatibilityClassMapFile;

        if (!is_array($backwardsCompatibilityClassMap)) {
            throw new \OxidEsales\UnifiedNameSpaceGenerator\Exceptions\InvalidBackwardsCompatibilityClassMapException(
                'Backwards compatibility class map is not an array ',
                static::ERROR_CODE_INVALID_BACKWARDS_COMPATIBILITY_CLASS_MAP
            );
        }

        return array_flip($backwardsCompatibilityClassMap);
    }
}
