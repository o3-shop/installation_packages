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

namespace OxidEsales\UnifiedNameSpaceGenerator\UnifiedNamespaceClassMap;

use OxidEsales\UnifiedNameSpaceGenerator\Exceptions\InvalidUnifiedNamespaceClassMapException;

/**
 * Class CommunityEditionUnifiedNamespaceClassMap
 *
 * Returns the O3-Shop Community Edition specific UnifiedNamespaceClassMap
 *
 * @package OxidEsales\UnifiedNameSpaceGenerator\UnifiedNamespaceClassMap
 */
class CommunityEditionUnifiedNamespaceClassMap
{

    /** @var \OxidEsales\Facts\Facts */
    protected $facts = null;

    protected $editionText = "O3-Shop Community Edition was chosen.";

    /**
     * @param \OxidEsales\Facts\Facts $facts
     */
    public function __construct(\OxidEsales\Facts\Facts $facts)
    {
        $this->facts = $facts;
    }

    /**
     * @return array The merged contents of the file UnifiedNamespaceClassMap.php of the
     *               O3-Shop Community edition
     *
     * @throws \Exception
     */
    public function getClassMap()
    {
        $communityEditionSourcePath = $this->facts->getCommunityEditionSourcePath();

        return $this->resolveUnifiedNamespaceClassMap($communityEditionSourcePath);
    }

    /**
     * @param string $pathToSourceDirectory
     *
     * @return string
     */
    protected function getFullPathFromSourceDirectoryToUnifiedNamespaceClassMap($pathToSourceDirectory)
    {
        $fullPath = $pathToSourceDirectory . DIRECTORY_SEPARATOR .
                    'Core' . DIRECTORY_SEPARATOR .
                    'Autoload' . DIRECTORY_SEPARATOR .
                    'UnifiedNameSpaceClassMap.php';

        return $fullPath;
    }

    /**
     * @param string $absolutePathToSourceDirectory
     *
     * @return array The UnifiedNamespaceClassMap
     *
     * @throws \Exception
     */
    protected function resolveUnifiedNamespaceClassMap($absolutePathToSourceDirectory)
    {
        $fullPathToUnifiedNamespaceClassMapFile = $this->getFullPathFromSourceDirectoryToUnifiedNamespaceClassMap(
            $absolutePathToSourceDirectory
        );

        if (!is_readable($fullPathToUnifiedNamespaceClassMapFile)) {
            throw new InvalidUnifiedNamespaceClassMapException(
                $this->editionText .
                ' But the file ' . $fullPathToUnifiedNamespaceClassMapFile .
                ' is not readable or does not exist',
                \OxidEsales\UnifiedNameSpaceGenerator\Generator::ERROR_CODE_INVALID_UNIFIED_NAMESPACE_CLASS_MAP
            );
        }

        $unifiedNamespaceClassMap = include $fullPathToUnifiedNamespaceClassMapFile;

        if (!is_array($unifiedNamespaceClassMap)) {
            throw new InvalidUnifiedNamespaceClassMapException(
                $this->editionText .
                ' But the file ' . $fullPathToUnifiedNamespaceClassMapFile . ' is not an array.',
                \OxidEsales\UnifiedNameSpaceGenerator\Generator::ERROR_CODE_INVALID_UNIFIED_NAMESPACE_CLASS_MAP
            );
        }

        return $unifiedNamespaceClassMap;
    }
}
