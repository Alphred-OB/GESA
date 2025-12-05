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
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:100',
                // Enforce university email domain
                'regex:/^[a-z]{2}-[a-z0-9]+@st\.umat\.edu\.gh$/i',
                // Custom validation to match email prefix with selected class
                function ($attribute, $value, $fail) {
                    $class = $this->input('class');
                    if (!$class) {
                        return; // Skip if class is not selected
                    }

                    // Extract the prefix from the email (first 2 characters before the dash)
                    if (preg_match('/^([a-z]{2})-/i', $value, $matches)) {
                        $emailPrefix = strtoupper($matches[1]);
                        
                        // Map class to expected prefix
                        $classPrefixMap = [
                            'Geomatic Engineering' => 'GM',
                            'Spatial Planning' => 'SP',
                            'Land Administration' => 'LA',
                        ];
                        
                        $expectedPrefix = $classPrefixMap[$class] ?? null;
                        
                        if ($expectedPrefix && $emailPrefix !== $expectedPrefix) {
                            $fail("Your email prefix ({$emailPrefix}) does not match your selected program. Expected {$expectedPrefix} for {$class}.");
                        }
                    }
                },
            ],
            'phone_number' => ['nullable', 'digits_between:9,11'],
            'index_number' => ['required', 'digits_between:9,11'],
            'class' => ['required', Rule::in(['Geomatic Engineering', 'Land Administration', 'Spatial Planning'])],
            'year' => ['required', Rule::in(['1', '2', '3', '4'])],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'accept_terms' => ['accepted'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.regex' => 'You must use your official university email address (e.g., gm-yourname1234@st.umat.edu.gh). If you don\'t have access to your university email, please contact the administrator.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
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
