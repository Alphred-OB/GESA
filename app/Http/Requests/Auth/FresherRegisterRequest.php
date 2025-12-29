<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class FresherRegisterRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:pending_registrations,username', 'unique:users,username'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                // Block university emails - this is for freshers only
                'not_regex:/^[a-z]{2}-[a-z0-9]+@st\.umat\.edu\.gh$/i',
                'unique:users,email',
                'unique:pending_registrations,email',
            ],
            'phone_number' => [
                'nullable', 
                'digits_between:9,11',
                'unique:users,phone_number',
                'unique:pending_registrations,phone_number',
            ],
            'index_number' => ['required', 'digits_between:9,11', 'unique:pending_registrations,index_number', 'unique:users,index_number'],
            'class' => ['required', \Illuminate\Validation\Rule::in(['Geomatic Engineering', 'Land Administration', 'Spatial Planning'])],
            'year' => ['required', \Illuminate\Validation\Rule::in(['1', '2', '3', '4'])],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'reason' => ['required', 'string', 'max:500'],
            'student_id' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Max 2MB
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
            'username.unique' => 'This username is already taken. Please choose a different one.',
            'email.not_regex' => 'Please use a personal email address. If you have access to your university email, use the regular registration form instead.',
            'student_id.image' => 'Student ID must be an image file.',
            'student_id.max' => 'Student ID file size must not exceed 2MB.',
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
