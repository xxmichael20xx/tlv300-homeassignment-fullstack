<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class DomainRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Regex for domain validation
        $domainPattern = '/^([a-z\d]([a-z\d-]*[a-z\d])*)\.([a-z]{2,})(\.[a-z]{2,})?$/i';

        // Validate if it's a URL
        $isUrl = filter_var($value, FILTER_VALIDATE_URL);

        // Check if the value is either a valid domain or a valid URL
        if (!preg_match($domainPattern, $value) && !$isUrl) {
            $fail(__('The :attribute must be a valid domain or URL.'));
        }
    }
}
