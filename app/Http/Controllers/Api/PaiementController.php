<?php

namespace App\Http\Controllers\Api;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaiementResource;
use App\Http\Resources\PaiementCollection;
use App\Http\Requests\PaiementStoreRequest;
use App\Http\Requests\PaiementUpdateRequest;

class PaiementController extends Controller
{
    public function index(Request $request): PaiementCollection
    {
        $this->authorize('view-any', Paiement::class);

        $search = $request->get('search', '');

        $paiements = Paiement::search($search)
            ->latest()
            ->paginate();

        return new PaiementCollection($paiements);
    }

    public function store(PaiementStoreRequest $request): PaiementResource
    {
        $this->authorize('create', Paiement::class);

        $validated = $request->validated();

        $paiement = Paiement::create($validated);

        return new PaiementResource($paiement);
    }

    public function show(Request $request, Paiement $paiement): PaiementResource
    {
        $this->authorize('view', $paiement);

        return new PaiementResource($paiement);
    }

    public function update(
        PaiementUpdateRequest $request,
        Paiement $paiement
    ): PaiementResource {
        $this->authorize('update', $paiement);

        $validated = $request->validated();

        $paiement->update($validated);

        return new PaiementResource($paiement);
    }

    public function destroy(Request $request, Paiement $paiement): Response
    {
        $this->authorize('delete', $paiement);

        $paiement->delete();

        return response()->noContent();
    }
}
