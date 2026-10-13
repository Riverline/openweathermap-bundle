<?php

namespace Riverline\OpenWeatherMapBundle\Service;

use Cmfcmf\OpenWeatherMap;

class OpenWeatherMapService
{
    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var string
     */
    protected $units;

    /**
     * @var OpenWeatherMap\Fetcher\FetcherInterface
     */
    protected $fetcher;

    /**
     * @var OpenWeatherMap\AbstractCache
     */
    protected $cache;

    /**
     * @var int
     */
    protected $cacheTtl;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @var OpenWeatherMap
     */
    protected $service;

    /**
     *
     * There are three ways to specify the place to get weather information for:
     * - Use the city name: $query must be a string containing the city name.
     * - Use the city id: $query must be an integer containing the city id.
     * - Use the coordinates: $query must be an associative array containing the 'lat' and 'lon' values.
     *
     *
     * @param $api_key
     * @param $units
     * @param $fetcher
     * @param OpenWeatherMap\AbstractCache $cache
     * @param $cacheTtl
     * @param $lang
     * @param $mode
     */
    public function __construct($api_key, $units, $fetcher, $cache, $cacheTtl, $lang, $mode)
    {
        $this->api_key      = $api_key;
        $this->units        = $units;
        $this->fetcher      = $fetcher;
        $this->cache        = $cache;
        $this->cacheTtl     = $cacheTtl;
        $this->lang         = $lang;
        $this->mode         = $mode;

        $this->service  = new OpenWeatherMap($this->fetcher, $this->cache, $this->cacheTtl);
    }

    /**
     * Returns the current weather at the place you specified as an object.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     * @return OpenWeatherMap\CurrentWeather
     * @throws OpenWeatherMap\Exception
     */
    public function getWeather($query, $units=null, $lang=null)
    {
        return $this->service->getWeather(
            $query,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key);
    }

    /**
     * Returns the current weather at the place you specified as an object.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param int              $days  For how much days you want to get a forecast. Default 1, maximum: 14.
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     * @return OpenWeatherMap\WeatherForecast
     * @throws OpenWeatherMap\Exception
     */
    public function getWeatherForecast($query, $days = 1, $units=null, $lang=null)
    {
        return $this->service->getWeatherForecast(
            $query,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key,
            $days
        );
    }

    /**
     * Returns the weather history for the place you specified as an object.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param \DateTime        $start
     * @param int              $endOrCount
     * @param string           $type
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     *
     * @return OpenWeatherMap\WeatherForecast
     * @throws OpenWeatherMap\Exception
     */
    public function getWeatherHistory($query, \DateTime $start, $endOrCount = 1, $type = 'hour', $units=null, $lang=null)
    {
        return $this->service->getWeatherHistory(
            $query,
            $start,
            $endOrCount,
            $type,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key
        );
    }

    /**
     * Directly returns the xml/json/html string returned by OpenWeatherMap for the current weather.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     * @param string           $mode  The format of the data fetched. Possible values are 'json', 'html' and 'xml' (default).
     * @return string
     */
    public function getRawWeatherData($query, $units=null, $lang=null, $mode=null)
    {
        return $this->service->getRawWeatherData(
            $query,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key,
            ((empty($mode)) ? $this->mode : $mode)
        );
    }

    /**
     * Directly returns the xml/json/html string returned by OpenWeatherMap for the hourly forecast.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     * @param string           $mode  The format of the data fetched. Possible values are 'json', 'html' and 'xml' (default).
     * @return string
     */
    public function getRawHourlyForecastData($query, $units=null, $lang=null, $mode=null)
    {
        return $this->service->getRawHourlyForecastData(
            $query,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key,
            ((empty($mode)) ? $this->mode : $mode)
        );
    }

    /**
     * Directly returns the xml/json/html string returned by OpenWeatherMap for the daily forecast.
     *
     * @param array|int|string $query The place to get weather information for. For possible values see below.
     * @param int              $cnt   How many days of forecast shall be returned? Maximum (and default): 14
     * @param string           $units Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang  The language to use for descriptions, default is 'en'. For possible values see below.
     * @param string           $mode  The format of the data fetched. Possible values are 'json', 'html' and 'xml' (default)
     *
     * @return string
     */
    public function getRawDailyForecastData($query, $cnt=14, $units=null, $lang=null, $mode=null)
    {
        return $this->service->getRawDailyForecastData(
            $query,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key,
            ((empty($mode)) ? $this->mode : $mode),
            $cnt
        );
    }

    /**
     * Directly returns the xml/json/html string returned by OpenWeatherMap for the daily forecast.
     *
     * @param array|int|string $query           The place to get weather information for. For possible values see below.
     * @param \DateTime        $start           The \DateTime object of the date to get the first weather information from.
     * @param \DateTime|int    $endOrCount      Can be either a \DateTime object representing the end of the period to
     *                                          receive weather history data for or an integer counting the number of
     *                                          reports requested.
     * @param string           $type            The period of the weather history requested. Can be either be either "tick",
     *                                          "hour" or "day".
     * @param string           $units           Can be either 'metric' or 'imperial' (default). This affects almost all units returned.
     * @param string           $lang            The language to use for descriptions, default is 'en'. For possible values see below.
     * @return string
     */
    public function getRawWeatherHistory($query, \DateTime $start, $endOrCount=1, $type='hour', $units=null, $lang=null)
    {
        return $this->service->getRawWeatherHistory(
            $query,
            $start,
            $endOrCount,
            $type,
            ((empty($units)) ? $this->units : $units),
            ((empty($lang)) ? $this->lang : $lang),
            $this->api_key
        );
    }
}
