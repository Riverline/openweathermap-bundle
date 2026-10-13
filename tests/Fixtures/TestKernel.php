<?php

namespace Riverline\OpenWeatherMapBundle\Tests\Fixtures;

use Riverline\OpenWeatherMapBundle\RiverlineOpenWeatherMapBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Smallest kernel that registers the bundle: no FrameworkBundle, one extension config, optional app services.
 * Each instance gets its own environment and cache directory, so every test compiles and dumps a fresh container.
 */
class TestKernel extends Kernel
{
    /**
     * Public alias to the (private) bundle service, the way Symfony's test container exposes private services.
     */
    public const SERVICE_ALIAS = 'test.riverline.openweathermap';

    private array $bundleConfig;

    private ?\Closure $registerServices;

    public function __construct(array $bundleConfig, ?\Closure $registerServices = null)
    {
        $this->bundleConfig = $bundleConfig;
        $this->registerServices = $registerServices;

        parent::__construct('test'.bin2hex(random_bytes(6)), false);
    }

    public function registerBundles(): iterable
    {
        return [new RiverlineOpenWeatherMapBundle()];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container): void {
            $container->loadFromExtension('riverline_open_weather_map', $this->bundleConfig);
            $container->setAlias(self::SERVICE_ALIAS, 'riverline.openweathermap')->setPublic(true);

            if (null !== $this->registerServices) {
                ($this->registerServices)($container);
            }
        });
    }

    public function getCacheDir(): string
    {
        return $this->getTmpDir().'/cache';
    }

    public function getLogDir(): string
    {
        return $this->getTmpDir().'/log';
    }

    public function getTmpDir(): string
    {
        return sys_get_temp_dir().'/riverline-openweathermap-bundle-tests/'.$this->getEnvironment();
    }
}
