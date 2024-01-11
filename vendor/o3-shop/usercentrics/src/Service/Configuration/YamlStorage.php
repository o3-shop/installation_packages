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

namespace OxidProfessionalServices\Usercentrics\Service\Configuration;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

final class YamlStorage implements StorageInterface
{
    /** @var Dumper */
    private $dumper;

    /** @var Parser */
    private $parser;

    /** @var string */
    private $directory;

    /** @var string */
    private $fileName;

    public function __construct(string $directory, string $fileName)
    {
        $this->dumper = new Dumper();
        $this->parser = new Parser();
        $this->directory = $directory;
        $this->fileName = $fileName;
    }


    public function getData(): array
    {
        if (file_exists($this->getConfigurationFilePath())) {
            /** @var mixed $result */
            $result = $this->parser->parseFile($this->getConfigurationFilePath());
        }

        if (!isset($result) || !is_array($result)) {
            $result = [];
        }

        return $result;
    }

    public function putData(array $data): void
    {
        $yamlData = $this->dumper->dump($data, 2);

        file_put_contents($this->getConfigurationFilePath(), $yamlData);
    }

    private function getConfigurationFilePath(): string
    {
        return $this->directory . DIRECTORY_SEPARATOR . $this->fileName;
    }
}
