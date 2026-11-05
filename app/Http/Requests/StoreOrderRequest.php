<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
            'order_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:nowe,w realizacji,zrealizowane'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.unit_price' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Wybór klienta jest wymagany.',
            'client_id.exists' => 'Wybrany klient nie istnieje w bazie.',
            'order_date.required' => 'Data zamówienia jest wymagana.',
            'order_date.date' => 'Data zamówienia musi być poprawną datą.',
            'status.required' => 'Status zamówienia jest wymagany.',
            'status.in' => 'Wybrany status zamówienia jest nieprawidłowy.',
            'notes.max' => 'Notatki nie mogą przekraczać 1000 znaków.',
            'products.required' => 'Zamówienie musi zawierać co najmniej jedną pozycję (produkt).',
            'products.array' => 'Lista pozycji zamówienia jest niepoprawna.',
            'products.min' => 'Zamówienie musi zawierać co najmniej jeden produkt.',
            'products.*.product_id.required' => 'Wybór produktu w pozycji jest wymagany.',
            'products.*.product_id.exists' => 'Wybrany produkt nie istnieje w bazie.',
            'products.*.quantity.required' => 'Ilość sztuk jest wymagana.',
            'products.*.quantity.integer' => 'Ilość sztuk musi być liczbą całkowitą.',
            'products.*.quantity.min' => 'Ilość sztuk musi wynosić co najmniej 1.',
            'products.*.unit_price.required' => 'Cena jednostkowa pozycji jest wymagana.',
            'products.*.unit_price.numeric' => 'Cena jednostkowa musi być liczbą.',
            'products.*.unit_price.min' => 'Cena jednostkowa musi wynosić co najmniej 0.01 zł.',
        ];
    }
}
