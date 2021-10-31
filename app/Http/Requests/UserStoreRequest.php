<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use App\Rules\ProvinceRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {   
        $this->merge([
            'name'          => trim(preg_replace("/[^-'A-Za-z. ]/", "", $this->name)),
            'password'      => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email'         => trim($this->email),
            'birth_date'    => trim($this->birth_date),
            'line1'         => trim(preg_replace("/[^-'#.,;&:A-Za-zÀ-ÿ0-9 ]/", "", $this->line1)),
            'line2'         => trim(preg_replace("/[^-'#.,;&:A-Za-zÀ-ÿ0-9 ]/", "", $this->line2)),
            'city'          => Str::ucfirst(trim(preg_replace("/[^-',.;&A-Za-zÀ-ÿ ]/", "", $this->city))),
			'province'      => ucwords(trim(preg_replace("/[^-',.;&A-Za-zÀ-ÿ ]/", "", $this->province))),
			'country'       => Str::upper(trim(preg_replace("/[^-',.;&A-Za-zÀ-ÿ ]/", "", $this->country))),
			'postal_code'   => Str::upper(filter_var($this->postal_code, FILTER_SANITIZE_STRING)),
        ]);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'          => 'bail|required|string|max:100',
            'email'         => 'bail|required|email|unique:users,email',
            'birth_date'    => 'bail|required|date',
            'line1'         => 'bail|required|max:255',
            'line2'         => 'bail|sometimes|required|max:255',
            'city'          => 'bail|required|string|max:255',
            'province'      => [
                'bail',
                'required',
                'string',
                'max:255',
                new ProvinceRule($this->country), 
            ],
			'country'       => 'bail|required|string|in:US,CA',
			'postal_code'   => 'bail|required|string|max:10',
        ];
    }

    /**
     * Update the Request instance.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance(): \Illuminate\Contracts\Validation\Validator
    {
        return parent::getValidatorInstance()->after(function ($validator) {
            $this->merge([
                'age'               => Carbon::parse($this->birth_date)->age,
                'email_verified_at' => now(),   
            ]);
            Arr::forget($this, 'birth_date');
        });
    }
}
