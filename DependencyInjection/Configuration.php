<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('sonata_page')->children();

        $node
            ->scalarNode('multisite')->isRequired()->end()

            ->arrayNode('ignore_route_patterns')
                ->addDefaultsIfNotSet()
                ->prototype('scalar')
                    ->defaultValue(array(
                        '/(.*)admin(.*)/',   # ignore admin route, ie route containing 'admin'
                        '/^_(.*)/',          # ignore symfony routes
                    ))
                ->end()
            ->end()

            ->arrayNode('ignore_routes')
                ->addDefaultsIfNotSet()
                ->prototype('scalar')
                    ->defaultValue(array(
                        'sonata_page_esi_cache',
                        'sonata_page_js_sync_cache',
                        'sonata_page_js_async_cache',
                        'sonata_page_apc_cache',
                    ))
                ->end()
            ->end()

            ->arrayNode('ignore_uri_patterns')
                ->addDefaultsIfNotSet()
                ->prototype('scalar')
                    ->defaultValue(array(
                        '/admin(.*)/',   # ignore admin route, ie route containing 'admin'
                    ))
                ->end()
            ->end()


            ->arrayNode('cache_invalidation')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('service')->defaultValue('sonata.cache.invalidation.simple')->end()
                    ->scalarNode('recorder')->defaultValue('sonata.cache.recorder')->end()
                    ->arrayNode('classes')
                        ->useAttributeAsKey('id')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()

            ->scalarNode('default_template')->isRequired()->end()

            ->arrayNode('templates')
                ->isRequired()
                ->useAttributeAsKey('id')
                ->prototype('array')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('path')->end()
                    ->end()
                ->end()
            ->end()

            ->arrayNode('page_defaults')
                ->useAttributeAsKey('id')
                ->prototype('array')
                    ->children()
                        ->booleanNode('decorate')->defaultValue(true)->end()
                    ->end()
                ->end()
            ->end()

            ->arrayNode('caches')
                ->children()
                    ->arrayNode('esi')
                        ->children()
                            ->arrayNode('servers')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->arrayNode('catch_exceptions')
                ->useAttributeAsKey('id')
                ->prototype('variable')->isRequired()->end()
            ->end()

            ->arrayNode('class')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('page')->defaultValue('Application\\Sonata\\PageBundle\\Entity\\Page')->end()
                    ->scalarNode('snapshot')->defaultValue('Application\\Sonata\\PageBundle\\Entity\\Snapshot')->end()
                    ->scalarNode('block')->defaultValue('Application\\Sonata\\PageBundle\\Entity\\Block')->end()
                    ->scalarNode('site')->defaultValue('Application\\Sonata\\PageBundle\\Entity\\Site')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
