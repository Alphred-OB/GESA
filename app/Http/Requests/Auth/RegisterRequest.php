<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'phone_number' => ['nullable', 'digits_between:9,11'],
            'index_number' => ['required', 'digits_between:9,11'],
            'class' => ['required', Rule::in(['Geomatic Engineering', 'Land Administration', 'Spatial Planning'])],
            'year' => ['required', Rule::in(['1', '2', '3', '4'])],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'accept_terms' => ['accepted'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'accept_terms' => $this->boolean('accept_terms'),
        ]);
    }
}
