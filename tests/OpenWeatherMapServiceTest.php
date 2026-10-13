<?php

namespace Riverline\OpenWeatherMapBundle\Tests;

use Riverline\OpenWeatherMapBundle\Tests\Fixtures\InMemoryCache;
use Riverline\OpenWeatherMapBundle\Tests\Fixtures\RecordingFetcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OpenWeatherMapServiceTest extends KernelTestCase
{
    public function testRawWeatherDataIsFetchedWithTheConfiguredSettings(): void
    {
        [$service, $fetcher] = $this->bootWithRecordingFetcher([
            'api_key' => 'test-api-key',
            'units' => 'metric',
            'lang' => 'fr',
            'mode' => 'json',
        ]);

        $body = $service->getRawWeatherData('Paris');

        self::assertSame(RecordingFetcher::BODY, $body);
        self::assertSame(
            ['q' => 'Paris', 'units' => 'metric', 'lang' => 'fr', 'mode' => 'json', 'APPID' => 'test-api-key'],
            $fetcher->lastQuery(),
        );
    }

    public function testPerCallSettingsWinOverTheConfiguredOnes(): void
    {
        [$service, $fetcher] = $this->bootWithRecordingFetcher([
            'api_key' => 'test-api-key',
            'units' => 'metric',
            'lang' => 'fr',
            'mode' => 'json',
        ]);

        $service->getRawWeatherData('Paris', 'imperial', 'de', 'xml');

        self::assertSame(
            ['q' => 'Paris', 'units' => 'imperial', 'lang' => 'de', 'mode' => 'xml', 'APPID' => 'test-api-key'],
            $fetcher->lastQuery(),
        );
    }

    public function testRepeatedRequestIsServedFromTheConfiguredCache(): void
    {
        [$service, $fetcher] = $this->bootWithRecordingFetcher(
            ['api_key' => 'test-api-key', 'cache_service_id' => 'app.owm.cache', 'cache_ttl' => 600],
            static function (ContainerBuilder $container): void {
                $container->register('app.owm.cache', InMemoryCache::class);
            },
        );

        $first = $service->getRawWeatherData('Paris');
        $second = $service->getRawWeatherData('Paris');

        self::assertSame(RecordingFetcher::BODY, $first);
        self::assertSame(RecordingFetcher::BODY, $second);
        self::assertCount(1, $fetcher->urls);
    }

    public function testRepeatedRequestIsFetchedAgainWithoutACache(): void
    {
        [$service, $fetcher] = $this->bootWithRecordingFetcher(['api_key' => 'test-api-key']);

        $service->getRawWeatherData('Paris');
        $service->getRawWeatherData('Paris');

        self::assertCount(2, $fetcher->urls);
    }
}
