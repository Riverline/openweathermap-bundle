## What is Riverline\OpenWeatherMapBundle

``Riverline\OpenWeatherMapBundle`` wraps open weather map php api in a Symfony bundle with configuration capabilities.

## Requirements

* PHP >= 8.2
* Symfony 6.4, 7.4 or 8.x (`symfony/http-kernel`, `symfony/config`, `symfony/dependency-injection`, `symfony/yaml`)
* cmfcmf/openweathermap-php-api 2.4

Symfony 8 itself requires PHP 8.4. For Symfony < 6.4 or PHP < 8.2, stay on `riverline/openweathermap-bundle` 1.x.

## Configuration

```yml
owm.curl.fetcher:
    class: Riverline\OpenWeatherMapBundle\Fetcher\CurlOwmFetcher

owm.cache:
    class: Riverline\OpenWeatherMapBundle\Cache\MemcachedOwmCache
    arguments:
        - '@cache'
        - '%kernel.environment%-owm-%%s'

riverline_open_weather_map:
    api_key: '%riverline_open_weather_map.api_key%'
    fetcher_service_id: owm.curl.fetcher
    lang: '%locale%'
    mode: json
    units: metric
    cache_service_id: owm.cache
    cache_ttl: '%riverline_open_weather_map.cache_ttl%'
```

The bundle registers the `riverline.openweathermap` service (`Riverline\OpenWeatherMapBundle\Service\OpenWeatherMapService`).

## Upgrading from 1.x

The configuration and the service are unchanged. 2.0 only raises the requirements (see above) and adds native return
types to `Configuration::getConfigTreeBuilder()` and `RiverlineOpenWeatherMapExtension::load()`, whose base class is
now the DependencyInjection component's `Extension`.

## Tests

```bash
composer install
vendor/bin/phpunit
```

The suite includes a container smoke test: it boots a kernel holding the bundle and resolves `riverline.openweathermap`,
with no network. CI runs it on PHP 8.2 and 8.4 against Symfony 6.4, 7.4 and 8.x.
