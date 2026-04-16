<?php

namespace Gingerminds\LaravelCore\Models;

interface FilterableModelInterface
{
    /**
     * @return array<mixed>
     */
    public static function getFilters(): array;
}
