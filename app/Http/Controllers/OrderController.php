<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('client')->withCount('products')->latest('id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($clientQuery) use ($search) {
                      $clientQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(8)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('orders.create', compact('clients', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = DB::transaction(function () use ($request) {
            $nextId = (Order::max('id') ?? 0) + 1;
            $orderNumber = 'ZAM/' . date('Y') . '/' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'client_id' => $request->validated('client_id'),
                'order_date' => $request->validated('order_date'),
                'status' => $request->validated('status'),
                'notes' => $request->validated('notes'),
                'total_price' => 0.00,
            ]);

            $pivotData = [];
            foreach ($request->input('products', []) as $item) {
                $pivotData[$item['product_id']] = [
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (float) $item['unit_price'],
                ];
            }

            $order->products()->attach($pivotData);
            $order->recalculateTotalPrice();

            return $order;
        });

        return redirect()
            ->route('orders.index')
            ->with('success', "Zamówienie {$order->order_number} zostało pomyślnie utworzone.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['client', 'products']);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load(['client', 'products']);
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('orders.edit', compact('order', 'clients', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::transaction(function () use ($request, $order) {
            $order->update([
                'client_id' => $request->validated('client_id'),
                'order_date' => $request->validated('order_date'),
                'status' => $request->validated('status'),
                'notes' => $request->validated('notes'),
            ]);

            $syncData = [];
            foreach ($request->input('products', []) as $item) {
                $syncData[$item['product_id']] = [
                    'quantity' => (int) $item['quantity'],
                    'unit_price' => (float) $item['unit_price'],
                ];
            }

            $order->products()->sync($syncData);
            $order->recalculateTotalPrice();
        });

        return redirect()
            ->route('orders.index')
            ->with('success', "Zamówienie {$order->order_number} zostało pomyślnie zaktualizowane.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;

        DB::transaction(function () use ($order) {
            $order->products()->detach();
            $order->delete();
        });

        return redirect()
            ->route('orders.index')
            ->with('success', "Zamówienie {$orderNumber} zostało pomyślnie usunięte.");
    }

    /**
     * Generate and stream PDF document with QR codes for each ordered item.
     */
    public function printPdf(Request $request, Order $order, \App\Services\QrCodeService $qrCodeService)
    {
        $order->load(['client', 'products']);

        $itemsWithQr = $order->products->map(function ($product) use ($order, $qrCodeService) {
            $qrContent = "{$order->id}|{$product->id}";
            $qrCode = $qrCodeService->generateOrderItemQr($order->id, $product->id);

            return (object) [
                'product' => $product,
                'quantity' => $product->pivot->quantity,
                'unit_price' => $product->pivot->unit_price,
                'total' => $product->pivot->quantity * $product->pivot->unit_price,
                'qr_code' => $qrCode,
                'qr_content' => $qrContent,
            ];
        });

        if ($request->has('preview') || $request->input('format') === 'html') {
            return view('orders.pdf', [
                'order' => $order,
                'itemsWithQr' => $itemsWithQr,
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.pdf', [
            'order' => $order,
            'itemsWithQr' => $itemsWithQr,
        ])->setPaper('a4', 'portrait');

        $filename = 'Zamowienie_' . str_replace(['/', '\\'], '_', $order->order_number) . '.pdf';

        if ($request->has('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
