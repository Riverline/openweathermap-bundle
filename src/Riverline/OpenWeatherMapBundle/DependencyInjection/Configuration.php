<?php

namespace Riverline\OpenWeatherMapBundle\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('riverline_open_weather_map');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('riverline_open_weather_map');
        }

        $rootNode
            ->children()
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('units')
                    ->defaultValue('imperial')
                    ->validate()
                    ->ifNotInArray(array('imperial', 'metric'))
                        ->thenInvalid('Invalid units for riverline_open_weather_map "%s"')
                    ->end()
                ->end()
                ->scalarNode('fetcher_service_id')
                    ->defaultFalse()
                ->end()
                ->scalarNode('cache_service_id')
                    ->defaultFalse()
                ->end()
                ->integerNode('cache_ttl')
                    ->defaultValue(600)
                    ->min(0)
                    ->max(86400)
                ->end()
                ->scalarNode('lang')
                    ->defaultValue('en')
                    ->validate()
                    ->ifNotInArray(array('en', 'fr', 'ru', 'it', 'sp', 'ua', 'de', 'pt', 'ro', 'pl', 'fi', 'nl', 'bg', 'se', 'zh_tw', 'zh_cn', 'tr'))
                        ->thenInvalid('Invalid language for riverline_open_weather_map "%s"')
                    ->end()
                ->end()
                ->scalarNode('mode')
                    ->defaultValue('xml')
                    ->validate()
                    ->ifNotInArray(array('xml', 'json', 'html'))
                        ->thenInvalid('Invalid mode for riverline_open_weather_map "%s"')
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}