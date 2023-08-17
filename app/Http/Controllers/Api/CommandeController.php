<?php

namespace App\Http\Controllers\Api;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommandeResource;
use App\Http\Resources\CommandeCollection;
use App\Http\Requests\CommandeStoreRequest;
use App\Http\Requests\CommandeUpdateRequest;

class CommandeController extends Controller
{
    public function index(Request $request): CommandeCollection
    {
        $this->authorize('view-any', Commande::class);

        $search = $request->get('search', '');

        $commandes = Commande::search($search)
            ->latest()
            ->paginate();

        return new CommandeCollection($commandes);
    }

    public function store(CommandeStoreRequest $request): CommandeResource
    {
        $this->authorize('create', Commande::class);

        $validated = $request->validated();

        $commande = Commande::create($validated);

        return new CommandeResource($commande);
    }

    public function show(Request $request, Commande $commande): CommandeResource
    {
        $this->authorize('view', $commande);

        return new CommandeResource($commande);
    }

    public function update(
        CommandeUpdateRequest $request,
        Commande $commande
    ): CommandeResource {
        $this->authorize('update', $commande);

        $validated = $request->validated();

        $commande->update($validated);

        return new CommandeResource($commande);
    }

    public function destroy(Request $request, Commande $commande): Response
    {
        $this->authorize('delete', $commande);

        $commande->delete();

        return response()->noContent();
    }
}
