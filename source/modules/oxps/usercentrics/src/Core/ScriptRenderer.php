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

namespace OxidProfessionalServices\Usercentrics\Core;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidProfessionalServices\Usercentrics\Exception\WidgetsNotSupported;
use OxidProfessionalServices\Usercentrics\Service\RendererInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ScriptRenderer extends ScriptRenderer_parent
{

    protected function getContainer(): ContainerInterface
    {
        return ContainerFactory::getInstance()->getContainer();
    }

    protected function getRendererService(): RendererInterface
    {
        $container = $this->getContainer();
        /** @var RendererInterface */
        return $container->get(RendererInterface::class);
    }

    /**
     * Enclose with script tag or add in function for wiget.
     *
     * @todo: remove ServiceNotFoundException from catch when service availability during module activation fixed
     *
     * @param string $scriptsOutput javascript to be enclosed.
     * @param string $widget        widget name.
     * @param bool   $isAjaxRequest is ajax request
     *
     * @return string
     */
    protected function enclose($scriptsOutput, $widget, $isAjaxRequest)
    {
        try {
            $service = $this->getRendererService();
            $result = $service->encloseScriptSnippet($scriptsOutput, $widget, $isAjaxRequest);
        } catch (WidgetsNotSupported | ServiceNotFoundException $exception) {
            $result = parent::enclose($scriptsOutput, $widget, $isAjaxRequest);
        }

        return $result;
    }

    /**
     * Form output for includes.
     *
     * @todo: remove ServiceNotFoundException from catch when service availability during module activation fixed
     *
     * @param array<int,array<string>> $includes String files to include.
     * @param string $widget   Widget name.
     *
     * @return string
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function formFilesOutput($includes, $widget)
    {
        try {
            $service = $this->getRendererService();
            $result = $service->formFilesOutput($includes, $widget);
        } catch (WidgetsNotSupported | ServiceNotFoundException $exception) {
            $result = parent::formFilesOutput($includes, $widget);
        }

        return $result;
    }
}
