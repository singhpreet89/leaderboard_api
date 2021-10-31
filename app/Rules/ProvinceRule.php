<?php

namespace App\Rules;

use App\Http\Constants\Constants;
use Illuminate\Contracts\Validation\Rule;

class ProvinceRule implements Rule
{
    /**
     * Create a new rule instance.
     * @param string $country
     * @return void
     */
    public function __construct(string $country)
    {
        $this->country = $country;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {   
        if($this->country === "US" && in_array($value, config('constants.US_STATES'))) {
            return true;
        } else if($this->country === "CA" && in_array($value, config('constants.CA_PROVINCES'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The province is invalid.';
    }
}
