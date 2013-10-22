<?php

namespace Anh\Bundle\TaggableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AnhTaggableExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Add mapping for Tag and Tagging entities from doctrine extension.
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = array(
            'orm' => array(
                'mappings' => array(
                    'anh_taggable' => array(
                        'type' => 'annotation',
                        'prefix' => 'Anh\Taggable\Entity',
                        'dir' => '%kernel.root_dir%/../vendor/anh/doctrine-extensions-taggable/lib/Anh/Taggable/Entity',
                        'alias' => 'AnhTaggable',
                        'is_bundle' => false
                    )
                )
            )
        );
        $container->prependExtensionConfig('doctrine', $config);
    }
}
