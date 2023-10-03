<?php

namespace Modules\ArtistSongs\Rules;

use Illuminate\Contracts\Validation\Rule;

class YearFormatRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Validate that the value is a valid year in "YYYY" format
        return preg_match('/^\d{4}$/', $value);
    }

    public function message()
    {
        return 'The :attribute must be a valid year in "YYYY" format.';
    }
}