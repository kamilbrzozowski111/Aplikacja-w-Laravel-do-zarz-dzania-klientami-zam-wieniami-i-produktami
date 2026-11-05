<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('clients.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                    &larr; Wróć do listy
                </a>
                <span class="text-gray-300">|</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Szczegóły Klienta: ') }} {{ $client->name }}
                </h2>
            </div>
            <div class="space-x-3">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700">
                    Edytuj dane
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Karta Danych Klienta -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Informacje Podstawowe</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Imię i Nazwisko / Firma:</span>
                        <p class="text-base font-bold text-gray-900 mt-1">{{ $client->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Adres E-mail:</span>
                        <p class="text-base text-gray-800 font-mono mt-1">{{ $client->email }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Numer Telefonu:</span>
                        <p class="text-base text-gray-800 mt-1">{{ $client->phone ?? 'Nie podano' }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Adres Zamieszkania / Siedziba:</span>
                        <p class="text-base text-gray-800 mt-1">{{ $client->address ?? 'Brak adresu' }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Miejscowość i Kod:</span>
                        <p class="text-base text-gray-800 mt-1">
                            {{ $client->city ?? '-' }} {{ $client->postal_code ? "({$client->postal_code})" : '' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Data Rejestracji:</span>
                        <p class="text-base text-gray-800 mt-1">{{ $client->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Historia Zamówień Klienta -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        Historia Zamówień Klienta ({{ $client->orders->count() }})
                    </h3>
                </div>

                @if($client->orders->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Numer zamówienia</th>
                                    <th class="px-4 py-3">Data zamówienia</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Pozycje w zamówieniu</th>
                                    <th class="px-4 py-3 text-right">Wartość całkowita</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($client->orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $order->order_number }}</td>
                                        <td class="px-4 py-3">{{ $order->order_date->format('d.m.Y') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            {{ $order->products->pluck('name')->join(', ') }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                            {{ number_format($order->total_price, 2, ',', ' ') }} zł
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 py-4">Ten klient nie złożył jeszcze żadnych zamówień.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
