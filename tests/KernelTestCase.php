<?php

namespace Riverline\OpenWeatherMapBundle\Tests;

use PHPUnit\Framework\TestCase;
use Riverline\OpenWeatherMapBundle\Service\OpenWeatherMapService;
use Riverline\OpenWeatherMapBundle\Tests\Fixtures\RecordingFetcher;
use Riverline\OpenWeatherMapBundle\Tests\Fixtures\TestKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class KernelTestCase extends TestCase
{
    private ?TestKernel $kernel = null;

    protected function tearDown(): void
    {
        if (null !== $this->kernel) {
            $this->kernel->shutdown();
            (new Filesystem())->remove($this->kernel->getTmpDir());
            $this->kernel = null;
        }
    }

    /**
     * Boots a kernel holding the bundle, which compiles and dumps the container like an application does.
     *
     * @param \Closure(ContainerBuilder): void|null $registerServices registers app services
     */
    protected function bootKernel(array $bundleConfig, ?\Closure $registerServices = null): ContainerInterface
    {
        $this->kernel = new TestKernel($bundleConfig, $registerServices);
        $this->kernel->boot();

        return $this->kernel->getContainer();
    }

    /**
     * Boots the bundle with a RecordingFetcher as its fetcher, so the URLs sent to OpenWeatherMap can be read back.
     *
     * @param \Closure(ContainerBuilder): void|null $registerServices registers more app services
     *
     * @return array{OpenWeatherMapService, RecordingFetcher}
     */
    protected function bootWithRecordingFetcher(array $bundleConfig, ?\Closure $registerServices = null): array
    {
        $container = $this->bootKernel(
            $bundleConfig + ['fetcher_service_id' => 'app.owm.fetcher'],
            static function (ContainerBuilder $container) use ($registerServices): void {
                $container->register('app.owm.fetcher', RecordingFetcher::class)->setPublic(true);

                if (null !== $registerServices) {
                    $registerServices($container);
                }
            },
        );

        return [$this->getOpenWeatherMapService($container), $container->get('app.owm.fetcher')];
    }

    protected function getOpenWeatherMapService(ContainerInterface $container): OpenWeatherMapService
    {
        $service = $container->get(TestKernel::SERVICE_ALIAS);
        self::assertInstanceOf(OpenWeatherMapService::class, $service);

        return $service;
    }
}
