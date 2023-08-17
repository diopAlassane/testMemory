<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommandeResource;
use App\Http\Resources\CommandeCollection;

class ClientCommandesController extends Controller
{
    public function index(Request $request, Client $client): CommandeCollection
    {
        $this->authorize('view', $client);

        $search = $request->get('search', '');

        $commandes = $client
            ->commandes()
            ->search($search)
            ->latest()
            ->paginate();

        return new CommandeCollection($commandes);
    }

    public function store(Request $request, Client $client): CommandeResource
    {
        $this->authorize('create', Commande::class);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'date'],
            'drink' => ['required', 'max:255', 'string'],
            'dessert' => ['required', 'max:255', 'string'],
            'food' => ['required', 'max:255', 'string'],
        ]);

        $commande = $client->commandes()->create($validated);

        return new CommandeResource($commande);
    }
}
