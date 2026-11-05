<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::withCount('orders')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(8)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()
            ->route('clients.index')
            ->with('success', "Klient \"{$client->name}\" został pomyślnie utworzony.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['orders' => function ($q) {
            $q->latest()->with('products');
        }]);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()
            ->route('clients.index')
            ->with('success', "Dane klienta \"{$client->name}\" zostały zaktualizowane.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $name = $client->name;
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "Klient \"{$name}\" został pomyślnie usunięty.");
    }
}
