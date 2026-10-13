<?php

namespace Riverline\OpenWeatherMapBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends KernelTestCase
{
    public static function invalidConfigs(): iterable
    {
        yield 'missing api_key' => [[]];
        yield 'empty api_key' => [['api_key' => '']];
        yield 'unknown units' => [['api_key' => 'test-api-key', 'units' => 'kelvin']];
        yield 'unknown lang' => [['api_key' => 'test-api-key', 'lang' => 'xx']];
        yield 'unknown mode' => [['api_key' => 'test-api-key', 'mode' => 'csv']];
        yield 'negative cache_ttl' => [['api_key' => 'test-api-key', 'cache_ttl' => -1]];
        yield 'cache_ttl over a day' => [['api_key' => 'test-api-key', 'cache_ttl' => 86401]];
    }

    #[DataProvider('invalidConfigs')]
    public function testInvalidConfigIsRejectedAtBoot(array $bundleConfig): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->bootKernel($bundleConfig);
    }

    public function testUnsetSettingsFallBackToImperialEnglishXml(): void
    {
        [$service, $fetcher] = $this->bootWithRecordingFetcher(['api_key' => 'test-api-key']);

        $service->getRawWeatherData('Paris');

        self::assertSame(
            ['q' => 'Paris', 'units' => 'imperial', 'lang' => 'en', 'mode' => 'xml', 'APPID' => 'test-api-key'],
            $fetcher->lastQuery(),
        );
    }
}
