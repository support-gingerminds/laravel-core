<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Models;

interface SearchableModelInterface
{
    /**
     * @return array<string>
     */
    public static function getSearchableFields(): array;
}
