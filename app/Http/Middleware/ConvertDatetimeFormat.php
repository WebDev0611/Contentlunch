<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class ConvertDatetimeFormat extends ConvertDateFormat
{
    protected function transform($key, $value)
    {
        if ($key === $this->dateField && $value) {
            return Carbon::createFromFormat($this->dateFormat, $value)->format('Y-m-d H:i:s');
        }

        return $value;
    }
}
