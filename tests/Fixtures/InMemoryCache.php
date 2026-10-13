<?php

namespace Riverline\OpenWeatherMapBundle\Tests\Fixtures;

use Cmfcmf\OpenWeatherMap\AbstractCache;

/**
 * Stands in for MemcachedOwmCache, which needs ext-memcached and a server. Entries never expire: the TTL is ignored.
 */
class InMemoryCache extends AbstractCache
{
    private array $entries = [];

    public function isCached($url)
    {
        return array_key_exists($url, $this->entries);
    }

    public function getCached($url)
    {
        return $this->entries[$url];
    }

    public function setCached($url, $content)
    {
        $this->entries[$url] = $content;

        return true;
    }
}
