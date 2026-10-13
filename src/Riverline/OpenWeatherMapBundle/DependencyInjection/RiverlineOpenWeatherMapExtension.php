<?php
namespace Riverline\OpenWeatherMapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class RiverlineOpenWeatherMapExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('riverline_open_weather_map.api_key', $config['api_key']);
        $container->setParameter('riverline_open_weather_map.units', $config['units']);

        if (!empty($config['fetcher_service_id'])) {
            $container->setAlias('riverline_open_weather_map.fetcher', $config['fetcher_service_id']);
        }

        if (!empty($config['cache_service_id'])) {
            $container->setAlias('riverline_open_weather_map.cache', $config['cache_service_id']);
        }

        $container->setParameter('riverline_open_weather_map.cache_ttl', $config['cache_ttl']);
        $container->setParameter('riverline_open_weather_map.lang', $config['lang']);
        $container->setParameter('riverline_open_weather_map.mode', $config['mode']);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
