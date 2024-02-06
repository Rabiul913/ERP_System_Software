<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FromToDateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fromDate = request()->from_date;
        // dd(request()->from_date);
        $toDate = $value;
        if(($fromDate > $toDate)){
            // 'The :attribute must be between 1980 to '.date('Y').'.');
            $fail( 'The :attribute must be equal to or after the from date');
        }
    }
}
