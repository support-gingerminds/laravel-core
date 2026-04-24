<?php

declare(strict_types=1);

namespace Gingerminds\LaravelCore\Http\Requests\ValueConverter;

use DateTime;

trait DateConverterTrait
{
    private function convertDate(?string $date, string $format = 'Y-m-d'): ?string
    {
        if ($date !== null && $date !== '') {
            $formattedDate = new DateTime($date);

            return $formattedDate->format($format);
        }

        return null;
    }
}
