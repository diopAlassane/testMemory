<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\View\View;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Reservation::class);

        $search = $request->get('search', '');

        $reservations = Reservation::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.reservations.index',
            compact('reservations', 'search')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Reservation::class);

        $clients = Client::pluck('id', 'id');

        return view('app.reservations.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();

        $reservation = Reservation::create($validated);

        return redirect()
            ->route('reservations.edit', $reservation)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Reservation $reservation): View
    {
        $this->authorize('view', $reservation);

        return view('app.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Reservation $reservation): View
    {
        $this->authorize('update', $reservation);

        $clients = Client::pluck('id', 'id');

        return view('app.reservations.edit', compact('reservation', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReservationUpdateRequest $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->authorize('update', $reservation);

        $validated = $request->validated();

        $reservation->update($validated);

        return redirect()
            ->route('reservations.edit', $reservation)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
