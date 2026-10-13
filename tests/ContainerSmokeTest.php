<?php

namespace Riverline\OpenWeatherMapBundle\Tests;

use Riverline\OpenWeatherMapBundle\Fetcher\CurlOwmFetcher;
use Riverline\OpenWeatherMapBundle\Tests\Fixtures\InMemoryCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerSmokeTest extends KernelTestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['OPENWEATHERMAP_API_KEY'], $_ENV['OPENWEATHERMAP_CACHE_TTL']);

        parent::tearDown();
    }

    public function testServiceResolvesWithEnvVarSettingsAndAppFetcherAndCache(): void
    {
        $_ENV['OPENWEATHERMAP_API_KEY'] = 'test-api-key';
        $_ENV['OPENWEATHERMAP_CACHE_TTL'] = '600';

        $container = $this->bootKernel(
            [
                'api_key' => '%env(OPENWEATHERMAP_API_KEY)%',
                'fetcher_service_id' => 'app.owm.fetcher',
                'cache_service_id' => 'app.owm.cache',
                'mode' => 'json',
                'units' => 'metric',
                'cache_ttl' => '%env(int:OPENWEATHERMAP_CACHE_TTL)%',
            ],
            static function (ContainerBuilder $container): void {
                $container->register('app.owm.fetcher', CurlOwmFetcher::class);
                $container->register('app.owm.cache', InMemoryCache::class);
            },
        );

        $this->getOpenWeatherMapService($container);
    }

    public function testServiceResolvesWithOnlyAnApiKey(): void
    {
        $container = $this->bootKernel(['api_key' => 'test-api-key']);

        $this->getOpenWeatherMapService($container);
    }
}
