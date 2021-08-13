<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\DependencyInjection;

use Klipper\Bundle\ApiBundle\Util\ControllerDefinitionUtil;
use Klipper\Module\ProductBundle\Controller\ApiProductCombinationController;
use Klipper\Module\ProductBundle\Controller\ApiProductController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperProductExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('product_manager.xml');
        $loader->load('price_manager.xml');
        $loader->load('doctrine_subscriber.xml');
        $loader->load('doctrine_delete_content_config.xml');
        $loader->load('upload_listener.xml');
        $loader->load('api_form.xml');

        ControllerDefinitionUtil::set($container, ApiProductController::class);
        ControllerDefinitionUtil::set($container, ApiProductCombinationController::class);
    }
}
