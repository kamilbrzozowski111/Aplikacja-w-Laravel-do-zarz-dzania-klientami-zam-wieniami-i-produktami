<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zarządzanie Klientami') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Dodaj nowego klienta
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
                <form method="GET" action="{{ route('clients.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj po nazwisku, emailu, telefonie lub mieście..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Szukaj
                        </button>
                        @if(request('search'))
                            <a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-300">
                                Wyczyść
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela Klientów -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">Imię i Nazwisko / Nazwa</th>
                                <th class="px-6 py-3.5">Kontakt</th>
                                <th class="px-6 py-3.5">Lokalizacja</th>
                                <th class="px-6 py-3.5 text-center">Zamówienia</th>
                                <th class="px-6 py-3.5 text-right">Akcje</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($clients as $client)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono font-medium text-gray-900">#{{ $client->id }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        <a href="{{ route('clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ $client->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900">{{ $client->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $client->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        <div>{{ $client->city ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $client->address }} {{ $client->postal_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $client->orders_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3 whitespace-nowrap font-medium text-xs">
                                        <a href="{{ route('clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900">Podgląd</a>
                                        <a href="{{ route('clients.edit', $client) }}" class="text-amber-600 hover:text-amber-900">Edytuj</a>
                                        <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć klienta {{ $client->name }}? Zostaną również usunięte powiązane zamówienia.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Nie znaleziono żadnych klientów.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($clients->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

