<?php

namespace Riverline\OpenWeatherMapBundle\Tests\Fixtures;

use Cmfcmf\OpenWeatherMap\Fetcher\FetcherInterface;

/**
 * Answers every request with a canned body and records the URLs it was asked for, so no test touches the network.
 */
class RecordingFetcher implements FetcherInterface
{
    public const BODY = '{"name":"Paris","main":{"temp":12.3}}';

    /**
     * @var list<string>
     */
    public array $urls = [];

    public function fetch($url)
    {
        $this->urls[] = $url;

        return self::BODY;
    }

    /**
     * @return array<string, string> the query parameters of the last request
     */
    public function lastQuery(): array
    {
        parse_str((string) parse_url(end($this->urls), PHP_URL_QUERY), $query);

        return $query;
    }
}
