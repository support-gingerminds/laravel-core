<?php

namespace Gingerminds\LaravelCore\Models;

interface SearchableModelInterface
{
    /**
     * @return array<string>
     */
    public static function getSearchableFields(): array;
}
