<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Pole Imię i Nazwisko / Nazwa klienta jest wymagane.',
            'email.required' => 'Adres e-mail jest wymagany.',
            'email.email' => 'Podany adres e-mail ma nieprawidłowy format.',
            'email.unique' => 'Klient o podanym adresie e-mail już istnieje w systemie.',
            'name.max' => 'Nazwa klienta nie może przekraczać 255 znaków.',
            'email.max' => 'Adres e-mail nie może przekraczać 255 znaków.',
        ];
    }
}
