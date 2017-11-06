<?php
declare(strict_types=1);

/**
 * This file is part of the JasperReportBundle.
 *
 * (c) Wilson Ng <frozalid.wilson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WilsonNg\JasperReportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The configuration of the bundle.
 * 
 * @author Wilson Ng <frozalid.wilson@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wilson_ng_jasper_report');

        $rootNode
            ->children()
                ->arrayNode('server')
                    ->isRequired()
                    ->children()
                        ->scalarNode('host')->isRequired()->end()
                        ->scalarNode('port')->isRequired()->end()
                        ->scalarNode('scheme')->defaultValue('http')->end()
                        ->scalarNode('username')->isRequired()->end()
                        ->scalarNode('password')->isRequired()->end()
                        ->scalarNode('organization')->defaultNull()->end()
                        ->scalarNode('base_url')->isRequired()->end()
                        ->scalarNode('timeout')->defaultValue(60)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
