<?php

namespace Gingerminds\LaravelCore\Models;

/**
 * @property-read int<0, max> $id
 */
interface ResourceModelInterface
{
    public const string RESOURCE_TYPE = '';

    /**
     * @return mixed
     */
    public function getKey();
}
