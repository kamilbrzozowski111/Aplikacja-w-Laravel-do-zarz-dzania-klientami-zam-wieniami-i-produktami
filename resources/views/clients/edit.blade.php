<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                &larr; Wróć do listy
            </a>
            <span class="text-gray-300">|</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edycja Klienta: ') }} {{ $client->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8">
                <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Imię i Nazwisko / Nazwa -->
                    <div>
                        <x-input-label for="name" :value="__('Imię i Nazwisko / Nazwa klienta *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Adres E-mail *')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $client->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Telefon -->
                    <div>
                        <x-input-label for="phone" :value="__('Numer telefonu')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $client->phone)" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <!-- Adres -->
                    <div>
                        <x-input-label for="address" :value="__('Ulica i numer lokalu')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $client->address)" />
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <!-- Miasto i Kod pocztowy w 2 kolumnach -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="city" :value="__('Miejscowość / Miasto')" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $client->city)" />
                            <x-input-error class="mt-2" :messages="$errors->get('city')" />
                        </div>
                        <div>
                            <x-input-label for="postal_code" :value="__('Kod pocztowy')" />
                            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', $client->postal_code)" />
                            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                        </div>
                    </div>

                    <!-- Przyciski akcji -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('clients.show', $client) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                            Zobacz profil klienta
                        </a>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('clients.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Anuluj
                            </a>
                            <x-primary-button>
                                {{ __('Zapisz zmiany') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
