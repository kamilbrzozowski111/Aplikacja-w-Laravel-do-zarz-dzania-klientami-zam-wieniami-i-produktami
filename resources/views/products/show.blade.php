<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('products.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                    &larr; Wróć do listy
                </a>
                <span class="text-gray-300">|</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Szczegóły Produktu: ') }} {{ $product->name }}
                </h2>
            </div>
            <div class="space-x-3">
                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700">
                    Edytuj dane
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Karta Danych Produktu -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Informacje Podstawowe</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Nazwa Produktu:</span>
                        <p class="text-base font-bold text-gray-900 mt-1">{{ $product->name }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Kod SKU:</span>
                        <p class="text-base text-indigo-700 font-mono font-bold mt-1">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Cena brutto:</span>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($product->price, 2, ',', ' ') }} zł</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Stan Magazynowy:</span>
                        <div class="mt-1">
                            @if($product->stock > 10)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    {{ $product->stock }} szt. (dostępny)
                                </span>
                            @elseif($product->stock > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                    {{ $product->stock }} szt. (niski stan magazynowy)
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                    0 szt. (chwilowy brak w magazynie)
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Liczba powiązanych zamówień:</span>
                        <p class="text-base font-semibold text-gray-800 mt-1">{{ $product->orders->count() }} zamówień</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase font-semibold text-gray-500">Data Dodania:</span>
                        <p class="text-base text-gray-800 mt-1">{{ $product->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="md:col-span-3">
                        <span class="text-xs uppercase font-semibold text-gray-500">Opis Produktu:</span>
                        <div class="mt-1 p-4 bg-gray-50 rounded-md border border-gray-100 text-sm text-gray-700 whitespace-pre-line">
                            {{ $product->description ?: 'Brak dodatkowego opisu dla tego produktu.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historia Zamówień z tym Produktem -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        Historia Zamówień z tym Produktem ({{ $product->orders->count() }})
                    </h3>
                </div>

                @if($product->orders->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Numer zamówienia</th>
                                    <th class="px-4 py-3">Klient</th>
                                    <th class="px-4 py-3">Data zamówienia</th>
                                    <th class="px-4 py-3 text-center">Ilość sztuk</th>
                                    <th class="px-4 py-3 text-right">Cena jednostkowa</th>
                                    <th class="px-4 py-3 text-right">Wartość pozycji</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($product->orders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-mono font-medium text-gray-900">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            @if($order->client)
                                                <a href="{{ route('clients.show', $order->client) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                    {{ $order->client->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">Brak klienta</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $order->order_date ? $order->order_date->format('d.m.Y') : $order->created_at->format('d.m.Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-semibold text-gray-900">
                                            {{ $order->pivot->quantity }} szt.
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            {{ number_format($order->pivot->unit_price, 2, ',', ' ') }} zł
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                                            {{ number_format($order->pivot->quantity * $order->pivot->unit_price, 2, ',', ' ') }} zł
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($order->status === 'zrealizowane')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    {{ $order->status }}
                                                </span>
                                            @elseif($order->status === 'w realizacji')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $order->status }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                    {{ $order->status }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 py-4 text-center">
                        Produkt nie pojawił się jeszcze w żadnym zamówieniu.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

