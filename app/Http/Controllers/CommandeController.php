<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CommandeStoreRequest;
use App\Http\Requests\CommandeUpdateRequest;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Commande::class);

        $search = $request->get('search', '');

        $commandes = Commande::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.commandes.index', compact('commandes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Commande::class);

        $clients = Client::pluck('id', 'id');

        return view('app.commandes.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommandeStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Commande::class);

        $validated = $request->validated();

        $commande = Commande::create($validated);

        return redirect()
            ->route('commandes.edit', $commande)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Commande $commande): View
    {
        $this->authorize('view', $commande);

        return view('app.commandes.show', compact('commande'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Commande $commande): View
    {
        $this->authorize('update', $commande);

        $clients = Client::pluck('id', 'id');

        return view('app.commandes.edit', compact('commande', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CommandeUpdateRequest $request,
        Commande $commande
    ): RedirectResponse {
        $this->authorize('update', $commande);

        $validated = $request->validated();

        $commande->update($validated);

        return redirect()
            ->route('commandes.edit', $commande)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Commande $commande
    ): RedirectResponse {
        $this->authorize('delete', $commande);

        $commande->delete();

        return redirect()
            ->route('commandes.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
