<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\PaiementCollection;

class ClientPaiementsController extends Controller
{
    public function index(Request $request, Client $client): PaiementCollection
    {
        $this->authorize('view', $client);

        $search = $request->get('search', '');

        $paiements = $client
            ->paiements()
            ->search($search)
            ->latest()
            ->paginate();

        return new PaiementCollection($paiements);
    }

    public function store(Request $request, Client $client): PaiementResource
    {
        $this->authorize('create', Paiement::class);

        $validated = $request->validate([
            'price' => ['required', 'numeric'],
            'print_pdf' => ['required', 'max:255', 'string'],
        ]);

        $paiement = $client->paiements()->create($validated);

        return new PaiementResource($paiement);
    }
}
