<?php

namespace Gingerminds\LaravelCore\Http\Requests\ValueConverter;

trait DecimalConverterTrait
{
    private function convertDecimal(?string $decimalValue, int $decimalCount = 2): ?string
    {
        if ($decimalValue !== null && $decimalValue !== '') {
            return number_format((float)$decimalValue, $decimalCount, '.', '');
        }

        return null;
    }
}
