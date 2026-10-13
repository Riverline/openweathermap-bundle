# Changelog

## 2.0.0

Runs on Symfony 6.4, 7.4 and 8.x. The configuration and the `riverline.openweathermap` service are unchanged.

### Changed

* Requires PHP >= 8.2 and `symfony/*` `^6.4 || ^7.4 || ^8.0`.
* Requires `cmfcmf/openweathermap-php-api` `^2.4`.
* Requires `symfony/yaml`: the extension loads its services from YAML, so applications without it failed to boot.
* `Configuration::getConfigTreeBuilder()` returns `TreeBuilder`, and `RiverlineOpenWeatherMapExtension::load()` returns
  `void`.
* `RiverlineOpenWeatherMapExtension` extends `Symfony\Component\DependencyInjection\Extension\Extension` instead of
  HttpKernel's `Extension`, which Symfony 8 removes.

### Fixed

* `OpenWeatherMapService` no longer creates a dynamic `$cacheClass` property (deprecated since PHP 8.2). The cache is
  kept in the declared `$cache` property.

### Added

* PHPUnit suite with a container smoke test, and a GitHub Actions matrix over PHP 8.2 / 8.4 and Symfony 6.4 / 7.4 / 8.x.
