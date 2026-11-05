<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienie {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
        .order-badge {
            text-align: right;
        }
        .order-number {
            font-size: 16px;
            font-weight: bold;
            color: #4f46e5;
            font-family: monospace;
        }
        .parties-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .parties-col {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .info-row {
            margin-bottom: 3px;
        }
        .info-label {
            font-weight: bold;
            color: #4b5563;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .items-table td {
            padding: 8px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
            font-size: 10.5px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .qr-cell {
            text-align: center;
            width: 85px;
            padding: 4px !important;
        }
        .qr-img {
            width: 65px;
            height: 65px;
            display: block;
            margin: 0 auto;
        }
        .qr-code-label {
            font-family: monospace;
            font-size: 9px;
            color: #4f46e5;
            font-weight: bold;
            margin-top: 2px;
        }
        .product-name {
            font-weight: bold;
            color: #111827;
        }
        .product-sku {
            font-family: monospace;
            font-size: 9.5px;
            color: #4f46e5;
        }
        .total-row td {
            font-weight: bold;
            background-color: #f9fafb;
            font-size: 11px;
        }
        .grand-total {
            font-size: 14px;
            color: #4f46e5;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .status-nowe { background-color: #fef3c7; color: #92400e; }
        .status-w_realizacji { background-color: #dbeafe; color: #1e40af; }
        .status-zrealizowane { background-color: #d1fae5; color: #065f46; }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        .signature-box {
            width: 45%;
            border-top: 1px dashed #9ca3af;
            text-align: center;
            padding-top: 5px;
            font-size: 10px;
            color: #6b7280;
        }
        .notes-box {
            background-color: #fdfdfd;
            border-left: 3px solid #6366f1;
            padding: 8px 12px;
            margin-bottom: 20px;
            font-size: 10px;
            color: #4b5563;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="title">ZAMÓWIENIE I KARTA KOMPLETACJI</div>
                <div class="subtitle">System Zarządzania Zamówieniami i Magazynem &bull; Wydruk z kodami QR</div>
            </td>
            <td class="order-badge">
                <div class="order-number">{{ $order->order_number }}</div>
                <div class="subtitle">Data: {{ $order->order_date ? $order->order_date->format('d.m.Y') : $order->created_at->format('d.m.Y') }}</div>
                <div style="margin-top: 4px;">
                    @php
                        $statusClass = match($order->status) {
                            'zrealizowane' => 'status-zrealizowane',
                            'w realizacji' => 'status-w_realizacji',
                            default => 'status-nowe'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ strtoupper($order->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="parties-table">
        <tr>
            <td class="parties-col">
                <div class="section-title">Sprzedawca / Wystawca</div>
                <div class="info-row"><span class="info-label">Firma:</span> System Zarządzania Laravel Sp. z o.o.</div>
                <div class="info-row"><span class="info-label">Adres:</span> ul. Informatyczna 10, 00-001 Warszawa</div>
                <div class="info-row"><span class="info-label">NIP:</span> 123-456-78-90</div>
                <div class="info-row"><span class="info-label">Kontakt:</span> kontakt@system-laravel.local</div>
            </td>
            <td style="width: 4%;"></td>
            <td class="parties-col">
                <div class="section-title">Nabywca / Odbiorca</div>
                @if($order->client)
                    <div class="info-row"><span class="info-label">Klient:</span> <strong>{{ $order->client->name }}</strong></div>
                    <div class="info-row"><span class="info-label">E-mail:</span> {{ $order->client->email }}</div>
                    <div class="info-row"><span class="info-label">Telefon:</span> {{ $order->client->phone ?? 'Nie podano' }}</div>
                    <div class="info-row"><span class="info-label">Adres:</span> {{ $order->client->address ?? '-' }}, {{ $order->client->postal_code }} {{ $order->client->city }}</div>
                @else
                    <div class="info-row">Brak przypisanego klienta</div>
                @endif
            </td>
        </tr>
    </table>

    @if($order->notes)
        <div class="notes-box">
            <strong>Uwagi do zamówienia:</strong> {{ $order->notes }}
        </div>
    @endif

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">Lp.</th>
                <th>Nazwa towaru / SKU</th>
                <th class="text-center" style="width: 90px;">Kod QR pozycji</th>
                <th style="width: 50px;" class="text-center">Ilość</th>
                <th style="width: 80px;" class="text-right">Cena jedn.</th>
                <th style="width: 90px;" class="text-right">Wartość brutto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itemsWithQr as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product->name }}</div>
                        <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                    </td>
                    <td class="qr-cell">
                        <img src="{{ $item->qr_code }}" class="qr-img" alt="QR {{ $item->qr_content }}">
                        <div class="qr-code-label">Kod: {{ $item->qr_content }}</div>
                    </td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ $item->quantity }} szt.
                    </td>
                    <td class="text-right">
                        {{ number_format($item->unit_price, 2, ',', ' ') }} zł
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ number_format($item->total, 2, ',', ' ') }} zł
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right" style="text-transform: uppercase;">
                    Łączna kwota do zapłaty (brutto):
                </td>
                <td class="text-right grand-total">
                    {{ number_format($order->total_price, 2, ',', ' ') }} zł
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="signatures-table">
        <tr>
            <td class="signature-box">
                Osoba upoważniona do wystawienia / Magazynier
            </td>
            <td style="width: 10%;"></td>
            <td class="signature-box">
                Podpis odbiorcy zamówienia
            </td>
        </tr>
    </table>

    <div class="footer">
        Wydrukowano z systemu Laravel &bull; Kody QR w formacie {ID_ZAMÓWIENIA}|{ID_PRODUKTU} służą do automatycznej kompletacji i weryfikacji magazynowej. &bull; Strona 1/1
    </div>
</body>
</html>

