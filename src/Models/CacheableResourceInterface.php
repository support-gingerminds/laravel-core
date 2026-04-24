<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Models;

interface CacheableResourceInterface
{
    public static function getCacheKey(): string;

    public static function getCacheTtlSeconds(): ?int;
}
