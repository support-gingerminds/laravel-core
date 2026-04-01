<?php

namespace Gingerminds\LaravelCore\ApiProvider;

use Illuminate\Http\Request;

interface ApiProviderInterface
{
    /**
     * @param array<mixed> $uriVariables
     * @param array<mixed> $context
     */
    public function addFilters(Request $request, array $uriVariables, array $context): void;
}
