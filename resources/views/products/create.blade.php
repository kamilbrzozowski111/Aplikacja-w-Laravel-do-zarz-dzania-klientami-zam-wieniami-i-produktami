<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                &larr; Wróć do listy
            </a>
            <span class="text-gray-300">|</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dodaj Nowy Produkt') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8">
                <form method="POST" action="{{ route('products.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nazwa produktu -->
                    <div>
                        <x-input-label for="name" :value="__('Nazwa produktu *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="np. Klawiatura mechaniczna RGB" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- SKU -->
                    <div>
                        <x-input-label for="sku" :value="__('Kod SKU * (unikalny identyfikator)')" />
                        <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full font-mono uppercase" :value="old('sku')" placeholder="np. PRD-KB-001" required />
                        <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                    </div>

                    <!-- Cena i Stan Magazynowy w 2 kolumnach -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="price" :value="__('Cena jednostkowa brutto (PLN) *')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('price')" placeholder="np. 149.99" required />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>
                        <div>
                            <x-input-label for="stock" :value="__('Stan magazynowy (sztuki) *')" />
                            <x-text-input id="stock" name="stock" type="number" step="1" min="0" class="mt-1 block w-full" :value="old('stock', 0)" placeholder="np. 25" required />
                            <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                        </div>
                    </div>

                    <!-- Opis produktu -->
                    <div>
                        <x-input-label for="description" :value="__('Opis produktu')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Wprowadź szczegółowy opis produktu, parametry techniczne...">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <!-- Przyciski akcji -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Anuluj
                        </a>
                        <x-primary-button>
                            {{ __('Zapisz produkt') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

