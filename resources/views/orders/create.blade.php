<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-700 text-sm font-medium">
                &larr; Wróć do listy
            </a>
            <span class="text-gray-300">|</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nowe Zamówienie') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('orders.store') }}" id="order-form" class="space-y-6">
                @csrf

                <!-- Karta Główna Zamówienia -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Informacje o Zamówieniu</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Klient -->
                        <div class="md:col-span-2">
                            <x-input-label for="client_id" :value="__('Klient *')" />
                            <select id="client_id" name="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="">-- Wybierz klienta z listy --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                        </div>

                        <!-- Data zamówienia -->
                        <div>
                            <x-input-label for="order_date" :value="__('Data zamówienia *')" />
                            <x-text-input id="order_date" name="order_date" type="date" class="mt-1 block w-full" :value="old('order_date', date('Y-m-d'))" required />
                            <x-input-error class="mt-2" :messages="$errors->get('order_date')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status zamówienia *')" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                <option value="nowe" {{ old('status', 'nowe') === 'nowe' ? 'selected' : '' }}>Nowe</option>
                                <option value="w realizacji" {{ old('status') === 'w realizacji' ? 'selected' : '' }}>W realizacji</option>
                                <option value="zrealizowane" {{ old('status') === 'zrealizowane' ? 'selected' : '' }}>Zrealizowane</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <!-- Notatki -->
                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Uwagi do zamówienia')" />
                            <x-text-input id="notes" name="notes" type="text" class="mt-1 block w-full" :value="old('notes')" placeholder="np. Pilna wysyłka kurierem, faktura proforma..." />
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                    </div>
                </div>

                <!-- Karta Pozycji Zamówienia -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 p-6 sm:p-8 space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Pozycje Zamówienia *</h3>
                            <p class="text-xs text-gray-500">Dodaj co najmniej jeden produkt do zamówienia.</p>
                        </div>
                        <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 border border-indigo-300 rounded-md font-semibold text-xs text-indigo-700 hover:bg-indigo-100 transition">
                            + Dodaj produkt
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('products')" />

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm divide-y divide-gray-200" id="items-table">
                            <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Produkt</th>
                                    <th class="px-4 py-3 w-32 text-center">Ilość (szt.)</th>
                                    <th class="px-4 py-3 w-36 text-right">Cena jedn. (zł)</th>
                                    <th class="px-4 py-3 w-36 text-right">Wartość (zł)</th>
                                    <th class="px-4 py-3 w-16 text-center">Usuń</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="items-body">
                                <!-- Dynamiczne wiersze -->
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold text-gray-900">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right uppercase text-xs">Łączna kwota do zapłaty:</td>
                                    <td class="px-4 py-3 text-right text-base text-indigo-700 whitespace-nowrap" id="order-grand-total">0,00 zł</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Przyciski Zapisz / Anuluj -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Anuluj
                    </a>
                    <x-primary-button>
                        {{ __('Złóż zamówienie') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dane produktów w JSON do dynamicznego formularza -->
    <script>
        const availableProducts = @json($products);
        const oldItems = @json(old('products', []));

        let rowIndex = 0;

        function formatMoney(amount) {
            return Number(amount).toLocaleString('pl-PL', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' zł';
        }

        function calculateTotals() {
            let grandTotal = 0;
            document.querySelectorAll('#items-body tr').forEach(row => {
                const qtyInput = row.querySelector('.item-quantity');
                const priceInput = row.querySelector('.item-price');
                const totalCell = row.querySelector('.item-total');

                const qty = parseFloat(qtyInput?.value) || 0;
                const price = parseFloat(priceInput?.value) || 0;
                const lineTotal = qty * price;

                grandTotal += lineTotal;
                if (totalCell) {
                    totalCell.textContent = formatMoney(lineTotal);
                }
            });

            document.getElementById('order-grand-total').textContent = formatMoney(grandTotal);
        }

        function createItemRow(selectedProductId = '', quantity = 1, unitPrice = '') {
            const tbody = document.getElementById('items-body');
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition item-row';
            tr.dataset.index = rowIndex;

            let productOptions = '<option value="">-- Wybierz produkt --</option>';
            availableProducts.forEach(prod => {
                const isSelected = String(prod.id) === String(selectedProductId) ? 'selected' : '';
                productOptions += `<option value="${prod.id}" data-price="${prod.price}" ${isSelected}>${prod.name} (${prod.sku}) - ${formatMoney(prod.price)}</option>`;
            });

            tr.innerHTML = `
                <td class="px-4 py-3">
                    <select name="products[${rowIndex}][product_id]" class="item-product w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                        ${productOptions}
                    </select>
                </td>
                <td class="px-4 py-3 text-center">
                    <input type="number" name="products[${rowIndex}][quantity]" value="${quantity}" min="1" step="1" class="item-quantity w-24 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </td>
                <td class="px-4 py-3 text-right">
                    <input type="number" name="products[${rowIndex}][unit_price]" value="${unitPrice}" min="0.01" step="0.01" class="item-price w-28 text-right rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                </td>
                <td class="px-4 py-3 text-right font-semibold text-gray-800 item-total whitespace-nowrap">
                    0,00 zł
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 text-lg font-bold transition">&times;</button>
                </td>
            `;

            tbody.appendChild(tr);

            const productSelect = tr.querySelector('.item-product');
            const priceInput = tr.querySelector('.item-price');
            const qtyInput = tr.querySelector('.item-quantity');
            const removeBtn = tr.querySelector('.remove-item-btn');

            productSelect.addEventListener('change', function() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const defaultPrice = selectedOption.getAttribute('data-price');
                if (defaultPrice) {
                    priceInput.value = parseFloat(defaultPrice).toFixed(2);
                }
                calculateTotals();
            });

            qtyInput.addEventListener('input', calculateTotals);
            priceInput.addEventListener('input', calculateTotals);

            removeBtn.addEventListener('click', function() {
                if (document.querySelectorAll('#items-body tr').length > 1) {
                    tr.remove();
                    calculateTotals();
                } else {
                    alert('Zamówienie musi zawierać przynajmniej jeden produkt.');
                }
            });

            rowIndex++;
            calculateTotals();
        }

        document.getElementById('add-item-btn').addEventListener('click', function() {
            createItemRow();
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (oldItems && Object.keys(oldItems).length > 0) {
                Object.values(oldItems).forEach(item => {
                    createItemRow(item.product_id, item.quantity, item.unit_price);
                });
            } else {
                createItemRow();
            }
        });
    </script>
</x-app-layout>

