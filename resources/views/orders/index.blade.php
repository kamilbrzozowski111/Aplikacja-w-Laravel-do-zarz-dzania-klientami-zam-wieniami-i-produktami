<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zarządzanie Zamówieniami') }}
            </h2>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Nowe zamówienie
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Komunikaty powodzenia -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r shadow-sm">
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filtrowanie i Wyszukiwanie -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po numerze zamówienia, kliencie lub e-mailu..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    
                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Wszystkie statusy</option>
                        <option value="nowe" {{ request('status') === 'nowe' ? 'selected' : '' }}>Nowe</option>
                        <option value="w realizacji" {{ request('status') === 'w realizacji' ? 'selected' : '' }}>W realizacji</option>
                        <option value="zrealizowane" {{ request('status') === 'zrealizowane' ? 'selected' : '' }}>Zrealizowane</option>
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Filtruj
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-300">
                                Wyczyść
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela Zamówień -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">Numer zamówienia</th>
                                <th class="px-6 py-3.5">Klient</th>
                                <th class="px-6 py-3.5">Data złożenia</th>
                                <th class="px-6 py-3.5 text-center">Pozycje</th>
                                <th class="px-6 py-3.5 text-right">Wartość brutto</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-right">Akcje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-900 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->client)
                                            <a href="{{ route('clients.show', $order->client) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $order->client->name }}
                                            </a>
                                            <div class="text-xs text-gray-500">{{ $order->client->email }}</div>
                                        @else
                                            <span class="text-gray-400">Brak klienta</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $order->order_date ? $order->order_date->format('d.m.Y') : $order->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            {{ $order->products_count }} {{ trans_choice('pozycja|pozycje|pozycji', $order->products_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                        {{ number_format($order->total_price, 2, ',', ' ') }} zł
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($order->status === 'zrealizowane')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                Zrealizowane
                                            </span>
                                        @elseif($order->status === 'w realizacji')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                W realizacji
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                Nowe
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap font-medium text-xs">
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Podgląd</a>
                                        <a href="{{ route('orders.edit', $order) }}" class="text-amber-600 hover:text-amber-900">Edytuj</a>
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć zamówienie {{ $order->order_number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Nie znaleziono żadnych zamówień.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

