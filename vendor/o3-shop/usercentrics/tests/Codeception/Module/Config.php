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

namespace OxidProfessionalServices\Usercentrics\Tests\Codeception\Module;

use Codeception\Lib\Interfaces\DependsOnModule;
use OxidProfessionalServices\Usercentrics\DataObject\Configuration;
use OxidProfessionalServices\Usercentrics\Service\Configuration\ConfigurationDao;
use OxidProfessionalServices\Usercentrics\Service\Configuration\YamlStorage;

/**
 * Class Config
 */
class Config extends \Codeception\Module implements DependsOnModule
{
    protected $requiredFields = ['shop_path', 'config_file'];
    protected $config = [
        'shop_path' => '',
        'config_file' => ''
    ];

    public function _depends()
    {
        return [];
    }

    private function getConfigManager(): ConfigurationDao
    {
        $storage =  new YamlStorage(
            $this->config['shop_path'],
            $this->config['config_file']
        );

        return new ConfigurationDao($storage);
    }

    public function getConfiguration(): Configuration
    {
        $configManager = $this->getConfigManager();
        return $configManager->getConfiguration();
    }

    public function putConfiguration(Configuration $configuration): void
    {
        $configManager = $this->getConfigManager();
        $configManager->putConfiguration($configuration);
    }
}
