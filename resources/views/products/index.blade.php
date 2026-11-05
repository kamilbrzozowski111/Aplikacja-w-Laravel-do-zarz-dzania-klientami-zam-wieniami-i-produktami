<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zarządzanie Produktami') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Dodaj nowy produkt
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

            <!-- Filtrowanie / Wyszukiwanie -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po nazwie, kodzie SKU lub opisie..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Szukaj
                        </button>
                        @if(request('search'))
                            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-300">
                                Wyczyść
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela Produktów -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">SKU</th>
                                <th class="px-6 py-3.5">Nazwa Produktu</th>
                                <th class="px-6 py-3.5">Cena</th>
                                <th class="px-6 py-3.5 text-center">Stan Magazynowy</th>
                                <th class="px-6 py-3.5 text-center">W zamówieniach</th>
                                <th class="px-6 py-3.5 text-right">Akcje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono font-medium text-gray-900">#{{ $product->id }}</td>
                                    <td class="px-6 py-4 font-mono text-xs text-indigo-700 font-semibold">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $product->name }}
                                        </a>
                                        @if($product->description)
                                            <p class="text-xs text-gray-500 font-normal line-clamp-1 mt-0.5">{{ Str::limit($product->description, 60) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                        {{ number_format($product->price, 2, ',', ' ') }} zł
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($product->stock > 10)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                {{ $product->stock }} szt.
                                            </span>
                                        @elseif($product->stock > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                {{ $product->stock }} szt. (niski stan)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                                0 szt. (brak)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $product->orders_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap font-medium text-xs">
                                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">Podgląd</a>
                                        <a href="{{ route('products.edit', $product) }}" class="text-amber-600 hover:text-amber-900">Edytuj</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć produkt {{ $product->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        Nie znaleziono żadnych produktów.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

