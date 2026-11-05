<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                    &larr; Wróć do listy
                </a>
                <span class="text-gray-300">|</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Szczegóły Zamówienia: ') }} <span class="font-mono text-indigo-700">{{ $order->order_number }}</span>
                </h2>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 transition">
                    Edytuj
                </a>
                <a href="{{ route('orders.print', $order) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Drukuj PDF (z kodami QR)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Podsumowanie i Dane Klienta -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informacje o Zamówieniu -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Informacje Ogólne</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-xs uppercase font-semibold text-gray-500">Numer zamówienia:</span>
                            <p class="font-mono font-bold text-gray-900 mt-1 text-base">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <span class="text-xs uppercase font-semibold text-gray-500">Data zamówienia:</span>
                            <p class="font-medium text-gray-800 mt-1">
                                {{ $order->order_date ? $order->order_date->format('d.m.Y') : $order->created_at->format('d.m.Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="text-xs uppercase font-semibold text-gray-500">Status:</span>
                            <div class="mt-1">
                                @if($order->status === 'zrealizowane')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        Zrealizowane
                                    </span>
                                @elseif($order->status === 'w realizacji')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        W realizacji
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        Nowe
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="sm:col-span-3 pt-3 border-t border-gray-100 flex items-baseline justify-between">
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-500">Łączna kwota do zapłaty:</span>
                                <div class="text-2xl font-black text-indigo-700 mt-0.5">
                                    {{ number_format($order->total_price, 2, ',', ' ') }} zł
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">Ceny zawierają podatek VAT</span>
                        </div>
                        @if($order->notes)
                            <div class="sm:col-span-3 pt-2">
                                <span class="text-xs uppercase font-semibold text-gray-500">Uwagi do zamówienia:</span>
                                <p class="mt-1 p-3 bg-gray-50 rounded text-gray-700 text-sm border border-gray-100">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Wizytówka Klienta -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Dane Nabywcy</h3>
                    @if($order->client)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-500">Klient:</span>
                                <p class="font-bold text-gray-900 mt-0.5">
                                    <a href="{{ route('clients.show', $order->client) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $order->client->name }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-500">E-mail:</span>
                                <p class="text-gray-700 font-mono text-xs mt-0.5">{{ $order->client->email }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-500">Telefon:</span>
                                <p class="text-gray-700 mt-0.5">{{ $order->client->phone ?? 'Nie podano' }}</p>
                            </div>
                            <div>
                                <span class="text-xs uppercase font-semibold text-gray-500">Adres dostawy:</span>
                                <p class="text-gray-700 mt-0.5">
                                    {{ $order->client->address ?? 'Brak adresu' }}<br>
                                    {{ $order->client->postal_code }} {{ $order->client->city }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Brak powiązanego klienta.</p>
                    @endif
                </div>
            </div>

            <!-- Tabela Pozycji Zamówienia -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        Zawartość Zamówienia ({{ $order->products->count() }} {{ trans_choice('pozycja|pozycje|pozycji', $order->products->count()) }})
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">Lp.</th>
                                <th class="px-4 py-3">Produkt</th>
                                <th class="px-4 py-3">Kod SKU</th>
                                <th class="px-4 py-3 text-center">Ilość szt.</th>
                                <th class="px-4 py-3 text-right">Cena jedn.</th>
                                <th class="px-4 py-3 text-right">Wartość pozycji</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($order->products as $index => $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-center font-mono text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->description)
                                            <p class="text-xs text-gray-400 font-normal line-clamp-1 mt-0.5">{{ Str::limit($product->description, 70) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-indigo-700 font-bold whitespace-nowrap">
                                        {{ $product->sku }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-900 whitespace-nowrap">
                                        {{ $product->pivot->quantity }} szt.
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        {{ number_format($product->pivot->unit_price, 2, ',', ' ') }} zł
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 whitespace-nowrap">
                                        {{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2, ',', ' ') }} zł
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold text-gray-900 border-t-2 border-gray-200">
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-right uppercase text-xs">Razem brutto:</td>
                                <td class="px-4 py-3 text-right text-base text-indigo-700 whitespace-nowrap">
                                    {{ number_format($order->total_price, 2, ',', ' ') }} zł
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

