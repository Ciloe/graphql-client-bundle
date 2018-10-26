<?php

namespace GraphClientBundle\Cache;

use GraphClientPhp\Cache\BasicCache;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var BasicCache
     */
    private $clientCache;

    /**
     * @param BasicCache $clientCache
     */
    public function __construct(BasicCache $clientCache)
    {
        $this->clientCache = $clientCache;
    }

    /**
     * {@inheritsDoc}
     */
    public function warmUp($cacheDir): void
    {
        $this->clientCache->warmUp();
    }

    /**
     * {@inheritsDoc}
     */
    public function isOptional(): bool
    {
        return false;
    }
}
