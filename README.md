## What is Riverline\OpenWeatherMapBundle

``Riverline\OpenWeatherMapBundle`` wraps open weather map php api in a symfony 2 bundle with configuration capabilities.

## Requirements

* PHP 5.3
* symfony/http-kernel >= 2.0
* symfony/config >= 2.0
* symfony/dependency-injection >= 2.0
* cmfcmf/openweathermap-php-api 2.0

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
