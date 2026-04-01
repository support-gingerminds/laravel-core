<?php

namespace Gingerminds\LaravelCore\Models;

interface CacheableResourceInterface
{
    public static function getCacheKey(): string;

    public static function getCacheTtlSeconds(): ?int;
}
