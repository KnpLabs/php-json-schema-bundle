<?php

declare(strict_types=1);

namespace KnpLabs\JsonSchemaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('knp_json_schema');

        $treeBuilder->getRootNode()
            ->children()
                ->enumNode('validator')
                    ->values(['OpisValidator', 'SwaggestValidator'])
                    ->defaultValue('SwaggestValidator')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
