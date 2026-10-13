<?php

namespace Riverline\OpenWeatherMapBundle\Cache;

use Cmfcmf\OpenWeatherMap\AbstractCache;

/**
 * Example cache implementation.
 *
 */
class MemcachedOwmCache extends AbstractCache
{

    /**
     * @param \Memcached $cacheService
     */
    protected $cacheService;

    /**
     * @var string
     */
    protected $keyPattern;

    /**
     * @param $cacheService
     * @param string $keyPattern
     */
    public function __construct(\Memcached $cacheService, $keyPattern='%s')
    {
        $this->cacheService = $cacheService;
        $this->keyPattern = $keyPattern;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function getKey($value)
    {
        return sprintf($this->keyPattern, md5($value));
    }

    /**
     * @inheritdoc
     */
    public function isCached($url)
    {
        // data is cached if result is not false OR result code is success
        if (false !== $this->cacheService->get($this->getKey($url))
            || $this->cacheService->getResultCode() == \Memcached::RES_SUCCESS) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getCached($url)
    {
        return $this->cacheService->get($this->getKey($url));
    }

    /**
     * @inheritdoc
     */
    public function setCached($url, $content)
    {
        return $this->cacheService->set($this->getKey($url), $content, $this->seconds);
    }
}