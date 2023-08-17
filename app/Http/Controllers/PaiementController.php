<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Paiement;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PaiementStoreRequest;
use App\Http\Requests\PaiementUpdateRequest;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Paiement::class);

        $search = $request->get('search', '');

        $paiements = Paiement::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.paiements.index', compact('paiements', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Paiement::class);

        $clients = Client::pluck('id', 'id');

        return view('app.paiements.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaiementStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Paiement::class);

        $validated = $request->validated();

        $paiement = Paiement::create($validated);

        return redirect()
            ->route('paiements.edit', $paiement)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Paiement $paiement): View
    {
        $this->authorize('view', $paiement);

        return view('app.paiements.show', compact('paiement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Paiement $paiement): View
    {
        $this->authorize('update', $paiement);

        $clients = Client::pluck('id', 'id');

        return view('app.paiements.edit', compact('paiement', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PaiementUpdateRequest $request,
        Paiement $paiement
    ): RedirectResponse {
        $this->authorize('update', $paiement);

        $validated = $request->validated();

        $paiement->update($validated);

        return redirect()
            ->route('paiements.edit', $paiement)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Paiement $paiement
    ): RedirectResponse {
        $this->authorize('delete', $paiement);

        $paiement->delete();

        return redirect()
            ->route('paiements.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
