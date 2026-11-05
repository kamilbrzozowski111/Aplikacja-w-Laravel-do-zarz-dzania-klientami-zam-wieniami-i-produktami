<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa produktu jest wymagana.',
            'name.max' => 'Nazwa produktu nie może przekraczać 255 znaków.',
            'sku.required' => 'Kod SKU produktu jest wymagany.',
            'sku.unique' => 'Produkt o podanym kodzie SKU już istnieje w bazie.',
            'sku.max' => 'Kod SKU nie może przekraczać 50 znaków.',
            'price.required' => 'Cena produktu jest wymagana.',
            'price.numeric' => 'Cena musi być poprawną liczbą (np. 49.99).',
            'price.min' => 'Cena produktu musi wynosić co najmniej 0.01 zł.',
            'stock.required' => 'Stan magazynowy jest wymagany.',
            'stock.integer' => 'Stan magazynowy musi być liczbą całkowitą.',
            'stock.min' => 'Stan magazynowy nie może być ujemny.',
        ];
    }
}
